<?php

    session_start();

    require __DIR__ . '/core/config.php';
    require __DIR__ . '/core/routes.php';
    require __DIR__ . '/core/modele.php';
    require __DIR__ . '/core/sessions.php';
    require __DIR__ . '/core/posts.php';
    require __DIR__ . '/core/controlleur.php';
    require __DIR__ . '/modeles/ContentsModele.php';
    require __DIR__ . '/controlleurs/ContentsControlleur.php';

    define('ROOT', pathinfo(__FILE__, PATHINFO_DIRNAME) . '/');
    define('WROOT', pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME));

    class Poki extends modele
    {
        public static $inited = false;
        public static $cfg = [];
        public static $mdl = false;
        public static $ctr = false;

        public static function init()
        {
            if (file_exists(__DIR__ . '/statics/config.php')) {
                require __DIR__ . '/statics/config.php';
                Config::$db_user = $dbuser;
                Config::$db_name = $dbname;
                Config::$db_password = $dbpass;
                Config::$db_host = $dbhost;
                if (self::$mdl = new modele()) {
                    self::$inited = true;
                }
            }
            self::$mdl = new ContentsModele();
            self::$ctr = new ContentsControlleur();
        }

        public static function get($categoryname, $contentid=false, $filter=false, $joins=false)
        {
            if (!self::$inited) self::init();
            return self::$mdl->{$contentid ? 'trouverContentsAccessor' : 'trouverTousContents'}($categoryname, $joins, ($contentid ? $contentid : $filter));
        }

        public static function push()
        {
            if (!self::$inited) self::init();

            ob_start();
            self::$ctr->add();
            $return = ob_get_contents();
            ob_clean();
            return $return;
        }

        public static function getPushRoute()
        {
            return Routes::find('content-add');
        }

        public static function getCSRF()
        {
            return Posts::getCSRF();
        }

        public static function parseFiles($filestring)
        {
            $filesnames = explode('|', $filestring);
            $files = [];
            foreach ($filesnames as $key => $filename) {
                if (strlen(trim($filename))) {
                    $files[] = Config::$fields_files_webpath . $filename;
                }
            }
            return $files;
        }

        public static function parseHtml($html)
        {
            return htmlspecialchars_decode($html);
        }
    }