<?php

    class UsersControlleur extends controlleur
    {
        private $cfg;

        public function __construct()
        {
            if (file_exists(ROOT . 'statics/config.php') && !isset($dbuser) && !isset($dbpass)) {
                include ROOT . 'statics/config.php';
                Config::$db_user = $dbuser;
                Config::$db_host = $dbhost;
                Config::$db_password = $dbpass;
                Config::$db_name = $dbname;
            }
            $this->cfg = $this->loadController('config');
        }

        public function login()
        {
            $username = Posts::post('user');
            $password = md5(Posts::post('pass'));
            if ($user = $this->loadModele()->tryLogon($username, $password)) {
                Session::set('admin', md5($user['id']));
                $this->json_success('Login success !');
                exit();
            }
            else {
                $this->json_error('Wrong credentials provided !', ['newtoken' => Posts::getCSRFTokenValue()]);
                exit();
            }
        }

        public function logout()
        {
            Session::end();
            $this->redirTo(Routes::find('/'));
        }

        public function list()
        {
            $this->cfg->configSurvey(false);
            $admin = $this->loginSurvey(false, 'login');
            $this->render('app/users', [
                "admin" => $admin,
				"pagetitle" => "Users",
                "categories" => $this->loadController('categories')->list(),
                "users" => $this->loadModele()->findAll()
            ]);
        }

        public function create()
        {
            $this->cfg->configSurvey(false);
            $admin = $this->loginSurvey(false, 'login');
            $this->render('app/user-form', [
                "admin" => $admin,
				"pagetitle" => "Create new user",
                "categories" => $this->loadController('categories')->list(),
                "roles" => $this->loadModele()->trouverTousRoles()
            ]);
        }

        public function update()
        {
            $this->cfg->configSurvey(false);
            $admin = $this->loginSurvey(false, 'login');

            if ($user = $this->loadModele()->findAdmin(Posts::get(0))) {
                $this->render('app/user-form', [
                    "admin" => $admin,
                    "pagetitle" => "Create new user",
                    "categories" => $this->loadController('categories')->list(),
                    "roles" => $this->loadModele()->trouverTousRoles(),
                    "user" => $user
                ]);
            }
            else {
                $this->redirTo(Routes::find('home'));
            }
        }

        public function account()
        {
            $this->cfg->configSurvey(false);
            $admin = $this->loginSurvey(false, 'login');
            $this->render('app/account', [
                "admin" => $admin,
				"pagetitle" => "Account settings",
                "categories" => $this->loadController('categories')->list()
            ]);
        }

        public function add()
        {
            $user = (object) [
                "name" => Posts::post('name'),
                "email" => Posts::post('email'),
                "role" => Posts::post(['role']) ? (Posts::post('role')!='0' ? Posts::post('role') : $this->loginSurvey(false, 'login')->roleid) : $this->loginSurvey(false, 'login')->roleid,
                "password" => Posts::post('pass'),
                "id" => Posts::post('editing')
            ];

            $this->checkUser($user);

            if ($this->loadModele()->{$user->id=='0' ? 'creerUser':'modifierUser'}($user)) {
                $this->json_success("done !");
            }
            else {
                $this->json_error("An error occured ! Check your connexion and try again.");
                exit();
            }
        }

        public function toggleActive()
        {
            if ($this->loadModele()->changeUserState(Posts::get(0), Posts::get(1))) {
                $this->json_success("Done !");
            }
            else {
                $this->json_error("An error occured ! Check your connexion and try again.");
                exit();
            }
        }

        public function delete()
        {
            if ($this->loadModele()->supprimerUsers(Posts::get(0))) {
                $this->json_success("Done !");
            }
            else {
                $this->json_error("An error occured ! Check your connexion and try again.");
                exit();
            }
        }

        public function loginSurvey($iflogged=false, $ifnot=false)
        {
            if ($user = $this->loadModele()->findAdmin(Session::get('admin'))) {
                if ($iflogged) {
                    $this->redirTo(Routes::find($iflogged));
                    exit();
                }
                else {
                    return $user;
                }
            }
            else {
                if ($ifnot) {
                    $this->redirTo(Routes::find($ifnot));
                    exit();
                }
            }
        }

        public function checkUser($user)
        {
            $error = false;
            if (!strlen($user->name)) {
                $error = "You have to give your name !";
            }
            else if (!preg_match("#^[a-zA-Z0-9._]+@[a-zA-Z0-9\-_.]+[.]{1}[a-zA-Z0-9]{2,}$#", $user->email) && $user->role!='1') {
                $error = "You must give a correct email address !";
            }
            else if ($user->role == '0' || trim($user->role)=='') {
                $error = "Choose a role for this user !";
            }
            else if (strlen($user->password) && strlen($user->password)<6) {
                $error = "Choose a stronger password !";
            }

            if ($error) {
                $this->json_error($error, ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
        }
    }