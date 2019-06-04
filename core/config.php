<?php

    namespace Poki;

    class Config {

        public static $db_user = "root";

        public static $db_password = "";

        public static $db_name = "";

        public static $db_host = "localhost";

        public static $db_type = "mysql";

        public static $serve_port = 9000;

        public static $dev_host = "localhost";

        public static $share_host = "192.168.43.56";

        public static $fields_files_path = ROOT . 'appfiles/fields_files/';

        public static $fields_files_webpath = WROOT . 'appfiles/fields_files/';

        public static $jsonp_files_path = ROOT . 'appfiles/category_params_files/';

        public static $appfolder = "noset";
    }