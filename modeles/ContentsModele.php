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

        public function trouverTousContents($categoryname, $checkJoin=false, $filter=false)
        {
            try {
                # getting joining table
                $joining = $this->getCategoryParams($categoryname);
                $joining['name'] = $categoryname;

                # searching right query string
                $sql = '';
                if ($checkJoin && count($joining['links'])) {
                    $sql = $this->getQueryStringFromCategoryParams($joining, $categoryname, false, $filter);
                }
                else {
                    $filter = $this->getFilter($filter);
                    $limit = $filter['limit'];
                    $order = $filter['order'];
                    $sql = "SELECT * FROM adm_app_$categoryname $order $limit";
                }
                #doing query
                $q = modele::$bd->query($sql);
                $r = $q->fetchAll(PDO::FETCH_ASSOC);
                $q->closeCursor();
                
                return $r;
            }
            catch (Exception $e) {
                return false;
            }
        }

        public function trouverContents($categoryname, $contentid, $checkJoin=false)
        {
            try {
                # getting joining table
                $joining = $this->getCategoryParams($categoryname);
                $joining['name'] = $categoryname;
                
                # searching right query string
                $sql = '';
                if ($checkJoin && count($joining['links'])) {
                    $sql = $this->getQueryStringFromCategoryParams($joining, $categoryname, $contentid);
                }
                else {
                    $sql = "SELECT * FROM adm_app_$categoryname WHERE id='$contentid'";
                }
                #doing query
                $q = modele::$bd->query($sql);
                $r = $q->fetchAll(PDO::FETCH_ASSOC);
                $q->closeCursor();

                return count($r) ? $r[0]:false;
            }
            catch (Exception $e) {
                echo json_encode([$e->getMessage()]); exit();
            }
        }

        public function trouverContentsAccessor($categoryname, $checkJoin, $contentid)
        {
            return $this->trouverContents($categoryname, $contentid, $checkJoin);
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

        public function compterContents($categoryname)
        {
            try {
                $q = modele::$bd->query("SELECT count(*) as els_count FROM adm_app_$categoryname");
                $r = $q->fetchAll();
                $q->closeCursor();
                return $r[0]['els_count'];
            }
            catch (Exception $e) {
                return false;
            }
        }

        /* Forked Method */

        public function getCategoryParams($categoryname)
        {
            return json_decode(file_get_contents(Config::$jsonp_files_path . "adm_app_$categoryname.params"), true);
        }

        public function getCategoriesJoinedName($links)
        {
            $categories = [];
            foreach ($links as $key => $link) {
                $categories[] = $link['linkedto'];
            }
            return $categories;
        }

        public function trouverCategory($name, $norestrict=false)
        {
            try {
                $dbname = Config::$db_name;
                $c_name = 'adm_app_' . $name;
                $norestrictstring = $norestrict ? " AND column_name!='id' AND column_name!='active' AND column_name!='added_at'" : '';
                $q = modele::$bd->query("SELECT column_name as name FROM INFORMATION_SCHEMA.COLUMNS where table_schema = '$dbname' AND TABLE_NAME='$c_name' $norestrictstring");
                $r = $q->fetchAll(PDO::FETCH_COLUMN);
                $q->closeCursor();
                return count($r) ? $r : false;
            }
            catch (Exception $e) {
                // exit(json_encode([$e->getMessage()]));
            }
        }

        public function getQueryStringFromCategoryParams($joining, $categoryname, $contentid=false, $filter=false)
        {
            # list of table to take in select query
            $categoriesJoined = $this->getCategoriesJoinedName($joining['links']);
            array_unshift($categoriesJoined, $categoryname);

            # preparing SELECT query string
            $querySelectStrings = array_map(function ($category) use ($categoryname) {
                $tablefields = $this->trouverCategory($category, ($category!=$categoryname));
                if ($tablefields) { // cause can be false if category does not exists
                    $tablestring = [];
                    foreach ($tablefields as $k => $field) {
                        $tablestring[] = 'adm_app_'.$category .'.'. $field . ($category!=$categoryname ? ' as '. $category .'_'. $field : '');
                    }
                    return implode(', ', $tablestring);
                }
            }, $categoriesJoined);
            
            # preparing WHERE condition
            $wherestring = [];
            foreach ($joining['links'] as $fieldlinked => $link) {
                $linkedto = 'adm_app_'. $link['linkedto'];
                $linked = 'adm_app_'. $joining['name'];
                $wherestring[] = "LEFT JOIN $linkedto ON $linkedto.id=$linked.$fieldlinked";
            }

            # preparing filters instruction
            $filter = $this->getFilter($filter);
            $limit = $filter['limit'];
            $order = $filter['order'];

            return "SELECT ". implode(',', $querySelectStrings) ." FROM adm_app_$categoryname ". implode(' ', $wherestring) . ($contentid ? " WHERE adm_app_$categoryname.id=$contentid" : " $order $limit");
        }

        private function getFilter($filter)
        {
            $limit = ''; $order = '';
            if ($filter) {
                if (isset($filter['limit'])) {
                    $limit = "LIMIT " .$filter['limit'];
                }
                if (isset($filter['order'])) {
                    $order = "ORDER BY " .$filter['order'];
                }
            }
            return ["limit" => $limit, "order" => $order];
        }
    }
    