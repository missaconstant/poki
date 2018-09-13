<?php

    class ApiModele extends modele
    {
        public function creerApi($categoryname)
        {
            try {
                $q = modele::$bd->prepare('INSERT INTO adm_api_access(category, allowed, apikey) VAlUES(:category, :allow, :apikey)');
                $r = $q->execute([
                    "category" => $categoryname,
                    "allowed" => "none",
                    "apikey" => md5(uniqid().date('dmYHis'))
                ]);
                $q->closeCursor();
                return true;
            }
            catch (Exception $e) {
                return false;
            }
        }

        public function modifierApi($categoryname, $allowed)
        {
            try {
                $q = modele::$bd->prepare("UPDATE adm_api_access SET allowed=:allowed WHERE category=:category");
                $r = $q->execute([
                    "allowed" => $allowed,
                    "category" => $categoryname
                ]);
                $q->closeCursor();
                return true;
            }
            catch (Exception $e) {
                return false;
            }
        }

        public function changerApi($categoryname, $delete)
        {
            try {
                $newapikey = $delete ? 'noset' : md5(uniqid().date('dmYHis'));
                $q = modele::$bd->prepare("UPDATE adm_api_access SET apikey=:apikey WHERE category=:category");
                $r = $q->execute([
                    "apikey" => $newapikey,
                    "category" => $categoryname
                ]);
                $q->closeCursor();
                return $newapikey;
            }
            catch (Exception $e) {
                return false;
            }
        }

        public function toggleApi($categoryname, $value) {
            try {
                $q = modele::$bd->prepare("UPDATE adm_api_access SET active=:active WHERE category=:category");
                $r = $q->execute([
                    "active" => $value,
                    "category" => $categoryname
                ]);
                $q->closeCursor();
                return true;
            }
            catch (Exception $e) {
                return false;
            }
        }

        public function trouverApi($categoryname)
        {
            try {
                $q = modele::$bd->query("SELECT * FROM adm_api_access WHERE category='$categoryname'");
                $r = $q->fetchAll();
                $q->closeCursor();
                return count($r) ? (object) $r[0] : false;
            }
            catch (Exception $e) {
                return false;
            }
        }
    }
    