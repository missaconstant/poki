<?php

    namespace Poki;

    class ContentsModele extends modele
    {
        public function creerContent($content, $categoryname, $edition=false)
        {
            try {
                $leftstring = [];
                $rightstring = [];
                /* add date to content */
                $content['added_at'] = date('d-m-Y H:i');
                /* */
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
            catch (\Exception $e) {
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
            catch (\Exception $e) {
                die(json_encode([ "error" => true, "message" => $e->getMessage() ]));
            }
        }

        public function trouverTousContents($categoryname, $checkJoin=false, $filter=false)
        {
            try {
                # getting joining table
                $joining = $this->getCategoryParams($categoryname);
                $joining['name'] = $categoryname;

                # setting the joining keys to replace the defaul one "id"
                $joining['joining_keys'] = $checkJoin && is_array($checkJoin) ? $checkJoin : [];
                

                # searching right query string
                $sql = '';
                if ($checkJoin && count($joining['links'])) {
                    $sql = $this->getQueryStringFromCategoryParams($joining, $categoryname, false, $filter);
                }
                else {
                    $filter = $this->getFilter($filter);
                    $limit = $filter['limit'];
                    $order = $filter['order'];
                    $where = isset($filter['where']) && strlen(trim($filter['where'])) ? 'WHERE ' . $filter['where']:'';
                    $sql = "SELECT * FROM adm_app_$categoryname $where $order $limit";
                }
                #doing query
                $q = modele::$bd->query($sql);
                $r = $q->fetchAll(\PDO::FETCH_ASSOC);
                $q->closeCursor();
                
                return $r;
            }
            catch (\Exception $e) {
                return false;
            }
        }

        /**
         * @method trouverContents
         * @param string categoryname
         * @param string|int find - id or content value to check (check in specified to another value than id)
         * @param boolean checkJoin - wether to do joinning
         * @param string check_in  - where to check for search
         */
        public function trouverContents($categoryname, $find, $checkJoin=false, $check_in='id')
        {
            try {
                # getting joining table
                $joining = $this->getCategoryParams($categoryname);
                $joining['name'] = $categoryname;

                # setting the joining keys to replace the defaul one "id"
                $joining['joining_keys'] = $checkJoin && is_array($checkJoin) ? $checkJoin : [];
                
                # searching right query string
                $sql = '';
                if ($checkJoin && count($joining['links'])) {
                    $sql = $this->getQueryStringFromCategoryParams($joining, $categoryname, $find);
                }
                else {
                    $sql = "SELECT * FROM adm_app_$categoryname WHERE $check_in='$find'";
                }
                #doing query
                $q = modele::$bd->query($sql);
                $r = $q->fetchAll(\PDO::FETCH_ASSOC);
                $q->closeCursor();

                return count($r) ? $r[0]:false;
            }
            catch (\Exception $e) {
                echo json_encode([$e->getMessage()]); exit();
            }
        }

        public function trouverContentsAccessor($categoryname, $checkJoin, $contentid)
        {
            return $this->trouverContents($categoryname, $contentid, $checkJoin);
        }

        public function trouverValuesContents($categoryname, $check_in, $contentlist)
        {
            $list = [];

            foreach ($contentlist as $k => $value)
            {
                $content = $this->trouverContents($categoryname, $value, false, $check_in);

                if ($content)
                {
                    $newctnt = [];

                    foreach ($content as $k => $value)
                    {
                        if ($k != 'active' && $k != 'added_at' && $k != 'combined_fields')
                        {
                            $newctnt[ $categoryname .'_' .$k ] = $value;
                        }
                    }

                    $list[] = $newctnt;
                }
            }

            return $list;
        }

        public function trouverIdsContents($categoryname, $idlist)
        {
            $list = $this->trouverValuesContents($categoryname, 'id', $idlist);

            return $list;
        }

        public function supprimerContents($categoryname, $contentid) {
            try {
                $ids = is_array($contentid) ? $contentid : [ $contentid ];

                foreach ($ids as $k => $id) {
                    $q = modele::$bd->exec("DELETE FROM adm_app_$categoryname WHERE id='$id'");
                }
                
                return true;
            }
            catch (\Exception $e) {
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
            catch (\Exception $e) {
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
                $r = $q->fetchAll(\PDO::FETCH_COLUMN);
                $q->closeCursor();
                return count($r) ? $r : false;
            }
            catch (\Exception $e) {
                // exit(json_encode([$e->getMessage()]));
            }
        }

        public function toggleContent($categoryname, $contentid, $newstate)
        {
            try {
                $contentids = is_array($contentid) ? $contentid : [ $contentid ];

                foreach ($contentids as $k => $id) {
                    modele::$bd->exec("UPDATE adm_app_$categoryname SET active=$newstate WHERE id=$id");
                }

                return true;
            }
            catch(Exception $e) {
                return false;
            }
        }

        /**
         * searchInCategory
         * @param $categoryname string
         * @param $kwd string
         * @param $limit string (ex: "0, val")
         */
        public function searchInCategory($categoryname, $kwd, $limit=false, $count=false)
        {
            try {
                # getting category
                $category = $this->trouverCategory(str_replace('adm_app_', '', $categoryname));
                # getting joining table
                $joining = $this->getCategoryParams(str_replace('adm_app_', '', $categoryname));
                $joining['name'] = str_replace('adm_app_', '', $categoryname);
                // $query = "SELECT ". ($count==true ? "count(*) as searchcount" : "*") ." FROM $categoryname WHERE ";
                # getting query string
                $query = $this->getQueryStringFromCategoryParams($joining, str_replace('adm_app_', '', $categoryname), false, [
                    "like" => $kwd,
                    "limit" => $limit ? $limit : false,
                ], $count);
                $q = modele::$bd->query($query);
                $r = $q->fetchAll(\PDO::FETCH_ASSOC);
                $q->closeCursor();
                return $r;
            } catch (\Exception $e) {
                echo json_encode(["error" => true, "message" => $e->getMessage()]);
            }
        }

        public function getQueryStringFromCategoryParams($joining, $categoryname, $contentid=false, $filter=false, $count=false)
        {
            # list of table to take in select query
            $categoriesJoined = $this->getCategoriesJoinedName($joining['links']);
            array_unshift($categoriesJoined, $categoryname);

            # to save category fields
            $fields = [];

            # preparing SELECT query string
            $querySelectStrings = array_map(function ($category) use ($categoryname, &$fields) {
                $tablefields = $this->trouverCategory($category, ($category!=$categoryname));
                if ($tablefields) { // cause can be false if category does not exists
                    $tablestring = [];
                    foreach ($tablefields as $k => $field) {
                        $tablestring[] = 'adm_app_'.$category .'.'. $field . ($category!=$categoryname ? ' as '. $category .'_'. $field : '');
                        # saving fields
                        $fields[] = 'adm_app_'.$category .'.'. $field;
                    }
                    return implode(', ', $tablestring);
                }
            }, $categoriesJoined);

            # preparing LIKE condition
            $likestring = [];
            $likeword = isset($filter['like']) ? $filter['like'] : '';
            foreach ($fields as $k => $field) {
                if (!preg_match("#.added_at$#", $field) & !preg_match("#.active$#", $field) && !preg_match("#.id$#", $field)) {
                    $likestring[] = $field . " LIKE '%$likeword%'";
                }
            }

            # preparing WHERE condition
            $joinedstring = [];
            foreach ($joining['links'] as $fieldlinked => $link)
            {
                $linkedto       = 'adm_app_'. $link['linkedto'];
                $linked         = 'adm_app_'. $joining['name'];
                $joinkey        = isset( $joining['joining_keys'][$fieldlinked] ) ? $joining['joining_keys'][$fieldlinked] : $link['joined_on'];

                $joinedstring[] = "LEFT JOIN $linkedto ON $linkedto.".  $joinkey  ."=$linked.$fieldlinked";
            }

            # preparing filters instruction
            $filter = $this->getFilter($filter);
            $limit = $filter['limit'];
            $order = $filter['order'];
            $where = $filter['where'];

            # preparing where string
            $isWhere = $contentid || $filter['like'];
            $contentWhere = $contentid ? "adm_app_$categoryname.id=$contentid" : null;
            $likeWhere = isset($filter['like']) ? implode(' OR ', $likestring) : null;
            $whereString = $isWhere ? 'WHERE ' . implode(' AND ', array_filter([$contentWhere, $likeWhere])) : '';
            $whereString .= strlen($where) ? (strlen($whereString) ? ' AND ' . $where : ' WHERE ' . $where) : '' ;

            # preparing count string
            $categoryname = str_replace('adm_app_', '', $categoryname);
            $countString = $count ? "COUNT(adm_app_$categoryname.id) as countlines" : false;

            // exit("SELECT ". ($countString ? $countString : implode(', ', $querySelectStrings)) ." FROM adm_app_$categoryname ". implode(' ', $joinedstring) . " $whereString $order $limit");

            return "SELECT ". ($countString ? $countString : implode(', ', $querySelectStrings)) ." FROM adm_app_$categoryname ". implode(' ', $joinedstring) . " $whereString $order $limit";
        }

        private function getFilter($filter)
        {
            return [
                "limit" => $filter && isset($filter['limit']) && strlen(trim($filter['limit'])) ? "LIMIT " . $filter['limit'] : '',
                "order" => $filter && isset($filter['order']) && strlen(trim($filter['order'])) ? "ORDER BY " . $filter['order'] : '',
                "like" => $filter && isset($filter['like']) && strlen(trim($filter['like'])) ? $filter['like'] : false,
                "where" => $filter && isset($filter['where']) && strlen(trim($filter['where'])) ? $filter['where'] : '',
            ];
        }
    }