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
    }
    