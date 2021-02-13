<?php

    namespace Poki;

    class ParamsModele extends modele
    {
        

        public function setLink($categoryname, $field, $link)
        {
            $params = $this->getCategoryParams($categoryname);

            if (!strlen($link) || $link == '0')
            {
                unset($params['links'][$field]);
            }
            else {
                $link = explode('/', $link);
                $linkto = $link[0];
                $label = $link[1];
                $params['links'][$field] = [ "linkedto" => $linkto, "label" => $label, "joined_on" => $label ];
            }

            return $this->saveCategoryParams($categoryname, $params);
        }

        public function setFieldLabel($categoryname, $fields)
        {
            // get category params
            $params = $this->getCategoryParams($categoryname);

            // creating fields array if doesn't exists
            $params['fields'] = $params['fields'] ?? [];

            // adding field label to each field
            foreach ( $fields as $k => $field )
            {
                // being prudent to not destroy field if already exists and create it if not exists => thank in few time
                $params['fields'][ $field->name ] = $params['fields'][ $field->name ] ?? [];

                // set field label
                $params['fields'][ $field->name ]['fieldlabel'] = $field->fieldlabel;
            }

            // update file fields
            return $this->saveCategoryParams($categoryname, $params);
        }

        public function getCategoryParams($categoryname)
        {
            return json_decode(file_get_contents(Config::$jsonp_files_path . CATEG_PREFIX . "$categoryname.params"), true);
        }

        public function createCategoryParams($category)
        {
            $params = [];

            if ( $category->oldname )
            {
                $params = $this->getCategoryParams( $category->oldname );
                
                $params['name']     = $category->name;
                $params['label']    = $category->label;
            }
            else {
                $params      = [
                    "name"      => $category->name,
                    "label"     => $category->label,
                    "links"     => [],
                    "fields"    => [],
                    "created_at" => date('d-m-Y H:i')
                ];
            }

            // delete the older category if name has changed
            if ( $category->oldname && $category->name != $category->oldname )
            {
                if ( $this->deleteCategoryParams( $category->oldname ) )
                {
                    return $this->saveCategoryParams( $category->name, $params );
                }
                else {
                    return false;
                }
            }
            else {
                return $this->saveCategoryParams( $category->name, $params );
            }

        }

        public function saveCategoryParams($categoryname, $datas)
        {
            return file_put_contents(Config::$jsonp_files_path . CATEG_PREFIX . "$categoryname.params", json_encode($datas));
        }

        public function deleteCategoryParams($categoryname)
        {
            return unlink(Config::$jsonp_files_path . CATEG_PREFIX . "$categoryname.params");
        }

        public function updateCategoryParams($category)
        {
            $categoryname   = $category->oldname;
            $newname        = $category->name;

            return rename(Config::$jsonp_files_path . CATEG_PREFIX . "$categoryname.params", Config::$jsonp_files_path . CATEG_PREFIX . "$newname.params");
        }

        public function changeFieldType($categoryname, $field, $type)
        {
            
        }
    }
    