<?php

    namespace Poki;

    class UsersModele extends modele
    {
        public function creerUser($user)
        {
            try {
                $q = modele::$bd->prepare("INSERT INTO _adm_users(name, email, password, role) VALUES (:name, :email, :password, :role)");
                $r = $q->execute([
                    "name" => $user->name,
                    "email" => $user->email,
                    "password" => md5($user->password),
                    "role" => $user->role
                ]);
                $q->closeCursor();
                return modele::$bd->lastInsertId();
            }
            catch (\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }

        public function modifierUser($user)
        {
            try {
                $passwordstring = strlen($user->password) ? 'password=:password,' : '';
                $q = modele::$bd->prepare("UPDATE _adm_users SET name=:name, email=:email, $passwordstring role=:role WHERE md5(id)=:id");
                $left = [
                    "name" => $user->name,
                    "email" => $user->email,
                    "role" => $user->role,
                    "id" => $user->id
                ];
                $right = strlen($user->password) ? ["password" => md5($user->password)] : [];
                $r = $q->execute(array_merge($left, $right));
                $q->closeCursor();
                return true;
            }
            catch (\Exception $e) {
                // echo json_encode(["error" => true, "message" => $e->getMessage()]); exit();
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }

        public function findAdmin($adminid)
        {
            try {
                $q = modele::$bd->query("SELECT _adm_users.id, _adm_users.name, _adm_users.email, _adm_users.password, _adm_users.active, _adm_users.role as roleid, _adm_roles.role FROM _adm_users, _adm_roles WHERE md5(_adm_users.id)='$adminid' AND _adm_roles.id=_adm_users.role");
                $r = $q->fetchAll();
                $q->closeCursor();
                return count($r) ? (object) $r[0] : false;
            }
            catch (\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }

        public function tryLogon($user, $pass)
        {
            $user = self::tryLogin([$user, $pass, 1], ['email', 'password', 'active'], '_adm_users');
            return $user;
        }

        public function findAll()
        {
            try {
                $q = modele::$bd->query("SELECT _adm_users.id, _adm_users.name, _adm_users.email, _adm_users.password, _adm_users.active, _adm_users.role as roleid, _adm_roles.role FROM _adm_users, _adm_roles WHERE _adm_roles.id=_adm_users.role");
                $r = $q->fetchAll(\PDO::FETCH_OBJ);
                $q->closeCursor();
                return $r;
            }
            catch (\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }

        public function changeUserState($id, $newstate)
        {
            try {
                $q = modele::$bd->exec("UPDATE _adm_users SET active='$newstate' WHERE md5(id)='$id'");
                return true;
            }
            catch (\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }

        public function supprimerUsers($id)
        {
            try {
                $q = modele::$bd->exec("DELETE FROM _adm_users WHERE md5(id)='$id'");
                return true;
            }
            catch (\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }

        public function trouverTousRoles()
        {
            try {
                $q = modele::$bd->query('SELECT * FROM _adm_roles');
                $r = $q->fetchAll(\PDO::FETCH_OBJ);
                $q->closeCursor();
                return $r;
            }
            catch (\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }
    }
