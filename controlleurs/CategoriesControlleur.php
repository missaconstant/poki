<?php

    namespace Poki;

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
            $admin = $this->usr->loginSurvey(false, 'login');

            if ($admin->role != 'admin') {
                $this->json_error('You cannot create category !');
                exit();
            }

            $name       = Posts::post('name');
            $label      = Posts::post('label');
            $edition    = Posts::post('editing');
            $category   = (object) ["name" => $name, "label" => $label, "oldname" => $edition];

            if (!strlen(trim($name)))
            {
                $this->json_error('Category name cannot be empty !', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            else if (preg_match("#\s#", trim($name)))
            {
                $this->json_error('A category should not contain whitespace !', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            else if ($this->loadModele()->existsCategory($name) && $name != $edition)
            {
                $this->json_error('A category with this name already exists !', ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            // else if ($name == $edition)
            // {
            //     $this->json_error('No change done !', ["newtoken" => Posts::getCSRFTokenValue()]);
            //     exit();
            // }
            else {
                if ($this->loadModele()->{ $edition != '0' ? 'modifierCategory':'creerCategory' }($category))
                {
                    $this->loadModele('params')->createCategoryParams($category);
                    $this->json_success('Category created !', [ "newtoken" => Posts::getCSRFTokenValue(), "name" => $name, "label" => $label ]);
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
            $admin = $this->usr->loginSurvey(false, 'login');

            if ($admin->role != 'admin') {
                $this->json_error('You cannot create category !');
                exit();
            }

            $name = Posts::get(0);

            if ($this->loadModele()->supprimerCategory($name)) {
                $this->loadModele('params')->deleteCategoryParams($name);
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
                $categorie      = $this->loadModele()->trouverCategory(Posts::get(0));
                $categorie_infs = $this->loadModele()->trouverTousCategories(Posts::get(0));
                $allfields      = $this->loadModele()->trouverTousCategoryFields();
                $category_api   = $this->loadModele('api')->trouverApi(Posts::get(0));
                $apitypes       = $this->loadModele('settings')->get('apipermissiontypes');

                $this->render('app/category-show', [
                    "admin"                 => $admin,
                    "pagetitle"             => "Category: " . Posts::get(0),
                    "categories"            => $this->list(),
                    "category_name"         => Posts::get(0),
                    "category_label"        => $categorie_infs ? $categorie_infs['label'] : '',
                    "category_fields"       => $categorie,
                    "all_category_fileds"   => $allfields,
                    "apitypes"              => explode(',', $apitypes->content),
                    "api"                   => $category_api,
                    "pluglist"              => $this->loadController('listener')->loadPlugins()
                ]);
            }
        }

        public function addField($edition=false)
        {
            $admin = $this->usr->loginSurvey(false, 'login');

            if ($admin->role != 'admin')
            {
                $this->json_error('You cannot create category !');
                exit();
            }

            $fields = [];
            $field_main = (object) [
                "name"      => Posts::post('fieldname'),
                "type"      => Posts::post('fieldtype'),
                "fieldlabel"=> Posts::post('fieldlabel'),
                "category"  => Posts::post('category'),
                "oldname"   => Posts::post('editing')
            ];
            $fields[] = $field_main;

            if (Posts::post(['fieldname_1'])) {
                $i = 1;
                while (Posts::post(['fieldname_' . $i])) {
                    $fields[] = (object) [
                        "name"      => Posts::post('fieldname_' . $i),
                        "type"      => Posts::post('fieldtype_' . $i),
                        "fieldlabel"=> Posts::post('fieldlabel_' . $i),
                        "category"  => Posts::post('category'),
                        "oldname"   => Posts::post('editing')
                    ];
                    $i++;
                }
            }

            $this->checkFieldValues($fields);
            
            if ($this->loadModele()->{ $edition ? 'modifierCategoryField' : 'creerCategoryField' }($edition ? $field_main : $fields))
            {
                // set field(s) label in fields params
                $this->loadModele('params')->setFieldLabel( Posts::post('category'), $fields );

                // answering
                $this->json_success('New fields added to !', [
                    "newtoken" => Posts::getCSRFTokenValue(),
                    "addedfields" => $fields
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
            $admin = $this->usr->loginSurvey(false, 'login');

            if ($admin->role != 'admin') {
                $this->json_error('You cannot create category !');
                exit();
            }
            
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

        public function linkField()
        {
            if ($admin = $this->usr->loginSurvey(false, false, false)) {
                $category   = Posts::get(0);
                $field      = Posts::get(1);
                $linkto     = Posts::get(2) != '0' ? Posts::get(2) . '/' . Posts::get(3) : $linkto = '0';

                if ($this->loadModele('params')->setLink($category, $field, $linkto)) {
                    echo $this->json_success("Link correctely setted !");
                }
                else {
                    echo $this->json_error("An error occurred. Please try again later.");
                }
            }
            else {
                echo $this->json_error("Vous devez être connecter pour effectuer cette operation !");
            }
        }

        public function form()
        {
            $name       = Posts::get(0);
            $contentid  = Posts::get([1]) ? Posts::get(1) : false;
            $content    = false;

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
                    "content" => $content,
                    "pluglist" => $this->loadController('listener')->loadPlugins()
                ]);
            }
        }

        public function listContents()
        {
            $contentsNumber = 0;
            $content        = null;
            $contentMax     = 10;
            $name           = Posts::get(0);
            $limit          = Posts::get([1]) && strlen(Posts::get(1)) ? (((Posts::get(1)-1)*$contentMax) . ", $contentMax") : "0, $contentMax";
            $search         = Posts::get([2]) && strlen(Posts::get(2)) ? Posts::get(2) : false;

            $this->cfg->configSurvey(false);
            $admin = $this->usr->loginSurvey(false, 'login');

            if (!$this->loadModele()->existsCategory($name))
            {
                $this->redirTo(Routes::find('dashboard'));
                exit();
            }
            else {
                $category   = $this->loadModele()->trouverCategory($name);
                $params     = $this->loadModele('params')->getCategoryParams( $name );
                if (!$search)
                {
                    $contents = $this->loadController('contents')->list($name, ["limit" => $limit]);
                    $contentsNumber = $this->loadModele('contents')->compterContents($name);
                }
                else {
                    $contents = $this->loadModele('contents')->searchInCategory(CATEG_PREFIX . $name, $search, $limit);
                    $contentsNumber = $this->loadModele('contents')->searchInCategory(CATEG_PREFIX . $name, $search, false, true);
                    $contentsNumber = $contentsNumber[0]['countlines'];
                }

                $this->render('app/category-list', [
                    "admin" => $admin,
                    "pagetitle" => 'Category contents: <a href="'. Routes::find('category-show') . '/' . $name .'">' . $name . '</a>',
                    "categories" => $this->list(),
                    "category_name" => $name,
                    "category_fields" => $category,
                    "category_params" => $params,
                    "nbrcontents" => $contentsNumber,
                    "maxcontentperpage" => $contentMax,
                    "actualcontentspage" => Posts::get([1]) ? Posts::get(1) : 1,
                    "issearch" => $search,
                    "contents" => $contents,
                    "pluglist" => $this->loadController('listener')->loadPlugins()
                ]);
            }
        }

        public function searchCountKeyInAllCategories()
        {
            try {
                $kwd = htmlentities(Posts::get(0));
                $categories = $this->loadModele()->trouverTousCategories();
                $found = [];
                for ($i=0; $i<count($categories); $i++) {
                    $found[] = [
                        "category" => str_replace(CATEG_PREFIX, '', $categories[$i]['field']),
                        "list" => $this->loadModele('contents')->searchInCategory($categories[$i]['field'], $kwd, false, true)
                    ];
                }
                echo $this->json_answer($found);
            } catch (Exception $e) {
                exit(json_encode(["error" => true, "message" => $e->getMessage()]));
            }
        }

        public function checkFieldValues($fields)
        {
            foreach ($fields as $k => $field) {
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
        }

        private function checkFieldExists($field, $category) {
            return $this->loadModele()->existsFieldCategory($category, $field);
        }
    }
    