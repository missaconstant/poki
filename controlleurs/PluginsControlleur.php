<?php

    namespace Poki;

    class PluginsControlleur extends controlleur
    {
        private $cfg;
        private $usr;

        public function __construct()
        {
            $this->cfg = $this->loadController('config');
			$this->usr = $this->loadController('users');
        }

        private function loadPlugins($plugid=false)
		{
			$plugins = json_decode(file_get_contents(ROOT . 'appfiles/listener/plugins.poki'), true);
			return $plugid ? (isset($plugins[$plugid]) ? $plugins[$plugid] : false) : $plugins;
        }
        
        private function loadDevPlugin($plugid)
        {
            return json_decode(file_get_contents(ROOT . 'pk-plugins/'. $plugid .'/package.poki'), true);
        }

        public function listPlugins()
        {
            $this->cfg->configSurvey(false);
            $admin = $this->usr->loginSurvey(false, 'login');
            
            $this->render('app/plugin.list', [
                "admin"             => $admin,
                "pagetitle"         => 'Plugins',
                "categories"        => $this->loadController('categories')->list(),
                "pluglist"          => $this->loadController('listener')->loadPlugins()
            ]);
        }

        public function toggle()
        {
            $plugid  = str_replace('pkpg-', '', Posts::get(0));
            $plugins = $this->loadPlugins();

            if ($plugins[$plugid])
            {
                $plugins[$plugid]['active'] = $plugins[$plugid]['active'] == 1 ? 0 : 1;

                if (file_put_contents(ROOT . 'appfiles/listener/plugins.poki', json_encode($plugins, JSON_PRETTY_PRINT)))
                {
                    $this->json_success("Plugin " . ($plugins[$plugid]['active']==1 ? "enabled" : "disabled"));
                    exit();
                }
                else
                {
                    $this->tell_error("An error occured. Please try again.");
                    exit();
                }
            }
            else {
                $this->tell_error("Plugin with ID: ". $plugid ." not found !");
                exit();
            }
        }

        public function delete()
        {
            $plugid  = str_replace('pkpg-', '', Posts::get(0));
            $plugins = $this->loadPlugins();

            if ($plugins[$plugid])
            {
                unset($plugins[$plugid]);

                if (file_put_contents(ROOT . 'appfiles/listener/plugins.poki', json_encode($plugins, JSON_PRETTY_PRINT)))
                {
                    $this->delDir(ROOT . 'pk-plugins/' . $plugid);

                    $this->json_success("Plugin deleted");
                    exit();
                }
                else
                {
                    $this->tell_error("An error occured. Please try again.");
                    exit();
                }
            }
            else
            {
                $this->tell_error("Plugin with ID: ". $plugid ." not found !");
                exit();
            }
        }

        public function add()
        {
            $file = Posts::file('plugin');

            if ( ! $file['error'])
            {
                $zip  = new \ZipArchive();
                $zgot = $zip->open($file['tmp_name']);
                $temp = md5($file['tmp_name']);
                $tdir = ROOT . 'appfiles/temp/' . $temp;
                $pdir = ROOT . 'pk-plugins/' . $temp;

                if ( ! file_exists(ROOT . 'appfiles/temp')) @mkdir(ROOT . 'appfiles/temp');
                if ( ! file_exists(ROOT . 'pk-plugins')) @mkdir(ROOT . 'pk-plugins');
                if ( ! file_exists(ROOT . 'appfiles/listener/plugins.poki')) @file_put_contents(ROOT . 'appfiles/listener/plugins.poki', '[]');
                
                if (file_exists(ROOT . 'pk-plugins') && file_exists(ROOT . 'appfiles/temp') && file_exists(ROOT . 'appfiles/temp') && file_exists(ROOT . 'appfiles/listener/plugins.poki'))
                {
                    if ($zgot)
                    {
                        if ($zip->extractTo( $tdir ))
                        {
                            if ($package = file_get_contents($tdir . '/package.poki'))
                            {
                                $this->checkPluginArchive($temp, $package, $tdir);

                                if (rename($tdir, $pdir))
                                {
                                    $this->json_success("Plugin correctly installed !");
                                }
                                else
                                {
                                    $this->tell_error("An error occured while installation. Please try again.");
                                }
                            }
                            else
                            {
                                $this->tell_error("Bad plugin zip file.");
                            }

                            $this->delDir($tdir);
                        }
                        else
                        {
                            $this->tell_error("An error occured while installation. Please try again.");
                        }
                        
                        $zip->close();
                    }
                }
                else {
                    $this->tell_error("Permission error. Can't add plugins.");
                }
            }
            else
            {
                $this->tell_error("The zip file contains errors.");
            }
        }

        public function update()
        {
            $plugid  = str_replace('pkpg-', '', Posts::get(0));
            $plugins = $this->loadPlugins();
            $package = $this->loadDevPlugin( $plugid );

            $this->checkPluginArchive( $plugid, $package, ROOT . 'pk-plugins/' . $plugid, false );

            if ( $app = $plugins[ $plugid ] )
            {
                $package['active']  = $app['active'];
                $plugins[ $plugid ] = $package;
                
                if ( file_put_contents( ROOT . 'appfiles/listener/plugins.poki', json_encode( $plugins, JSON_PRETTY_PRINT ) ) )
                    $this->json_success("Plugin correctly updated !");
                else
                    $this->tell_error("Update failed !");
            }
            else {
                $this->tell_error("No normal !");
            }
        }

        public function generate()
        {
            $name        = Posts::post('pg_name');
            $label       = Posts::post('pg_lb_name');
            $author      = Posts::post('pg_author');
            $licence     = Posts::post('pg_licence');
            $description = Posts::post('pg_description');

            if ( ! strlen($name) || ! strlen($label) || ! strlen($author) || ! strlen($licence) || ! strlen($description) )
            {
                $this->tell_error("You have to fill correctly all fields !");
            }
            else {
                $package = [
                    "name"          => $name,
                    "label_name"    => $label,
                    "version"       => "1.0",
                    "licence"       => $licence,
                    "icon"          => "icon.png",
                    "description"   => $description,
                    "listener"      => [ "name" => "pk-mylistener", "handle" => [ "create", "update", "delete" ] ],
                    "menulinks"     => [
                        "hello"     => [ "link" => "Hello", "action" => "/hello", "view" => "hello" ],
                        "about"     => [ "link" => "About", "action" => "/about", "view" => "about" ]
                    ],
                    "styles"        => [ 'main.css' ],
                    "scripts"       => [ 'main.js' ],
                    "handlers"      => [ "actions" ],
                    "door"          => "start",
                    "apidoor"       => "apistart",
                    "active"        => 0
                ];

                // creating tmp folder if doesn't exists
                if ( ! file_exists(ROOT . 'appfiles/temp') ) @mkdir(ROOT . 'appfiles/temp');

                // creating new plugin folder
                if ( ! file_exists(ROOT . 'appfiles/temp/' . $name) )
                {
                    @mkdir(ROOT . 'appfiles/temp/' . $name, 0777);
                }
                else {
                    @$this->delDir(ROOT . 'appfiles/temp/' . $name);
                    @mkdir(ROOT . 'appfiles/temp/' . $name, 0777);
                }

                // creating under folders
                @mkdir(ROOT . 'appfiles/temp/' . $name . '/listeners', 0777);
                @mkdir(ROOT . 'appfiles/temp/' . $name . '/views', 0777);
                @mkdir(ROOT . 'appfiles/temp/' . $name . '/views/includes', 0777);
                @mkdir(ROOT . 'appfiles/temp/' . $name . '/assets', 0777);
                @mkdir(ROOT . 'appfiles/temp/' . $name . '/assets/images', 0777);
                @mkdir(ROOT . 'appfiles/temp/' . $name . '/assets/styles', 0777);
                @mkdir(ROOT . 'appfiles/temp/' . $name . '/assets/scripts', 0777);

                // creating files
                @file_put_contents( ROOT . 'appfiles/temp/' . $name . '/assets/styles/main.css', '/* here goes styles */' );
                @file_put_contents( ROOT . 'appfiles/temp/' . $name . '/assets/scripts/main.js', '/* here goes scripts */' );
                @file_put_contents( ROOT . 'appfiles/temp/' . $name . '/listeners/pk-mylistener.php', Generator::listenerFile() );
                @file_put_contents( ROOT . 'appfiles/temp/' . $name . '/listeners/pk-mylistener.php', Generator::listenerFile() );
                @file_put_contents( ROOT . 'appfiles/temp/' . $name . '/listeners/pk-mylistener.php', Generator::listenerFile() );
                @file_put_contents( ROOT . 'appfiles/temp/' . $name . '/views/hello.view.php', Generator::helloView() );
                @file_put_contents( ROOT . 'appfiles/temp/' . $name . '/views/about.view.php', Generator::aboutView() );
                @file_put_contents( ROOT . 'appfiles/temp/' . $name . '/start.php', Generator::startFile() );
                @file_put_contents( ROOT . 'appfiles/temp/' . $name . '/apistart.php', Generator::apiStartFile() );
                @file_put_contents( ROOT . 'appfiles/temp/' . $name . '/package.poki', json_encode($package, JSON_PRETTY_PRINT) );

                $zipper = new FlxZipArchive();
                $bname  = ROOT . 'appfiles/temp/' . $name;
                $res = $zipper->open( $bname . '.zip', \ZipArchive::CREATE );

                if ( $res === TRUE )
                {
                    $dir = @opendir( $bname . '/' );

                    while ($file = readdir( $dir ))
                    {
                        if ($file == '..' || $file == '.') continue;
                        $zipper->{ filetype( $bname .'/'. $file ) == 'dir' ? 'addDir' : 'addFile' }( $bname .'/'.$file, $file );
                    }

                    closedir($dir);
                    $zipper->close();

                    exit( $this->json_success("Done !", [ "link" => WROOT . 'appfiles/temp/'. $name .'.zip', "newtoken" => Posts::getCSRFTokenValue() ]) );
                }
                else {
                    $this->delDir( $bname );
                    $this->tell_error( "Cannot create the plugin !" );
                }
            }
        }

        private function delDir($dir)
        { 
            $files = array_diff(scandir($dir), array('.','..')); 
            
            foreach ($files as $file)
            { 
                (is_dir("$dir/$file")) ? $this->delDir("$dir/$file") : unlink("$dir/$file"); 
            }

            return rmdir($dir); 
        } 

        private function checkPluginArchive($id, $json_package, $tdir, $firstinstall=true)
        {
            // parsing package file
            $package = ! is_array($json_package) ? json_decode($json_package, true) : $json_package;

            // checking fields
            if (
                !$this->notEmpty($package['name']) || !$this->notEmpty($package['label_name']) || !$this->notEmpty($package['version']) || 
                !$this->notEmpty($package['licence']) || !$this->notEmpty($package['description']) || !$this->notEmpty($package['menulinks']) || 
                !$this->notEmpty($package['door'])
            ) {
                $this->tell_error("Plugin package file not complete !");
                if ($firstinstall) $this->delDir($tdir);
                exit();
            }

            // checking door
            if ( ! file_exists($tdir . '/' . $package['door'] . '.php') && ! file_exists($tdir . '/' . $package['door']))
            {
                $this->tell_error("Plugin door not found !");
                if ($firstinstall) $this->delDir($tdir);
                exit();
            }

            // getting plugins list for saving
            if ( $firstinstall )
            {
                if ($plugins = file_get_contents(ROOT . 'appfiles/listener/plugins.poki'))
                {
                    // parsing plugins list
                    $plugins = json_decode($plugins, true);

                    // deactiving the package to let the user active
                    $package['active'] = 0;

                    // put package in plugins list
                    $plugins[$id] = $package;

                    // save updated list
                    if ( ! file_put_contents(ROOT . 'appfiles/listener/plugins.poki', json_encode($plugins, JSON_PRETTY_PRINT)))
                    {
                        $this->tell_error("An error occured. Please try again.");
                        if ($firstinstall) $this->delDir($tdir);
                        exit();
                    }
                }
                else
                {
                    $this->tell_error("An error occured. Please try again.");
                    if ($firstinstall) $this->delDir($tdir);
                    exit();
                }
            }
        }

        private function tell_error($message)
        {
            $this->json_error($message, [ "newtoken" => Posts::getCSRFTokenValue() ]);
        }

        private function notEmpty($val)
        {
            return (isset($val) && !empty($val)) || (isset($val) && strlen(trim($val)));
        }
    }