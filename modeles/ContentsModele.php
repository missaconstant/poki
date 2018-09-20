<?php

    class ContentsModele extends modele
    {
        public function creerContent($content, $categoryname, $edition=false)
        {
            try {
                $leftstring = [];
                $rightstring = [];
                foreach ($content as $key => $value) {
                    $rightstring[] = ':' . $key;
                    $leftstring[] = $key;
                }
                $leftstring = implode(', ', $leftstring);
                $rightstring = implode(', ', $rightstring);

                $q = modele::$bd->prepare("INSERT INTO adm_app_$categoryname ($leftstring) VALUES ($rightstring)");
                $r = $q->execute($content);
                $q->closeCursor();
                return true;
            }
            catch (Exception $e) {
                return false;
            }
        }

        public function modifierContent($content, $categoryname, $contentid)
        {
            try {
                $leftstring = [];
                $rightstring = [];
                foreach ($content as $key => $value) {
                    $querystring[] = $key . '=:' . $key;
                }
                $querystring = implode(', ', $querystring);
                $content['id'] = $contentid;

                $q = modele::$bd->prepare("UPDATE adm_app_$categoryname SET $querystring WHERE id=:id");
                $r = $q->execute($content);
                $q->closeCursor();
                return true;
            }
            catch (Exception $e) {
                return false;
            }
        }

        public function trouverTousContents($categoryname)
        {
            try {
                $q = modele::$bd->query("SELECT * FROM adm_app_$categoryname");
                $r = $q->fetchAll(PDO::FETCH_ASSOC);
                $q->closeCursor();
                return $r;
            }
            catch (Exception $e) {
                return false;
            }
        }

        public function trouverContents($categoryname, $contentid)
        {
            try {
                $q = modele::$bd->query("SELECT * FROM adm_app_$categoryname WHERE id='$contentid'");
                $r = $q->fetchAll(PDO::FETCH_ASSOC);
                $q->closeCursor();
                return count($r) ? $r[0]:false;
            }
            catch (Exception $e) {
                return false;
            }
        }

        public function supprimerContents($categoryname, $contentid) {
            try {
                $q = modele::$bd->exec("DELETE FROM adm_app_$categoryname WHERE id='$contentid'");
                return true;
            }
            catch (Exception $e) {
                return false;
            }
        }

        public function getCategoryParams($categoryname)
        {
            return json_decode(file_get_contents(Config::$jsonp_files_path . "adm_app_$categoryname.params"), true);
        }
    }
    