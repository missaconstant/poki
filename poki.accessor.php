<?php

    if (session_status() == PHP_SESSION_NONE) session_start();

    require __DIR__ . '/core/config.php';
    require __DIR__ . '/core/routes.php';
    require __DIR__ . '/core/modele.php';
    require __DIR__ . '/core/sessions.php';
    require __DIR__ . '/core/posts.php';
    require __DIR__ . '/core/controlleur.php';
    require __DIR__ . '/modeles/ContentsModele.php';
    require __DIR__ . '/controlleurs/ContentsControlleur.php';
    require __DIR__ . '/controlleurs/ListenerControlleur.php';

    define('ROOT', pathinfo(__FILE__, PATHINFO_DIRNAME) . '/');
    define('WROOT', pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME));

    class Poki extends modele
    {
        public static $inited = false;
        public static $cfg = [];
        public static $mdl = false;
        public static $ctr = false;
        public static $listen = true;

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
            Posts::disableCSRF();
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

            self::$ctr->add(false);
            $return = ob_get_contents();

            ob_clean();
            return json_decode($return);
        }

        public static function getPushRoute()
        {
            return Routes::find('content-add');
        }

        public static function create($categoryname, $content)
        {
            if (!self::$inited) self::init();
            $result = self::$mdl->creerContent($content, $categoryname);

            # fire oncreate event
            if (self::$listen && $result)
                self::$ctr->fire('create', [
                    "contentid" => null,
                    "content"   => $content,
                    "categoryname" => $categoryname
                ]);
            # end of event

            return $result;
        }

        public static function edit($categoryname, $content, $id)
        {
            if (!self::$inited) self::init();
            $result = self::$mdl->modifierContent($content, $categoryname, $id);

            # fire onupdate event
            if (self::$listen && $result)
                self::$ctr->fire('update', [
                    "contentid" => $id,
                    "content"   => $content,
                    "categoryname" => $categoryname
                ]);
            # end of event
                
            return $result;
        }

        public static function delete($categoryname, $id)
        {
            if (!self::$inited) self::init();
            $result = self::$mdl->supprimerContents($categoryname, $id);
            
            # fire ondelete event
            if (self::$listen && $result)
                self::$ctr->fire('delete', [
                    "contentid" => $id,
                    "categoryname" => $categoryname
                ]);
            # end of event
                
            return $result;
        }

        public static function upload($name)
        {
            if (!self::$inited) self::init();
            ob_start();

            self::$fls->uploadFile($name, false);
            $return = ob_get_contents();

            ob_clean();
            return json_decode($return);
        }

        public function search($categoryname, $keyword, $limit, $count)
        {
            if (!self::$inited) self::init();
            return self::$mdl->searchInCategory($categoryname, $keyword, $limit, $count);
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