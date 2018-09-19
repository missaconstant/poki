<?php

    class Helpers
    {
        /**
         * @var types
         * Corresponding types
         */
        public static $types = [
            "varchar" => "Alphanumeric",
            "int" => "Numeric",
            "text" => "Text field",
            "char" => "File field"
        ];

        /**
         * @var categoryparams
         * Selected category json settings
         */
        public static $categoryparams = [];

        /**
         * Return field Corresponding ty according to @var types
         * @param oname : Original name
         * @param withbagde : wether should return a bootstrap badge (optional)
         * @return html
         */
        public static function getFieldPseudoType($oname, $withbagde=false)
        {
            $vars = ["varchar" => "info", "int" => "danger", "text" => "success", "char" => "primary"];
            $rets = $withbagde ? '<span class="p-2 bagde badge-pill badge-'. $vars[$oname] .'">'. self::$types[$oname] .'</span>' : self::$types[$oname];
            return $rets;
        }

        /**
         * Displays html form field corresponding to data type and length
         * @param type : field type
         * @param name : field name attribute value
         * @param value : Default value to put on field when rendering (optional)
         * @param categoryname : Field category name (optional)
         * @return html
         */
        public static function displayHtmlField($type, $name, $value=false, $categoryname=false) {
            $field_id = uniqid();
            $linked = self::isLinked($categoryname, $name);
            if (!$linked) {
                switch ($type) {
                    case 'int':
                        return '<input type="number" max-length="255" class="form-control" name="'. $name .'" value="'. ($value ? $value:'') .'">';
                        break;
                    case 'varchar':
                        return '<input type="text" max-length="255" class="form-control" name="'. $name .'" value="'. ($value ? $value:'') .'">';
                        break;
                    case 'char':
                        $files = explode('|', $value);
                        $items = [];
                        $tabvalue = [];
                        
                        foreach ($files as $k => $file) {
                            if (strlen(trim($file))) {
                                $items[] = '<div class="item itfile mb-1" data-fname="'. $file .'"><a class="fname" href="'. Config::$fields_files_webpath . $file .'" target="_blank" id="'. (explode('.', $file))[0] .'">'. $file .'</a> <span class="closer" onclick="removeUploaded(this, \''. $field_id .'\')">&times;</span></div>';
                                $tabvalue[] = $file;
                            }
                        }
                        
                        return '
                                <input type="file" class="form-control adminizer-file-field" data-field="'. $field_id .'" multiple>
                                <input type="hidden" id="'. $field_id .'" name="'. $name .'" value="'. implode('|', $tabvalue) .'">
                                <div class="file-uploaded mt-2">'. implode('', $items) .'</div>
                            ';
                        break;
                    case 'text':
                        return '<textarea class="form-control summerable '. (self::isHtmlContent($value) ? 'summered':'') .'" name="'. $name .'">'. ($value ? $value:'') .'</textarea>';
                        break;
                }
            }
            else {
                require_once ROOT . 'modeles/ContentsModele.php';

                $mdl = new ContentsModele();
                $foreigns = $mdl->trouverTousContents($linked['linkedto']);
                $options = ['<option value="0"></option>'];
                
                foreach ($foreigns as $k => $foreign) {
                    $selected = $value == $foreign['id'] ? 'selected' : '';
                    $options[] = '<option value="'. $foreign['id'] .'" '. $selected .'>'. $foreign[$linked['label']] .'</option>';
                }
                
                $field = '<select class="form-control block linkchoose" onchange="bindSelectChange(\''. $field_id .'\', this.value)" style="width:100%;">'. implode("", $options) .'</select>';
                $field.= '<input type="hidden" name="'. $name .'" id="'. $field_id .'">';

                return $field;
            }
        }

        /**
         * Check wether a field is linked with another one and return linked label
         * @param categoryname : name of category
         * @param field : name of the field
         * @param value : field value
         * @return string_label
         */
        public static function checkLinkedLabel($categoryname, $field, $value)
        {
            if ($linked = self::isLinked($categoryname, $field)) {
                require_once ROOT . 'modeles/ContentsModele.php';

                $mdl = new ContentsModele();
                $foreign = $mdl->trouverContents($linked['linkedto'], $value);
                return $foreign ? $foreign[$linked['label']] : '';
            }
            else {
                return $value;
            }
        }

        /**
         * Checks wether a field is linked with another one
         * @param categoryname : name of category of field
         * @param field : name of field
         * @return Array
         */
        public static function isLinked($categoryname, $field)
        {
            return isset(self::getCategoryParams($categoryname, 'links')[$field]) ? self::getCategoryParams($categoryname, 'links')[$field] : false;
        }

        /**
         * Checks wether content is html
         * @param content : content to check on
         * @return bool
         */
        public static function isHtmlContent($content)
        {
            return $content ? preg_match("#&lt;(.*)&gt;#", $content) : false;
        }

        /**
         * Echo if exists
         * @param var : variable to show
         * @param property : what property show when object
         * @param othervalue : to return when not exists
         * @return text
         */
        public static function eie($var, $property=false, $othervalue='')
        {
            if (!isset($var)) return $othervalue;
            return $property ? $var->$property : $var;
        }

        /**
         * Gets category settings values
         * @param categoryname : name of category to see on
         * @param param : the setting parameter to use
         * @param renew : should load setting file again or use saved version
         */
        public static function getCategoryParams($categoryname, $param, $renew=false)
        {
            if (!isset(self::$categoryparams[$categoryname]) || $renew) {
                self::$categoryparams[$categoryname] = json_decode(file_get_contents(Config::$jsonp_files_path . "adm_app_$categoryname.params"), true);
            }
            return self::$categoryparams[$categoryname][$param];
        }
    }
    