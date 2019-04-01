<?php

    /**
     * @class ApiControlleur
     * @extends controlleur
     * Manage api actions
     */
    class ApiControlleur extends controlleur
    {
        private $cfg;
        private $usr;

        public function __construct()
        {
            if (file_exists(ROOT . 'statics/config.php') && !isset($dbuser) && !isset($dbpass))
            {
                include ROOT . 'statics/config.php';
                Config::$db_user = $dbuser;
                Config::$db_host = $dbhost;
                Config::$db_password = $dbpass;
                Config::$db_name = $dbname;
            }
            $this->usr = $this->loadController('users');
            $this->cfg = $this->loadController('config');
        }

        /**
         * Toogle Api, enable or disable
         * @return json_message
         */
        public function toggle()
        {
            if ($this->loadModele()->toggleApi(Posts::get(0), Posts::get(1)))
            {
                $this->json_success("Change done !");
                exit();
            }
            else {
                $this->json_error("An error occured ! Try again later.");
                exit();
            }
        }

        /**
         * Sets api access right
         * @return json_message
         */
        public function set()
        {
            $allowed = Posts::post(['allowed']) ? trim(implode(',',$_POST['allowed'])) : '';
            $category = Posts::post('category');

            if ($this->loadModele()->modifierApi($category, $allowed))
            {
                $this->json_success("Done !", ["newtoken" => Posts::getCSRFTokenValue()]); exit();
            }
            else {
                $this->json_error("An error occured ! Try again later.", ["newtoken" => Posts::getCSRFTokenValue()]);
            }
        }

        /**
         * Creates or Changes api key
         * @return json_message
         */
        public function changeKey()
        {
            $category = Posts::get(0);
            $action = Posts::get([1]) == false ? false : (Posts::get(1)=='unset' ? true : false);

            if ($newapikey = $this->loadModele()->changerApi($category, $action))
            {
                $this->json_success("Done !", ["newapikey" => $newapikey]); exit();
            }
            else {
                $this->json_error("An error occured ! Try again later.", ["newtoken" => Posts::getCSRFTokenValue()]);
            }
        }

        /**
         * Api version 1
         * @return void
         */
        public function v1()
        {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json");

            Posts::disableCSRF();

            $category = Posts::get([0]) ? Posts::get(0) : '';
            $action = Posts::get([1]) ? Posts::get(1) : false;
            $content = Posts::get([2]) ? Posts::get(2) : 0;
            $apikey = Posts::get([3]) ? Posts::get(3) : '';

            # get actions
            if ($action && $action == 'get')
            {
                if ($content && $content != 'all')
                {
                    # get one
                    $this->apiSurvey($category, 'get-one', $apikey);
                    $found = $this->loadModele('contents')->trouverContents($category, $content, true);
                    if ($found)
                    {
                        $this->json_answer(["error" => 0, "contents" => [$found]]);
                        exit();
                    }
                    else {
                        $this->apiError(404, 'Nothing found !');
                    }
                }
                else {
                    # get all | get
                    $this->apiSurvey($category, 'get', $apikey);
                    $contents = $this->loadModele('contents')->trouverTousContents($category, true);
                    if ($contents && count($content))
                    {
                        $this->json_answer(["error" => 0, "contents" => $contents]);
                        exit();
                    }
                    else {
                        $this->apiError(404, 'Nothing found !');
                    }
                }
            }
            # cud action : create, update, delete, find
            else if ($action && in_array($action, ['add', 'edit', 'delete', 'find']))
            {
                $categoryname = $category;
                $apikey = Posts::post(['apikey']) ? Posts::post('apikey') : false;
                $contentid = $content;

                if (!$categoryname)
                {
                    $this->apiError(403, "Action not permitted or target omitted !");
                }

                $this->apiSurvey($categoryname, $action, $apikey);

                # delete action
                if ($action == 'delete')
                {
                    if ($this->loadModele('contents')->supprimerContents($categoryname, $contentid))
                    {
                        $this->json_answer(["error" => 0, "message" => "Action done !"]);
                    }
                    else {
                        $this->apiError(404, "Error found !");
                    }
                }
                # add and edit action
                else if ($action == 'edit' || $action == 'add')
                {
                    $content = [];
                    $queryfields = ['apikey'];
    
                    foreach ($_POST as $k => $value)
                    {
                        if ($k != 'id' && $k != 'added_at')
                        {
                            if (!in_array($k, $queryfields))
                            {
                                $content[$k] = $value;
                            }
                        }
                        else {
                            $this->apiError(402, "You are not allowed to edit this ". $k);
                            break;
                        }
                    }
                    
                    if ($this->loadModele('contents')->{ $action=='edit' ? 'modifierContent':'creerContent' }($content, $categoryname, $contentid))
                    {
                        $this->json_answer(["error" => 0, "message" => "Action done !"]);
                    }
                    else {
                        $this->apiError(404, "Error found !");
                    }
                }
                else if ($action && $action == 'find')
                {
                    $wherestring = [];
                     
                    foreach ($_POST as $k => $value)
                    {
                        if ($k == 'apikey') continue;
                        
                        $wherestring[] = "$k='$value'";
                    }
                    
                    if ($contents = $this->loadModele('contents')->trouverTousContents($categoryname, false, [ "where" => implode(' AND ', $wherestring) ]))
                    {
                        $this->json_answer(["error" => 0, "contents" => $contents]);
                        exit();
                    }
                    else {
                        $this->apiError(404, "Error found !");
                    }
                }
            }
            else
            {
                if ($category && $category == 'app')
                {
                    $plg_id  = $action;
                    $plg_act = $content;
                    $get_val = [];
                    $i       = 3;
                    
                    while (Posts::get([$i]))
                    {
                        $get_val[] = Posts::get($i);
                        $i++;
                    }
                    
                    $resonse = (object) $this->loadController('listener')->plugin($plg_id, $plg_act, $get_val, Posts::post());
                    
                    if ($resonse->error)
                    {
                        $this->apiError(403, $resonse->message);
                    }
    
                    exit();
                }
                else {
                    $this->apiError(403, "You're asking for impossible !");
                }
            }
        }

        /**
         * Api level access survey
         * @param category asked category
         * @param permission access level asked
         * @param apikey api key provided
         */
        public function apiSurvey($categoryname, $permission, $apikey)
        {
            $api = $this->loadModele()->trouverApi($categoryname);
            if ($api && $api->active == '1')
            {
                if (!in_array($permission, explode(',', $api->allowed)) && $api->apikey != 'noset' && $apikey != $api->apikey)
                {
                    $this->apiError(401, "You don't have this permission level without an api key.");
                }
                else if (!in_array($permission, explode(',', $api->allowed)) && $api->apikey == 'noset')
                {
                    $this->apiError(402, "This authorisation level is disabled.");
                }
            }
            else if ($api && $api->active != '1')
            {
                $this->apiError(405, "Access not allowed !");
            }
            else {
                $this->apiError(403, "You're asking for impossible !");
            }
        }

        /**
         * Returns api error according to error type
         * @param errortype
         * @param messsage Message to return from error
         */
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

        /**
         * Go to external server to get information about remote asker
         */
        public function getAskerInfo()
        {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json");
            
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, 'http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($c, CURLOPT_TIMEOUT, 2);
            $r = curl_exec($c);
            curl_close($c);
            exit($r);
        }
    }
    