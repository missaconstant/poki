<?php

	/**
	* 
	*/
	class ListenerControlleur extends controlleur
	{
		
		function __construct()
		{
		
		}

		public function on($event, $datas)
		{
			$funcs = json_decode(file_get_contents(ROOT . 'appfiles/listener/listeners.poki'), true);
			foreach ($funcs as $plugin => $params) {
				if (in_array($event, $params['handle'])) {
					require_once ROOT .'pk-plugins/'. $plugin .'/listeners/'. $params['listener'] .'.php';

					$class = ucfirst(str_replace('pk-', '', $params['listener']));
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

		public static function log($message)
		{
			file_put_contents('ROOTerror', file_get_contents('ROOTerror') . $message . "\n");
		}
	}