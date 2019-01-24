<?php

	Interface Listener {

		public static function onCreate($params);

		// public static function onBeforeCreate($params);

		public static function onUpdate($params);

		// public static function onBeforeUpdate($params);

		public static function onDelete($params);

		// public static function onBeforeDelete($params);

		public static function onRead($params);

		// public static function onBeforeRead($params);

		public static function onLogin($params);

		// public static function onBeforeLogin($params);

		public static function onLogout($params);

		// public static function onBeforeLogout($params);

	}