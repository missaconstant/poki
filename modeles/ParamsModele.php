<?php

    class ParamsModele extends modele
    {
        

        public function setLink($categoryname, $field, $link)
        {
            $params = $this->getCategoryParams($categoryname);
            if (!strlen($link) || $link == '0') {
                unset($params['links'][$field]);
            }
            else {
                $link = explode('/', $link);
                $linkto = $link[0];
                $label = $link[1];
                $params['links'][$field] = ["linkedto" => $linkto, "label" => $label];
            }
            return $this->saveCategoryParams($categoryname, $params);
        }

        public function getCategoryParams($categoryname)
        {
            return json_decode(file_get_contents(Config::$jsonp_files_path . "adm_app_$categoryname.params"), true);
        }

        public function createCategoryParams($category)
        {
            $categoryname = $category->name;
            $structure = [
                "name" => $categoryname,
                "links" => [],
                "created_at" => date('d-m-Y H:i')
            ];
            return file_put_contents(Config::$jsonp_files_path . "adm_app_$categoryname.params", json_encode($structure));
        }

        public function saveCategoryParams($categoryname, $datas)
        {
            return file_put_contents(Config::$jsonp_files_path . "adm_app_$categoryname.params", json_encode($datas));
        }

        public function deleteCategoryParams($categoryname)
        {
            return unlink(Config::$jsonp_files_path . "adm_app_$categoryname.params");
        }

        public function updateCategoryParams($category)
        {
            $categoryname = $category->oldname;
            $newname = $category->name;
            return rename(Config::$jsonp_files_path . "adm_app_$categoryname.params", Config::$jsonp_files_path . "adm_app_$newname.params");
        }

        public function changeFieldType($categoryname, $field, $type)
        {
            
        }
    }
    