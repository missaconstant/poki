<?php

    namespace Poki;

	/**
	* 
	*/
	class Session
	{
		
		function __construct()
		{
			# code...
		}

		static function set($name, $value)
		{
			$_SESSION[$name] = $value;
		}

		static function get($name)
		{
			return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
		}

		static function exists($name)
		{
			return self::get($name) ? true : false;
		}

		static function unset($name=false)
		{
			if ($name) {
				unset($_SESSION[$name]);
			}
			else {
				self::end();
			}
		}

		static function end()
		{
			session_destroy();
		}
	}