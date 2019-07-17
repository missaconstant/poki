<?php

    namespace Poki;

	/**
	* Some tools to help developpers for some actions
	* Why not a dm(do_more) tool ???
	*/
	class dm
	{
		
		/*
		* Take a string(a,b,c) and return an html list
		*/
		public static function lineToList($string,$delim="-",$class="",$el="li"){
			$list = explode($delim, $string) ;
			$returns = '' ;
			$class = $class!='' ? " class=\"$class\"" : '' ;
			for($i=0;$i<count($list);$i++){
				$returns .= '<li'.$class.'>'.$list[$i].'</li>' ;
			}
			echo $returns ;
		}

		public static function uploadFiles($files,$handler){
			$count = count($files['name']) ;
			$fileArray = array() ;
			for($i=0; $i<$count; $i++){
				/* getting extension */
				$ext = explode('.', $files['name'][$i]) ;
				$ext = $ext[count($ext)-1] ;
				/* creating file */
				$file = array(
					"name" => $files['name'][$i],
					"error" => $files['error'][$i],
					"size" => $files['size'][$i],
					"tmp_name" => $files['tmp_name'][$i],
					"extension" => strtolower($ext)
				) ;
				$fileArray[] = $file ;
			}
			/* handling */
			for($i=0; $i<count($fileArray); $i++){
				if($handler) $handler($fileArray[$i],$i) ;
			}
		}

		static function isNumber($num){
			return preg_match("#[A-Za-z]#", $num) ? 0 : 1 ;
		}

		static function getFromCsv($content){
			/* splitting $content into lines */
			$sected = explode("\n", $content) ;

			/* getting rows(header) name */
			$rows = explode(',',trim($sected[0])) ;
			/* getting rows number */
			$number_rows = count($rows) ;
			/* creating lines array to receive all the lines */
			$lines = [] ;

			/* extracting datas */
			foreach($sected as $id => $val) {
				if($id && strlen(trim($val))){
					/* getting rows in data line */
					$data_line_rows = explode(',', $val) ;
					/* have this line same rows number as the header ? */
					if(count($data_line_rows)==count($rows)){
						/* creating associated array for line with array(col=>value) */
						$line = [] ;
						/* looping on rows to create associated array */
						for($i=0; $i<count($rows); $i++){
							/*$line[$rows[$i]] = $data_line_rows[$i] ;*/
							$line[] = $data_line_rows[$i] ;
						}
						/* now adding this line to lines array */
						$lines[] = $line ;
					}
					else{
						throw new Exception("Le nombre de colones de la ligne ".$id." ne correspond pas au nombre de ligne dans l'entête du fichier", 1);
						/*break ;*/
						return false ;
					}
				}
			}
			/* returning the result */
			return $lines ;
		}

		public static function lorem($echo=true){
			if($echo){
				echo '
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
				;
			}
			else
				return 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.' ;
		}

		public static function sendMail($from,$to,$msg,$subject,$senderName,$ishtmlcontent=0){
			$mail = $to ;

			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
			{
				$passage_ligne = "\r\n";
			}
			else
			{
				$passage_ligne = "\n";
			}
			//=====Déclaration des messages au format texte et au format HTML.
			// $message_txt = $msg;
			$message = '';
			$message_html = '
			<html>
				<head>
				  <title>Nouveau message !</title>
				</head>
				<body style="background:#dfdfdf;padding:30px;color:#333;">
					<div style="width:80%;margin:auto;text-align:center;">
						<a href="#">
							<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTbIVB_QPwbdOS877VfU3KKc08c4yisHJbfxqng92nfSkhwqpOJ3w" style="width:100px;margin-bottom:20px;"/>
						</a>
					</div>
				  	<div style="background:#fff;width:80%;margin:auto;">
				  		<div style="padding:20px 15px;">
				  			'.($ishtmlcontent ? $msg : str_replace("\n", "<br/><br/>", $msg)).'
				  		</div>
				  	</div>
				  	<div style="text-align:center;color:#666;margin-top:15px;">
				  		&copy;copyright https://showme.ci 2018
				  	</div>
				</body>
			</html>
			';
			//==========
			 
			//=====Création de la boundary
			$boundary = "-----=".md5(rand());
			//==========
			 
			//=====Définition du sujet.
			$sujet = $subject ;
			//=========
			 
			//=====Création du header de l'e-mail.
			$header = "From: \"".$senderName."\"<".$from.">".$passage_ligne;
			$header.= "Reply-to: \"".$senderName."\" <".$from.">".$passage_ligne;
			$header.= "MIME-Version: 1.0".$passage_ligne;
			$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			//==========
			 
			//=====Création du message.
			// $message = $passage_ligne."--".$boundary.$passage_ligne;
			// //=====Ajout du message au format texte.
			// $message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
			// $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			// $message.= $passage_ligne.$message_txt.$passage_ligne;
			//==========
			$message.= $passage_ligne."--".$boundary.$passage_ligne;
			//=====Ajout du message au format HTML
			$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_html.$passage_ligne;
			//==========
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			//==========
			 
			//=====Envoi de l'e-mail.
			return mail($mail,$sujet,$message,$header);
			//==========
		}

		static function mdHash($value)
		{
			return md5(md5($value).md5(strlen($value)));
		}

		static function sendMailHelper($gateway, $sender, $receiver, $subject, $content, $senderName, $ishtmlcontent)
		{
			$c = curl_init();
			curl_setopt($c, CURLOPT_URL, $gateway);
			curl_setopt($c, CURLOPT_POST, true);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query([
				"sender" => $sender, "receiver" => $receiver, "subject" => $subject, "content" => $content, "sendername" => $senderName, "ishtml" => $ishtmlcontent
			]));
			
			$r = json_decode(curl_exec($c), true);
			curl_close($c);
			
			return $r;
		}

		static function getHttpQueryValues($query)
		{
			$parts = explode("&", $query);
			$array = [];
			foreach ($parts as $k => $v) {
				$s = explode("=", $v);
				$array[trim($s[0])] = isset($s[1]) ? trim($s[1]) : '';
			}
			return $array;
		}

		static function dump($vars)
		{
			if ( is_array($vars) )
			{
				for ($i=0; $i<count($vars); $i++)
				{
					var_dump($vars[$i]);
				}
			}
			else {
				var_dump($vars);
			}

			exit;
		}
	}