<?php

    namespace Poki;

	abstract class Listener {

		abstract public static function onCreate($params);

		// public static function onBeforeCreate($params);

		abstract public static function onUpdate($params);

		// public static function onBeforeUpdate($params);

		abstract public static function onDelete($params);

		// public static function onBeforeDelete($params);

		abstract public static function onRead($params);

		// public static function onBeforeRead($params);

		abstract public static function onLogin($params);

		// public static function onBeforeLogin($params);

		abstract public static function onLogout($params);

		// public static function onBeforeLogout($params);

	}