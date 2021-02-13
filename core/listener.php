<?php

    namespace Poki;

	abstract class Listener {

		abstract public static function onCreate($params);

		// public static function onBeforeCreate($params);

		abstract public static function onUpdate($params);

		// public static function onBeforeUpdate($params);

		abstract public static function onDelete($params);

		// public static function onBeforeDelete($params);

		// abstract public static function onRead($params);

		// public static function onBeforeRead($params);

		// abstract public static function onLogin($params);

		// public static function onBeforeLogin($params);

		// abstract public static function onLogout($params);

		// public static function onBeforeLogout($params);

	}

	/* Poki plugin generator */

	class Generator
	{

		public static function startFile ()
		{
			$content = 	"<?php \n\n" .
						"\tnamespace Poki;\n\n" .
						"\tclass Main extends Pluger\n" .
						"\t{\n" .
						"\t\tpublic function hello(\$params)\n" .
						"\t\t{\n" .
						"\t\t\treturn [ \"message\" => \"Hello World ! This is Poki !\" ];\n" .
						"\t\t}\n\n" .
						"\t\tpublic function about(\$params)\n" .
						"\t\t{\n\n" .
						"\t\t}\n" .
						"\t}";

			return $content;
		}

		public static function apiStartFile()
		{
			$content = 	"<?php \n\n" .
						"\tnamespace Poki;\n\n" .
						"\tclass ApiEntry extends Pluger\n" .
						"\t{\n" .
						"\t\tpublic function hello(\$get, \$post)\n" .
						"\t\t{\n" .
						"\t\t\texit(json_encode( [ \"message\" => \"Hello World ! This is Poki !\" ] ));\n" .
						"\t\t}\n" .
						"\t}";

			return $content;
		}

		public static function helloView()
		{
			$content = 	"<h1>Hello World !</h1>";

			return $content;
		}

		public static function aboutView()
		{
			$content =  "<h1>ABOUT POKI !</h1>\n" .
						"<p>\n" .
							"\tLorem ipsum dolor, sit amet consectetur adipisicing elit. At hic esse non tenetur sit aperiam, suscipit consectetur placeat autem illo ex sint? Voluptatem ad laborum dolorum, officia porro fugiat pariatur.\n" .
						"</p>";

			return $content;
		}

		public static function listenerFile()
		{
			$content = 	"<?php \n\n" .
						"\tnamespace Poki;\n\n" .
						"\tclass Mylistener extends Listener\n" .
						"\t{\n" .
						"\t\tpublic static function onCreate(\$params)\n" .
						"\t\t{\n" .
						"\t\t\t//self::log(\"Un element a été ajouté dans la categorie \" . \$params['categoryname']);\n" .
						"\t\t}\n\n" .
						"\t\tpublic static function onUpdate(\$params)\n" .
						"\t\t{\n" .
						"\t\t\t//self::log(\"L'élement avec pour ID \". \$params['contentid'] .\" a été mis à jour dans la categorie \" . \$params['categoryname']);\n" .
						"\t\t}\n" .
						"\t\tpublic static function onDelete(\$params)\n" .
						"\t\t{\n" .
						"\t\t\t//self::log(\"L'élement avec pour ID \". \$params['contentid'] .\" a été supprimé dans la categorie \" . \$params['categoryname']);\n" .
						"\t\t}\n" .
						"\t}";

			return $content;
		}

	}

	if (class_exists('ZipArchive')) {
		class FlxZipArchive extends \ZipArchive
		{
			public function addDir($location, $name)
			{
				$this->addEmptyDir($name);
				$this->addDirDo($location, $name);
			}

			private function addDirDo($location, $name)
			{
				$name .= '/';
				$location .= '/';
				$dir = opendir($location);

				while ($file = readdir($dir))
				{
					if ($file == '.' || $file == '..') continue;
					$do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
					$this->$do($location . $file, $name . $file);
				}

				closedir($dir);
			}
		}
	}
