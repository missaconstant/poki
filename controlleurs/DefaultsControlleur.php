<?php

    namespace Poki;
	
	class DefaultsControlleur extends controlleur
	{
		private $cfg;
		private $usr;

		public function __construct()
		{
			Posts::disableCSRF();
			$this->cfg = $this->loadController('config');
			$this->usr = $this->loadController('users');
		}
		
		public function blank()
		{
			$this->cfg->configSurvey(false);
			// $this->render('app/blank');
			$mdl = $this->loadModele('contents');
			$ret = $mdl->something('articles');
			var_dump($ret); exit();
			// $mdl->getQueryStringFromCategoryParams();
		}

		public function install()
		{
			$this->cfg->configSurvey('/', 'install');
			$this->render('starter/config', []);
		}

		public function configure()
		{
			if (Posts::post(['dbname'])) {
				$dbuser = Posts::post('dbuser');
				$dbname = Posts::post('dbname');
				$dbpass = Posts::post('dbpass');
				$dbhost = Posts::post('dbhost');
				$appfolder = Config::$appfolder;

				if ($this->cfg->connectDatabase($dbhost, $dbname, $dbuser, $dbpass)) {
					if ($this->cfg->setConfigs($dbhost, $dbname, $dbuser, $dbpass, $appfolder)) {
						$this->redirTo(Routes::find('makeinstall'));
					}
					else {
						Session::set('errortype', 'permissionerror');
						$this->redirTo(Routes::find('errorpage'));
						exit();
					}
				}
				else {
					Session::set('errortype', 'dbconnecterror');
					$this->redirTo(Routes::find('errorpage'));
					exit();
				}
			}
			else if (Posts::post(['username'])) {
				$username = strlen(trim(Posts::post('username'))) ? trim(Posts::post('username')) : 'poki';
				$password = strlen(Posts::post('password')) ? Posts::post('password') : 'poki';

				if (strlen($username)>2) {
					if ($this->cfg->setDefaultsTables($username, $password)) {
						$this->redirTo(Routes::find('configdone'));
						exit();
					}
					else {
						Session::set('errortype', 'tableseterror');
						$this->redirTo(Routes::find('errorpage'));
						exit();
					}
				}
				else {
					Session::set('errortype', 'usernamefailed');
					$this->redirTo(Routes::find('errorpage'));
					exit();
				}
			}

			$this->cfg->configSurvey('/');
		}

		public function makeinstall()
		{
			$this->cfg->configSurvey('/', 'make-install');
			$this->render('starter/make-install');
		}

		public function configdone()
		{
			$this->render('starter/config-done');
		}

		public function errorpage()
		{
			$errortype = Session::get('errortype');
			Session::unset("errortype");
			$this->render('starter/error-page', [
				"errormsg" => $this->cfg->statics['errors'][$errortype]
			]);
		}

		public function login()
		{
			$this->cfg->configSurvey(false);
			$this->usr->loginSurvey('home');
			$this->render('app/login');	
		}

		public function index()
		{
			$this->redirTo(Routes::find('home'));
		}

		public function home()
		{
			$this->cfg->configSurvey(false);
			$admin = $this->usr->loginSurvey(false, 'login');
			$this->render('app/home', [
				"admin" => $admin,
				"pagetitle" => "",
				"categories" => $this->loadController('categories')->list(),
				"pluglist" => $this->loadController('listener')->loadPlugins()
			]);
		}

		public function performances()
		{
			echo $this->getWastedTime();
		}
	}