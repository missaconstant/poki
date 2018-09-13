<?php

    class CategoriesControlleur extends controlleur
    {
        private $usr;
        private $cfg;

        public function __construct()
        {
            if (file_exists(ROOT . 'statics/config.php') && !isset($dbuser) && !isset($dbpass)) {
                include ROOT . 'statics/config.php';
                Config::$db_user = $dbuser;
                Config::$db_host = $dbhost;
                Config::$db_password = $dbpass;
                Config::$db_name = $dbname;
            }
            $this->usr = $this->loadController('users');
            $this->cfg = $this->loadController('config');
        }

        public function create()
        {
            $name = Posts::post('name');
            $edition = Posts::post('editing');
            $category = (object) ["name" => $name, "oldname" => $edition];
            if (!strlen(trim($name))) {
                $this->json_error('Category name cannot be empty !', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            else if (preg_match("#\s#", trim($name))) {
                $this->json_error('A category should not contain whitespace !', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            else if ($this->loadModele()->existsCategory($name) && $name != $edition) {
                $this->json_error('A category with this name already exists !', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            else if ($name == $edition) {
                $this->json_error('No change done !', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            else {
                if ($this->loadModele()->{ $edition != '0' ? 'modifierCategory':'creerCategory' }($category)) {
                    $this->json_success('Category created !', ["newtoken" => Posts::getCSRFTokenValue(), "name" => $name]);
                    exit();
                }
                else {
                    $this->json_error('An error occured ! Try again later.', ["newtoken" => Posts::getCSRFTokenValue()]);
                    exit();
                }
            }
        }

        public function delete()
        {
            $name = Posts::get(0);

            if ($this->loadModele()->supprimerCategory($name)) {
                $this->json_success('Deleted !');
                exit();
            }
            else {
                $this->json_error('An error occured ! Try again later.', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
        }
        
        public function list()
        {
            $list = $this->loadModele()->trouverTousCategories();
            return $list;
        }

        public function show()
        {

            $this->cfg->configSurvey(false);
            $admin = $this->usr->loginSurvey(false, 'login');

            if (!Posts::get([0]) || !$this->loadModele()->existsCategory(Posts::get(0))) {
                $this->redirTo(Routes::find('dashboard'));
                exit();
            }
            else if ($admin->role != 'admin') {
                $this->redirTo(Routes::find('category-list') .'/'. Posts::get(0));
            }
            else {
                $categorie = $this->loadModele()->trouverCategory(Posts::get(0));
                $category_api = $this->loadModele('api')->trouverApi(Posts::get(0));
                $apitypes = $this->loadModele('settings')->get('apipermissiontypes');
                $this->render('app/category-show', [
                    "admin" => $admin,
                    "pagetitle" => "Category: " . Posts::get(0),
                    "categories" => $this->list(),
                    "category_name" => Posts::get(0),
                    "category_fields" => $categorie,
                    "apitypes" => explode(',', $apitypes->content),
                    "api" => $category_api
                ]);
            }
        }

        public function addField($edition=false)
        {
            $field = (object) [
                "name" => Posts::post('fieldname'),
                "type" => Posts::post('fieldtype'),
                "category" => Posts::post('category'),
                "oldname" => Posts::post('editing')
            ];
            $this->checkFieldValues($field);
            
            if ($this->loadModele()->{ $edition ? 'modifierCategoryField' : 'creerCategoryField' }($field)) {
                $this->json_success('New field added to !', [
                    "newtoken" => Posts::getCSRFTokenValue(),
                    "addedtype" => $field->type,
                    "addedname" => $field->name
                ]);
                exit();
            }
            else {
                $this->json_error('An error occured. Try again.', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
        }

        public function editField()
        {
            $this->addField(true);
        }

        public function deleteField()
        {
            $field = Posts::get(1);
            $category = Posts::get(0);
            if ($this->loadModele()->supprimerCategoryField($field, $category)) {
                $this->json_success('Field completely deleted !');
                exit();
            }
            else {
                $this->json_success('An error occured ! Try again later.');
                exit();
            }
        }

        public function form()
        {
            $name = Posts::get(0);
            $contentid = Posts::get([1]) ? Posts::get(1) : false;
            $content = false;

            $this->cfg->configSurvey(false);
            $admin = $this->usr->loginSurvey(false, 'login');

            if (!$this->loadModele()->existsCategory($name) || ($contentid && !($content = $this->loadModele('contents')->trouverContents($name, $contentid)))) {
                $this->redirTo(Routes::find('dashboard'));
                exit();
            }
            else {
                // var_dump($content);exit;
                $categorie = $this->loadModele()->trouverCategory($name);
                $this->render('app/category-form', [
                    "admin" => $admin,
                    "pagetitle" => "Category form: " . $name,
                    "categories" => $this->list(),
                    "category_name" => $name,
                    "category_fields" => $categorie,
                    "content" => $content
                ]);
            }
        }

        public function listContents()
        {
            $name = posts::get(0);

            $this->cfg->configSurvey(false);
            $admin = $this->usr->loginSurvey(false, 'login');

            if (!$this->loadModele()->existsCategory($name)) {
                $this->redirTo(Routes::find('dashboard'));
                exit();
            }
            else {
                $category = $this->loadModele()->trouverCategory($name);
                $contents = $this->loadController('contents')->list($name);
                $this->render('app/category-list', [
                    "admin" => $admin,
                    "pagetitle" => 'Category contents: <a href="'. Routes::find('category-show') . '/' . $name .'">' . $name . '</a>',
                    "categories" => $this->list(),
                    "category_name" => $name,
                    "category_fields" => $category,
                    "contents" => $contents
                ]);
            }
        }

        public function checkFieldValues($field)
        {
            $sname = explode(' ', (trim($field->name)));
            
            if (count($sname) > 1 || strlen($field->name)==0) {
                $this->json_error('Field name might not contain whitespaces !', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            else if ($field->type=='0') {
                $this->json_error('You have to choose a type for this field !', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            else if (!isset(Helpers::$types[$field->type])) {
                $this->json_error('This type is not allowed and should break your app !', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            else if ($this->checkFieldExists($field->name, $field->category)) {
                if ($field->oldname != $field->name) {
                    $this->json_error('This field name already exists. Choose another one.', ["newtoken" => Posts::getCSRFTokenValue()]);
                    exit();
                }
            }
        }

        private function checkFieldExists($field, $category) {
            return $this->loadModele()->existsFieldCategory($category, $field);
        }
    }
    