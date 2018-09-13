<?php

    class Helpers
    {
        public static $types = [
            "varchar" => "Alphanumeric",
            "int" => "Numeric",
            "text" => "Text field",
            "char" => "File field"
        ];

        public static function getFieldPseudoType($oname, $withbagde=false)
        {
            $vars = ["varchar" => "info", "int" => "danger", "text" => "success", "char" => "primary"];
            $rets = $withbagde ? '<span class="p-2 bagde badge-pill badge-'. $vars[$oname] .'">'. self::$types[$oname] .'</span>' : self::$types[$oname];
            return $rets;
        }

        public static function displayHtmlField($type, $name, $value=false) {
            $field_id = uniqid();
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

        public static function isHtmlContent($content)
        {
            return $content ? preg_match("#&lt;(.*)&gt;#", $content) : false;
        }

        /**
         * Echo if exists
         */
        public static function eie($var, $property=false)
        {
            if (!isset($var)) return '';
            return $property ? $var->$property : $var;
        }
    }
    