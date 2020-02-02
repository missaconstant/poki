<?php

    namespace Poki;

    class ViewProvider extends controlleur
    {

        private static function getViewHTML($viewpath)
        {
            return ROOT . 'core/plugers/views/' . $viewpath . '.php';
        }

        public static function includeViewHTML($viewpath, $scopeVars=[])
        {
            extract($scopeVars);
            include ROOT . 'pk-plugins/'. $GLOBALS['plugid'] .'/views/includes/'. $viewpath .'.php';
        }

        private static function serialize($array, $jump=[])
        {
            $parts = [];

            foreach ($array as $k => $v)
            {
                if (in_array($k, $jump)) continue;

                $parts[] = $k . '="'. $v .'"';
            }

            return implode(' ', $parts);
        }

		public static function generateRandomString($length = 25) {
			$characters			= '0123456789abcdef';
			$charactersLength	= strlen($characters);
			$randomString		= '';

			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}

			return $randomString;
		}

        public static function form($options)
        {
            $lines     = $options['fields'];
            $attrs     = self::serialize($options['attributes'], ['action']);
            $action    = Routes::find('plugins') . '/' . $GLOBALS['plugid'] . '/' . $options['attributes']['action'];
            $buttons   = (object) $options['buttons'];
            $formtitle = $options['title'];
            $formdesc  = $options['description'];

            include self::getViewHTML('form');
        }

        public static function table($options)
        {
            $headers   = $options['headers'];
            $lines     = $options['lines'];
            $attrs     = self::serialize($options['attributes']);
            $formtitle = $options['title'];
            $formdesc  = $options['description'];
            $datatable = isset($options['datatable']) && $options['datatable'] ? 'pk-datatable' : '';

            include self::getViewHTML('table');
        }

        public static function alert($options)
        {
            $type    = $options['type'];
            $message = $options['message'];
            $dismiss = isset($options['dismissible']) && $options['dismissible'];

            include self::getViewHTML('alert');
        }

        public static function highlight($options)
        {
            $language  = $options['lang'];
            $code      = $options['content'];

            include self::getViewHTML('highlight');
        }

        public static function countbox($options)
        {
            $count  = $options['count'];
            $label  = $options['label'];
            $icon   = $options['icon'];
            $icolor = '#' . $options['iconcolor'];
            $attrs  = $options['attrs'] ?  self::serialize( $options['attrs'] ) : '';
            $element= $options['element'] ?? 'div';

            include self::getViewHTML('countbox');
        }

    }
