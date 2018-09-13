<?php

    class ContentsControlleur extends controlleur
    {
        private $cfg;
        private $usr;

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

        public function add()
        {
            $categoryname = Posts::post('category');
            $category = $this->loadModele('categories')->trouverCategory($categoryname);
            $edition = Posts::post('editing');

            $content = $this->getContentObject($category);

            if ($this->loadModele()->{ $edition!='0' && strlen(trim($edition)) ? 'modifierContent':'creerContent' }($content, $categoryname, $edition)) {
                $this->json_success("Content saved succefully !", ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            else {
                $this->json_error("An error occured. Please try again later.", ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
        }

        public function list($category)
        {
            return $this->loadModele()->trouverTousContents($category);
        }

        public function delete()
        {
            $contentid = Posts::get(0);
            $categoryname = Posts::get(1);

            if ($this->loadModele()->supprimerContents($categoryname, $contentid)) {
                $this->json_success("Content deleted !");
                exit();
            }
            else {
                $this->json_error("An error occured. Please try again later.");
                exit();
            }
        }

        private function getContentObject($categoryItems) {
            $object = [];
            foreach ($categoryItems as $k => $item) {
                if (!in_array($item['name'], ['id', 'active', 'added_at'])) {
                    if (Posts::post([$item['name']])) {
                        $object[$item['name']] = Posts::post($item['name']);
                    }
                    else {
                        $object = false;
                        break;
                    }
                }
            }
            return $object;
        }

        public function test()
        {
            var_dump(Posts::get([1]));
        }
    }
    