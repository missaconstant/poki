<?php

    namespace Poki;

    class ConfigModele extends modele
    {
        public function createUserTable($username, $password)
        {
            $done = true;

            /* Creating table */
            try {
                $sql = "CREATE TABLE IF NOT EXISTS adm_users (
                    id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    name varchar(150) NOT NULL,
                    email varchar(200) NOT NULL,
                    password varchar(200) NOT NULL,
                    active int(1) NOT NULL DEFAULT '1',
                    role int(1) NOT NULL
                )";
                $q = modele::$bd->query($sql);
                $q->closeCursor();
            }
            catch (\Exception $e) {
                $done = false;
            }

            /* Creating datas */
            try {
                $sql = "INSERT INTO adm_users(name, email, password, active, role) VALUES('admin', '$username', md5('$password'), '1', '1')";
                $q = modele::$bd->query($sql);
                $q->closeCursor();
            }
            catch (\Exception $e) {
                $done = false;
            }

            return $done;
        }

        public function createDefaultTables()
        {
            /* Creating default category parmas json file */
            $paramcreated = $this->createCategoryParamFile('default');
            if (!$paramcreated) return false;

            /* Creating table */
            $done = true;

            try {
                /* table 1 */
                $sql = "CREATE TABLE IF NOT EXISTS adm_settings (
                    id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    keyname varchar(150) NOT NULL,
                    keyalias varchar(155) NOT NULL,
                    content varchar(255) NOT NULL,
                    active int(1) NOT NULL DEFAULT '1',
                    added_at varchar(100) NOT NULL DEFAULT '" . date('d-m-Y H:i') . "'
                )";
                $q = modele::$bd->query($sql);
                $q->closeCursor();


                /* table 1 */
                $sql3 = "CREATE TABLE IF NOT EXISTS adm_roles (
                    id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    role varchar(100) NOT NULL,
                    active int(1) NOT NULL DEFAULT '1'
                )";
                $q3 = modele::$bd->query($sql3);
                $q->closeCursor();

                /* table 2 */
                $sql1 = "CREATE TABLE IF NOT EXISTS adm_api_access (
                    id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    category varchar(255) NOT NULL,
                    allowed text,
                    apikey varchar(255),
                    active int(1) NOT NULL DEFAULT '1',
                    added_at varchar(100) NOT NULL DEFAULT '" . date('d-m-Y H:i') . "'
                )";
                $q1 = modele::$bd->query($sql1);
                $q1->closeCursor();
                
                /* table 2 */
                $sql2 = "CREATE TABLE IF NOT EXISTS adm_app_default (
                    id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    title varchar(150) NOT NULL,
                    content text NOT NULL,
                    active char(1) NOT NULL DEFAULT '1',
                    added_at varchar(100)
                )";
                $q2 = modele::$bd->query($sql2);
                $q2->closeCursor();
            }
            catch (\Exception $e) {
                $done = false;
            }

            /* Creating datas */
            try {
                $sql5 = "INSERT INTO adm_settings(keyname, keyalias, content) VALUES('language', 'default', 'english')";
                $q5 = modele::$bd->exec($sql5);

                $sql4 = "INSERT INTO adm_roles(role, active) VALUES ('admin', '1'), ('writer', '1'), ('viewer', '1')";
                $q4 = modele::$bd->exec($sql4);

                $sql6 = "INSERT INTO adm_settings(keyname, keyalias, content) VALUES('apipermissiontypes', 'apitypes', 'get,get-one,add,edit,delete,find')";
                $q6 = modele::$bd->exec($sql6);

                $sql7 = "INSERT INTO adm_api_access(category, allowed, apikey, active) VALUES('default', '', 'noset', 0)";
                $q7 = modele::$bd->exec($sql7);

                $sql8 = "INSERT INTO adm_app_default(title, content, active, added_at) VALUES('Welcome on Poki !', 'You are now on Poki. Then enjoy ! Create categories and admin your website easely.', '1', '" .date('d-m-Y H:i'). "')";
                $q8 = modele::$bd->exec($sql8);
            }
            catch (\Exception $e) {
                $done = false;
                die($e->getMessage());
            }

            return $done && $paramcreated;
        }

        public function existsDefaultTables()
        {
            $dbname = Config::$db_name;
            $sql = "SELECT * FROM information_schema.tables WHERE table_schema = '$dbname' AND (table_name = 'adm_default' OR table_name = 'adm_users' OR table_name = 'settings')";
            try {
                $q = modele::$bd->query($sql);
                $r = $q->fetchAll();
                $q->closeCursor();
                return count($r);
            }
            catch (\Exception $e) {
                return false;
            }
        }

        public function createCategoryParamFile($category)
        {
            $structure = [
                "name" => $category,
                "links" => [],
                "created_at" => date('d-m-Y H:i')
            ];
            return file_put_contents(Config::$jsonp_files_path . "adm_app_$category.params", json_encode($structure));
        }
    }
    