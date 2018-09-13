<?php

    class ConfigControlleur extends controlleur
    {

        public $statics;
        /**
        * Construct method. update db connexion values
        * @return void
        */
        public function __construct()
        {
            if (file_exists(ROOT . 'statics/config.php')) {
                include ROOT . 'statics/config.php';
                Config::$db_user = $dbuser;
                Config::$db_host = $dbhost;
                Config::$db_password = $dbpass;
                Config::$db_name = $dbname;
            }

            if (file_exists(ROOT . 'statics/statics.php')) {
                include_once ROOT . 'statics/statics.php';
                $this->statics = getStatics();
            }
        }

        /**
        * Checks wether the app is configured
        * @return bool
        */
        public function isConfigured()
        {
            return ($this->checkConfigFile() ? 1:0) + ($this->checkDefaultTables() ? 1:0);
        }

        /**
        * Try connection to database
        * @param $dbhost, database hostname
        * @param $dbname, database name
        * @param $user, database username
        * @param $pass, database password
        * @return bool
        */
        public function connectDatabase($dbhost, $dbname, $user, $pass)
        {
            try {
                $db = new PDO("mysql:host=$dbhost; dbname=$dbname", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                return $db;
            }
            catch (Exception $e) {
                return false;
            }
        }

        /**
         * Check whether config file is already setted
         */
        public function checkConfigFile()
        {
            if (file_exists(ROOT . 'statics/config.php')) {
                require ROOT . 'statics/config.php';

                if (
                    !isset($dbname) || !strlen(trim($dbname)) ||
                    !isset($dbuser) || !strlen(trim($dbuser)) ||
                    !isset($dbhost) || !strlen(trim($dbhost)) ||
                    !isset($dbpass)
                ) {
                    return false;
                }
            }
            else {
                return false;
            }
            return true;
        }

        /**
         * check wheter default tables (default, users and settings are setted)
         */
        public function checkDefaultTables()
        {
            return $this->loadModele()->existsDefaultTables();
        }

        /**
        * Middleware for configuration checking
        * @param $redirect, should redirect when configured ?
        * @return void
        */
        public function configSurvey($redirtoifok='/', $path=false)
        {
            $val = $this->isConfigured();
            if ($val < 2) {
                if ($val == 0 && $path != 'install') {
                    $this->redirTo(Routes::find('install'));
                }
                else if ($val == 1 && $path != 'make-install') {
                    $this->redirTo(Routes::find('makeinstall'));
                }
            }
            else {
                if ($redirtoifok) {
                    $this->redirTo(Routes::find($redirtoifok));
                }
            }
        }

        /**
        * Creates config.php file
        * @param $dbhost, database hostname
        * @param $dbname, database name
        * @param $user, database username
        * @param $pass, database password
        * @return void
        */
        public function setConfigs($dbhost, $dbname, $user, $pass, $appfolder)
        {
            $config = "<?php \n\n\t\$dbuser = \"$user\"; \n\n\t\$dbhost = \"$dbhost\"; \n\n\t\$dbpass = \"$pass\"; \n\n\t\$dbname = \"$dbname\"; \n\n\t\$appfolder = \"$appfolder\";";
            return file_put_contents(ROOT . 'statics/config.php', $config);
        }

        /**
        * Creates defaults table, user table and settings tables
        * @return void
        */
        public function setDefaultsTables($username, $password)
        {
            $ok1 = $this->loadModele()->createUserTable($username, $password);
            $ok2 = $this->loadModele()->createDefaultTables();
            return $ok1 && $ok2;
        }
    }
    