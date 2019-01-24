<?php

	ini_set('error_reporting', E_ALL);

	session_start();

	define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
	define('WROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
	define('INCLUDES', ROOT.'/vues/includes/');
	define('THEME', WROOT.'THEME/');
	// define('HTTP_REFERER', str_replace('index.php', '', $_SERVER['HTTP_REFERER']));

	/* database variable defining */
	/***********************/

	include_once ROOT.'core/config.php';
	include_once ROOT.'core/helpers.php';
	include_once ROOT.'core/road.php';
	include_once ROOT.'core/modele.php' ;
	include_once ROOT.'core/controlleur.php' ;
	include_once ROOT.'core/files.php' ;
	include_once ROOT.'core/utils.php' ;
	include_once ROOT.'core/routes.php' ;
	include_once ROOT.'core/sessions.php' ;
	include_once ROOT.'core/posts.php' ;
	include_once ROOT.'core/listener.php';

    $_SERVER['REQUEST_URI'] = urldecode($_SERVER['REQUEST_URI']);
    
    /* obtention du dossier de base de l'application */
    if (Config::$appfolder == 'noset') {
		$dirname = basename(__DIR__);
    	if ($pos = strpos($_SERVER['REQUEST_URI'], $dirname)) {
    		$new = substr($_SERVER['REQUEST_URI'], 0, $pos + strlen($dirname));
			Config::$appfolder = $new;
		}
		else {
			Config::$appfolder = '';
		}
	}

    $s = $_SERVER['REQUEST_URI'];
	$s = str_replace(Config::$appfolder, '', $s);
	$_SERVER['REQUEST_URI'] = strlen($s) ? $s : '/';

    /* recupperation de la partie concernant la requête */
	$q = explode('/', $_SERVER['REQUEST_URI']);

	define('BASE_URI', '/' . $q[1]);

	/* recuperation des differentes parties: controlleur, action paramètres */
	/* si on passe par apache(localhost/projet), on coupe à partir du 3e élement de $q sinon, on coupe à partir du 2e */
	$parts = array_slice($q, strtolower($_SERVER['HTTP_HOST'])=='localhost' ? 2 : 1);

	/* variable global get */
	$g = array_slice($q, strtolower($_SERVER['HTTP_HOST'])=='localhost' ? 4 : 3);
	$_GET = [];
	foreach ($g as $k => $v) {
		$_GET[$k] = $v;
		$_GET["value".($k+1)] = $v;
	}
	/* controller and action */
	$ctrl = isset($parts[0]) && strlen($parts[0]) ? ucfirst($parts[0]) : 'Defaults';
	$action = isset($parts[1]) ? $parts[1] : 'index';
	/* roading */
	$road->doAction($ctrl, $action);