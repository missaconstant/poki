<?php

	namespace Poki;

	/**
	* classe Files permettra de gerer les appels sur les fichiers
	*/
	class Files
	{
		static function style($name){
			if(file_exists(ROOT.'files/css/'.$name.'.css'))
				return WROOT.'files/css/'.$name.'.css' ;
			else
				throw new \Exception("Fichier style demandé introuvable", 1);
		}

		static function script($name){
			if(file_exists(ROOT.'files/js/'.$name.'.js'))
				return WROOT.'files/js/'.$name.'.js' ;
			else
				throw new \Exception("Fichier script demandé introuvable", 1);
		}

		static function image($name){
			if(file_exists(ROOT.'files/images/'.$name))
				return WROOT.'files/images/'.$name ;
			else
				throw new \Exception("Fichier image demandé introuvable", 1);
		}

		static function file($path){
			if(file_exists(ROOT.'files/'.$path))
				return WROOT.'files/'.$path ;
			else
				throw new \Exception("Fichier image demandé introuvable", 1);
		}

		static function plugin($path){
			if(file_exists(ROOT.'files/plugins/'.$path))
				return WROOT.'files/plugins/'.$path ;
			else if(file_exists(ROOT.'files/bower_components/'.$path))
                return WROOT.'files/bower_components/'.$path ;
			else
				throw new \Exception("Fichier image demandé introuvable", 1);
		}

		static function includes($name){
			if(file_exists(ROOT.'vues/includes/'.$name.'.inc.php'))
				include_once ROOT.'vues/includes/'.$name.'.inc.php' ;
			else
				throw new \Exception("Fichier à inclure introuvable", 1);

		}

		static function verifyFile($file,$requiredExt=false){
			$result = array(true,'Le ficher est bon !') ;
			/* gettin extension */
			$ext = explode('.', $file['name']) ;
			$ext = $ext[count($ext)-1] ;
			/* depend on $_FILES[] var */
			if($file['error']!=0)
				return array(false,'Le fichier contient une ou plusieurs erreurs !') ;
			else if(strlen($file['name'])<4)
				return array(false,'Le nom du fichier est incorrect !') ;
			else if($requiredExt && !in_array(strtolower($ext), $requiredExt))
				return array(false, 'Le type du fichier est inconnu !') ;
			else
				return $result ;
		}

		static function getExtension($file){
			$ext = explode('.', $file['name']) ;
			$ext = $ext[count($ext)-1] ;
			return $ext ;
		}
	}
