<?php

    namespace Poki;

    class CategoriesModele extends modele
    {

        public function trouverTousCategories($getone='')
        {
            $dbname = Config::$db_name;
            $q = modele::$bd->query("SELECT table_name as field FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name REGEXP '^adm_app_". ( strlen($getone) ? $getone : '' ) ."'");
            $r = $q->fetchAll(\PDO::FETCH_ASSOC);

            // associating label for each category
            foreach ( $r as $k => $category )
            {
                // get category params
                $params = json_decode(file_get_contents(Config::$jsonp_files_path .$category['field']. ".params"), true);
                
                // affecting label
                $r[ $k ]['label'] = $params['label'] ?? '';
            }

            $q->closeCursor();
            return $getone ? (count($r) ? $r[0] : false) : $r;
        }

        public function trouverCategory($name)
        {
            $dbname = Config::$db_name;
            $c_name = 'adm_app_' . $name;
            $q = modele::$bd->query("SELECT column_name as name, data_type as type, column_type as ctype FROM INFORMATION_SCHEMA.COLUMNS where table_schema = '$dbname' AND TABLE_NAME='$c_name' AND column_name!='id' AND column_name!='active' AND column_name!='added_at' AND column_name!='combined_fields'");
            $r = $q->fetchAll();
            
            $q->closeCursor();
            return count($r) ? $r : false;
        }

        public function trouverTousCategoryFields()
        {
            $dbname = Config::$db_name;
            $q = modele::$bd->query("SELECT table_name as tab_name, column_name as name, data_type as type, column_type as ctype FROM INFORMATION_SCHEMA.COLUMNS where table_schema='$dbname' AND TABLE_NAME REGEXP '^adm_app' AND column_name!='active' AND column_name!='added_at' AND column_name!='combined_fields'");
            $r = $q->fetchAll(\PDO::FETCH_ASSOC);

            $q->closeCursor();
            return $r;
        }

        public function creerCategory($category) {
            try {
                $name = 'adm_app_' . $category->name;
                $q = modele::$bd->exec("CREATE TABLE $name (
                    id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    active int(1) NOT NULL DEFAULT '1',
                    added_at varchar(100),
                    combined_fields json
                )");

                $name = $category->name;
                $sql1 = "INSERT INTO adm_api_access(category, allowed, apikey, active) VALUES('$name', '', 'noset', '0')";
                $q1 = modele::$bd->exec($sql1);

                return true;
            }
            catch (\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }

        public function modifierCategory($category)
        {
            try {
                $name = 'adm_app_' . $category->name;
                $oldname = 'adm_app_' . $category->oldname;
                $q = modele::$bd->exec("ALTER TABLE $oldname RENAME TO $name");
                $q1 = modele::$bd->exec("UPDATE adm_api_access SET category='" . $category->name ."' WHERE category='". $category->oldname ."'");
                return true;
            }
            catch (\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }

        public function supprimerCategory($name) {
            try {
                $name = 'adm_app_' . $name;
                $q = modele::$bd->exec("DROP TABLE $name");
                return true;
            }
            catch (\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }

        public function existsCategory($name) {
            $dbname = Config::$db_name;
            $name = 'adm_app_' . $name;
            $q = modele::$bd->query("SELECT table_name as field FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name='$name'");
            $r = $q->fetchAll();
            $q->closeCursor();
            return count($r);
        }

        public function creerCategoryField($fields)
        {
            try {
                foreach ($fields as $k => $field) {
                    $category = 'adm_app_' . $field->category;
                    $fieldname = $field->name;
                    $fieldtype = $field->type;
                    $fieldlength = $field->type != 'text' && $field->type != 'date' && $field->type != 'tinytext' ? '(255)' : '';
                    $q = modele::$bd->exec("ALTER TABLE $category ADD $fieldname $fieldtype $fieldlength");
                }
                return true;
            }
            catch (\Exception $e) {
                //return false;
                exit(json_encode(["error" => true, "message" => $e->getMessage()]));
            }
        }

        public function modifierCategoryField($field)
        {
            try {
                $category = 'adm_app_' . $field->category;
                $fieldname = $field->name;
                $fieldtype = $field->type;
                $oldname = $field->oldname;
                $fieldlength = $field->type != 'text' && $field->type != 'date' && $field->type != 'tinytext' ? '(255)' : '';
                $q = modele::$bd->query("ALTER TABLE $category CHANGE $oldname $fieldname $fieldtype $fieldlength");
                return true;
            }
            catch (\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }

        public function supprimerCategoryField($fieldname, $category)
        {
            try {
                $q = modele::$bd->exec("ALTER TABLE adm_app_$category DROP $fieldname");
                return $q;
            }
            catch(\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }

        public function existsFieldCategory($name, $field) {
            $dbname = Config::$db_name;
            $c_name = 'adm_app_' . $name;
            $q = modele::$bd->query("SELECT column_name as name, data_type as type, column_type as ctype FROM INFORMATION_SCHEMA.COLUMNS where table_schema = '$dbname' AND TABLE_NAME='$c_name' AND column_name='$field'");
            $r = $q->fetchAll();
            $q->closeCursor();
            return count($r);
        }
    }
