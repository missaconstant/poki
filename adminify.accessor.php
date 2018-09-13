<?php

    require __DIR__ . '/core/config.php';
    require __DIR__ . '/core/modele.php';

    define('ROOT', pathinfo(__FILE__, PATHINFO_DIRNAME) . '/');
    define('WROOT', pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME));

    class Adminify extends modele
    {
        public static $inited = false;
        public static $cfg = [];
        public static $mdl = false;

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
        }

        public static function get($categoryname, $contentid=false, $limits=false)
        {
            if (!self::$inited) self::init();
            try {
                $whereclause = $contentid ? "WHERE id=$contentid" : "";
                $limitclause = $limits && count($limits) > 1 && !$contentid ? "LIMIT ". $limits[0] .", ". $limits[1] : '';
                $limitclause = $limits && count($limits) == 1 && !$contentid ? "LIMIT ". $limits[0] : '';

                $q = modele::$bd->query("SELECT * FROM adm_app_$categoryname $whereclause $limits");
                $r = $q->fetchAll(PDO::FETCH_OBJ);
                $q->closeCursor();
                
                return $contentid ? (count($r) ? $r[0] : false) : $r;
            }
            catch (Exception $e) {
                return false;
            }
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