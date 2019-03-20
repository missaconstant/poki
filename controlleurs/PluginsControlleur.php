<?php

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

                if (file_put_contents(ROOT . 'appfiles/listener/plugins.poki', json_encode($plugins)))
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

                if (file_put_contents(ROOT . 'appfiles/listener/plugins.poki', json_encode($plugins)))
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
                $zip  = new ZipArchive();
                $zgot = $zip->open($file['tmp_name']);
                $temp = md5($file['tmp_name']);
                $tdir = ROOT . 'appfiles/temp/' . $temp;
                $pdir = ROOT . 'pk-plugins/' . $temp;

                if ( ! file_exists(ROOT . 'appfiles/temp')) @mkdir(ROOT . 'appfiles/temp');
                if ( ! file_exists(ROOT . 'pk-plugins')) @mkdir(ROOT . 'pk-plugins');
                
                if (file_exists(ROOT . 'pk-plugins') && file_exists(ROOT . 'pk-plugins'))
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
                                    $this->tell("An error occured while installation. Please try again.");
                                }
                            }
                            else
                            {
                                $this->tell("Bad plugin zip file.");
                            }

                            $this->delDir($tdir);
                        }
                        else
                        {
                            $this->tell("An error occured while installation. Please try again.");
                        }
                        
                        $zip->close();
                    }
                }
                else {
                    $this->tell("Permission error. Can't add plugins.");
                }
            }
            else
            {
                $this->tell("The zip file contains errors.");
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

        private function checkPluginArchive($id, $json_package, $tdir)
        {
            // parsing package file
            $package = json_decode($json_package, true);

            // checking fields
            if (
                !$this->notEmpty($package['name']) || !$this->notEmpty($package['label_name']) || !$this->notEmpty($package['version']) || 
                !$this->notEmpty($package['licence']) || !$this->notEmpty($package['description']) || !$this->notEmpty($package['menulinks']) || 
                !$this->notEmpty($package['door'])
            ) {
                $this->tell_error("Plugin package file not complete !");
                $this->delDir($tdir);
                exit();
            }

            // checking door
            if ( ! file_exists($tdir . '/' . $package['door'] . '.php') && ! file_exists($tdir . '/' . $package['door']))
            {
                $this->tell_error("Plugin door not found !");
                $this->delDir($tdir);
                exit();
            }

            // getting plugins list for saving
            if ($plugins = file_get_contents(ROOT . 'appfiles/listener/plugins.poki'))
            {
                // parsing plugins list
                $plugins = json_decode($plugins, true);

                // deactiving the package to let the user active
                $package['active'] = 0;

                // put package in plugins list
                $plugins[$id] = $package;

                // save updated list
                if ( ! file_put_contents(ROOT . 'appfiles/listener/plugins.poki', json_encode($plugins)))
                {
                    $this->tell_error("An error occured. Please try again.");
                    $this->delDir($tdir);
                    exit();
                }
            }
            else
            {
                $this->tell_error("An error occured. Please try again.");
                $this->delDir($tdir);
                exit();
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