<?php

	// if (!class_exists('Poki')) include ROOT . 'poki.accessor.php';

	/**
	* 
	*/
	class Mylistener extends Listener
	{
		
		public static function onCreate($params)
		{
			self::log("Un element a été ajouté dans la categorie " . $params['categoryname']);
		}
		
		public static function onUpdate($params)
		{
			self::log("L'élement avec pour ID ". $params['contentid'] ." a été mis à jour dans la categorie " . $params['categoryname']);
		}
		
		public static function onDelete($params)
		{
			self::log("L'élement avec pour ID ". $params['contentid'] ." a été supprimé dans la categorie " . $params['categoryname']);
		}
		
		public static function onRead($params)
		{

		}
		
		public static function onLogin($params)
		{
			self::log("L'utilisateur " . $params['email'] . " s'est connecté le " . date('d-m-Y à H:i'));
		}
		
		public static function onLogout($params)
		{
			self::log("L'utilisateur " . $params['email'] . " s'est déconnecté le " . date('d-m-Y à H:i'));
		}

		public static function log($message)
		{
			file_put_contents(ROOT . 'traces/success.logs', file_get_contents(ROOT . 'traces/success.logs') .'[ '. date('d-m-Y H:i:s') .' ] '. $message . "\n");
		}
	}