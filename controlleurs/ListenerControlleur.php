<?php

	/**
	* 
	*/
	class ListenerControlleur extends controlleur
	{
		private $cfg;
		private $usr;
		
		function __construct()
		{
			$this->cfg = $this->loadController('config');
			$this->usr = $this->loadController('users');
		}

		public function on($event, $datas)
		{
			$funcs = json_decode(file_get_contents(ROOT . 'appfiles/listener/plugins.poki'), true);
			foreach ($funcs as $plugin => $params) {
				if (in_array($event, $params['listener']['handle'])) {
					require_once ROOT .'pk-plugins/'. $plugin .'/listeners/'. $params['listener']['name'] .'.php';

					$class = ucfirst(str_replace('pk-', '', $params['listener']['name']));
					$evt   = 'on' . ucfirst($event);
					$class::$evt($datas);
				}
			}
		}

		public function addListener($type, $plugin, $name) {

		}

		public function removeListener($type, $plugin, $name) {

		}

		public function muteListener($type, $plugin, $name) {

		}

		public function loadPlugins($plugname=false)
		{
			$plugins = json_decode(file_get_contents(ROOT . 'appfiles/listener/plugins.poki'), true);
			return $plugname ? $plugins[$plugname] : $plugins;
		}

		public function app()
		{
			$this->cfg->configSurvey(false);
			$admin = $this->usr->loginSurvey(false, 'login');

			$plugname = Posts::get(0);
			$action   = Posts::get(1);
			$plugin   = $this->loadPlugins($plugname);
			$door     = ROOT . 'pk-plugins/' . $plugname . '/' . $plugin['door'] . '.php';
			$view     = ROOT . 'pk-plugins/' . $plugname . '/views/' . $plugin['menulinks'][$action]['view'] . '.view.php';

			if ($plugin['active'] == 0) $this->redirTo(Routes::find('home'));

			include $door;

			$class    = new Main();

			if (method_exists($class, $action)) $class->{ $action }();

			if (file_exists($view)) {
				$this->render('app/plugview', [
					"admin" => $admin,
					"pagetitle" => "Pk-Hello",
					"categories" => $this->loadController('categories')->list(),
					"pluglist" => $this->loadController('listener')->loadPlugins(),
					"view" => ROOT . 'pk-plugins/' . $plugname . '/views/' . $plugin['menulinks'][$action]['view'] . '.view.php'
				]);
			}
		}

		public static function log($message)
		{
			file_put_contents('ROOTerror', file_get_contents('ROOTerror') . $message . "\n");
		}
	}