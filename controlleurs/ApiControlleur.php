<?php

    class ApiControlleur extends controlleur
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

        public function toggle()
        {
            if ($this->loadModele()->toggleApi(Posts::get(0), Posts::get(1))) {
                $this->json_success("Change done !");
                exit();
            }
            else {
                $this->json_error("An error occured ! Try again later.");
                exit();
            }
        }

        public function set()
        {
            $allowed = Posts::post(['allowed']) ? trim(implode(',',$_POST['allowed'])) : '';
            $category = Posts::post('category');

            if ($this->loadModele()->modifierApi($category, $allowed)) {
                $this->json_success("Done !", ["newtoken" => Posts::getCSRFTokenValue()]); exit();
            }
            else {
                $this->json_error("An error occured ! Try again later.", ["newtoken" => Posts::getCSRFTokenValue()]);
            }
        }

        public function changeKey()
        {
            $category = Posts::get(0);
            $action = Posts::get([1]) == false ? false : (Posts::get(1)=='unset' ? true : false);

            if ($newapikey = $this->loadModele()->changerApi($category, $action)) {
                $this->json_success("Done !", ["newapikey" => $newapikey]); exit();
            }
            else {
                $this->json_error("An error occured ! Try again later.", ["newtoken" => Posts::getCSRFTokenValue()]);
            }
        }

        public function watch()
        {
            header("Content-Type: application/json");

            $category = Posts::get([0]) ? Posts::get(0) : '';
            $content = Posts::get([1]) ? Posts::get(1) : false;
            $apikey = Posts::get([2]) ? Posts::get(2) : '';

            if (!$content || $content=='all') {
                $this->apiSurvey($category, 'get', $apikey);
                $contents = $this->loadModele('contents')->trouverTousContents($category);
                if ($contents && count($content)) {
                    $this->json_answer(["error" => 0, "contents" => $contents]);
                    exit();
                }
                else {
                    $this->apiError(404, 'Nothing found !');
                }
            }
            else if ($content && $content != 'all') {
                $this->apiSurvey($category, 'get-one', $apikey);
                $found = $this->loadModele('contents')->trouverContents($category, $content);
                if ($found) {
                    $this->json_answer(["error" => 0, "contents" => [$found]]);
                    exit();
                }
                else {
                    $this->apiError(404, 'Nothing found !');
                }
            }
        }

        public function do()
        {
            header("Content-Type: application/json");
            Posts::disableCSRF();
            
            $action = Posts::post(['action']) ? Posts::post('action') : false;
            $categoryname = Posts::post(['target']) ? Posts::post('target') : false;
            $apikey = Posts::post(['apikey']) ? Posts::post('apikey') : false;
            $contentid = Posts::post(['targetid']) ? Posts::post('targetid') : false;
            $settings = $this->loadModele('settings')->get('apipermissiontypes');
            
            if (!in_array($action, explode(',', $settings->content)) || !$categoryname) {
                $this->apiError(403, "Action not permitted or target omitted !");
            }

            $this->apiSurvey($categoryname, $action, $apikey);

            if ($action == 'delete') {
                if ($this->loadModele('contents')->supprimerContents($categoryname, $contentid)) {
                    $this->json_answer(["error" => 0, "message" => "Action done !"]);
                }
                else {
                    $this->apiError(404, "Error found !");
                }
            }
            else if ($action == 'edit' || $action == 'add') {
                $content = [];
                $queryfields = ['action', 'target', 'targetid', 'apikey'];

                foreach ($_POST as $k => $value) {
                    if ($k != 'id' && $k != 'active' && $k != 'added_at') {
                        if (!in_array($k, $queryfields)) {
                            $content[$k] = $value;
                        }
                    }
                    else {
                        $this->apiError(402, "You are not allowed to edit this ". $k);
                        break;
                    }
                }
                
                if ($this->loadModele('contents')->{ $action=='edit' ? 'modifierContent':'creerContent' }($content, $categoryname, $contentid)) {
                    $this->json_answer(["error" => 0, "message" => "Action done !"]);
                }
                else {
                    $this->apiError(404, "Error found !");
                }
            }
        }

        public function apiSurvey($categoryname, $permission, $apikey) {
            $api = $this->loadModele()->trouverApi($categoryname);
            if ($api && $api->active == '1') {
                if (!in_array($permission, explode(',', $api->allowed)) && $api->apikey != 'noset' && $apikey != $api->apikey) {
                    $this->apiError(401, "You don't have this permission level without api right key.");
                }
                else if (!in_array($permission, explode(',', $api->allowed)) && $api->apikey == 'noset') {
                    $this->apiError(402, "This authorisation level is disabled.");
                }
            }
            else if ($api && $api->active != '1') {
                $this->apiError(405, "Access not allowed !");
            }
            else {
                $this->apiError(403, "You're asking for impossible !");
            }
        }

        private function apiError($errortype, $message)
        {
            $this->json_answer([
                "error" => $errortype,
                "message" => $message
            ]);
            exit();
        }

        public function apiTest()
        {
            /*$values = [
                "action" => "delete"
            ];
// exit($_SERVER['SERVER_PORT']);
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, 'http://localhost:9000');
            // curl_setopt($c, CURLOPT_PORT , $_SERVER['SERVER_PORT']);
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($c, CURLOPT_TIMEOUT, 5);
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($values));
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            $r = curl_exec($c);
            curl_close($c);

            var_dump($r);*/
            $this->render('app/apitest');
        }
    }
    