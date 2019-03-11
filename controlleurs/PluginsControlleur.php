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
                else {
                    $this->json_error("An error occured. Please try again.");
                    exit();
                }
            }
            else {
                $this->json_error("Plugin with ID: ". $plugid ." not found !");
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
                    @unlink(ROOT . 'pk-plugins/' . $plugid);

                    $this->json_success("Plugin deleted");
                    exit();
                }
                else {
                    $this->json_error("An error occured. Please try again.");
                    exit();
                }
            }
            else {
                $this->json_error("Plugin with ID: ". $plugid ." not found !");
                exit();
            }
        }
    }