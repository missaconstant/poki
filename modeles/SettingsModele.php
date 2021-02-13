<?php

    namespace Poki;

    class SettingsModele extends modele
    {
        public function create($keyname, $stringvalue) {

        }

        public function update($keyname, $stringvalue) {

        }

        public function get($keyname) {
            try {
                $q = modele::$bd->query("SELECT * FROM _adm_settings WHERE keyname='$keyname'");
                $r = $q->fetchAll(\PDO::FETCH_ASSOC);
                $q->closeCursor();
                return count($r) ? (object) $r[0] : false;
            }
            catch (\Exception $e) {
                if ( Config::$env == 'DEV' )
                    die( $e->getMessage() );
                else
                    return false;
            }
        }
    }
