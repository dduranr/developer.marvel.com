<?php

	class DuranHelpers {
		public $cadena;
		public $separador;
		public $quitarespacios;
		public $mayusminus_originales;
		public $mayus;


		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨     RECUPERA EL NOMBRE DE UN ARCHIVO (sin la extensión y su punto)               ¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		// No hay que hacerle nada a la cadena antes de pasarla como parámetro a este método. Si se le hace algo, posiblemente no funcione este método.
		public function getFileName($_cadena) {
			$DuranHelpers = new DuranHelpers;
			$this->cadena = $_cadena;
			$this->cadena = trim($this->cadena);
			$fileName_extension = $DuranHelpers->getFileExtension($this->cadena);
			$this->cadena = $DuranHelpers->slugify($this->cadena,'_',false);
			$fileName_extension_length = (strlen($fileName_extension))+1; // El length de la extensión incluye el punto (por eso el +1)
			return substr($this->cadena,0,-$fileName_extension_length); // Se devuelve el nombre del archivo slugified, quitando del final el número de caracteres del length de la extensión
		}


		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨     RECUPERA LA EXTENSIÓN DE UN ARCHIVO (sin el punto)                           ¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		public function getFileExtension($_cadena) {
			$this->cadena = $_cadena;
			$this->cadena = trim($this->cadena);
			return substr(strrchr($this->cadena,'.'),1);
		}


		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨     CONVIERTE CADENA EN FORMATO NOMBRE DE ARCHIVO (opción para unir palabras con caracter custom)
		// ¨¨¨¨¨¨¨     Opción para unir palabras con caracter custom                                ¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		public function slugify($_cadena,$_separador,$_quitarespacios=false) {
			$this->cadena = $_cadena;
			$this->separador = $_separador;
			$this->quitarespacios = $_quitarespacios;

			// Eliminamos espacios
			if ($this->quitarespacios) $this->cadena = str_replace(' ', '', $this->cadena);
			// Reemplazamos acentos y otros caracteres
			$this->cadena = str_replace(
				array('Á','É','Í','Ó','Ú','Ñ','á','é','í','ó','ú','ñ','À','È','Ì','Ò','Ù','à','è','ì','ò','ù','\'','&'),
				array('a','e','i','o','u','n','a','e','i','o','u','n','a','e','i','o','u','a','e','i','o','u','',''),
				$this->cadena);
			// replace non letter or digits by -
			$this->cadena = preg_replace('~[^\pL\d]+~u', $this->separador, $this->cadena);
			// transliterate
			$this->cadena = iconv('utf-8', 'us-ascii//TRANSLIT', $this->cadena);
			// remove unwanted characters
			$this->cadena = preg_replace('~[^-\w]+~', '', $this->cadena);
			// trim
			$this->cadena = trim($this->cadena, $this->separador);
			// remove duplicate -
			$this->cadena = preg_replace('~-+~', $this->separador, $this->cadena);
			// remove duplicate -
			$this->cadena = preg_replace('~_+~', $this->separador, $this->cadena);
			// lowercase
			$this->cadena = strtolower($this->cadena);

			if (empty($this->cadena)) {
				return 'n'.$this->separador.'a';
			}
			return $this->cadena;
		}






		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨           QUITA ACENTOS          ¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		// Por defecto devuelve la cadena con mayúsculas/minúsculas originales pero sin acentos.
		// Parámetros:
		//		$cadena:				String que contiene el texto donde se habrán de quitar acentos.
		//		$mayusminus_originales:	Si es "true", devuelve la cadena con las mayúsculas y minúsculas tal cual estaban originalmente,
		//								y $mayus no importa.
		//		$mayus:					Sólo aplica si $mayusminus_originales es "false". Si $mayus es true se devuelve la cadena en
		// 								mayusculas, de lo contrario van en minúsculas.

		public function quitarAcentos($_cadena, $_mayusminus_originales=true, $_mayus=false) {
			$this->cadena                = $_cadena;
			$this->mayusminus_originales = $_mayusminus_originales;
			$this->mayus                 = $_mayus;

			$this->cadena = str_replace(
				array("Á", "É", "Í", "Ó", "Ú", "á", "é", "í", "ó", "ú", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&Agrave;", "&Egrave;", "&Igrave;", "&Ograve;", "&Ugrave;", "&agrave;", "&egrave;", "&igrave;", "&ograve;", "&ugrave;"),
				array("A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u"),
				$this->cadena
			);
			if ($this->mayusminus_originales) {
				return $this->cadena;
			}
			else {
				if ($this->mayus) {
					$this->cadena = strtoupper($this->cadena);
					return $this->cadena;
				}
				else {
					$this->cadena = strtolower($this->cadena);
					return $this->cadena;
				}
			}
		}




		// http://www.forosdelweb.com/f18/aporte-funcion-para-evitar-xss-948577/ Por: GatorV
		public function filterXSS($val) {
			$this->cadena = trim($val);
			// Remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed this prevents some character re-spacing such as <java\0script> note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
			$this->cadena = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $this->cadena);

			// Straight replacements, the user should never need these since they're normal characters. This prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
			$search  = 'abcdefghijklmnopqrstuvwxyz';
			$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$search .= '1234567890!@#$%^&*()';
			$search .= '~`";:?+/={}[]-_|\'\\';
			for ($i = 0; $i < strlen($search); $i++) {
				// ;? matches the ;, which is optional
				// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

				// &#x0040 @ search for the hex values
				$this->cadena = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $this->cadena); // with a ;
				// @ @ 0{0,7} matches '0' zero to seven times
				$this->cadena = preg_replace('/(�{0,8}'.ord($search[$i]).';?)/', $search[$i], $this->cadena); // with a ;
			}

			// now the only remaining whitespace attacks are \t, \n, and \r
			$ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
			$ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
			$ra = array_merge($ra1, $ra2);

			$found = true; // keep replacing as long as the previous round replaced something
			while ($found == true) {
				$val_before = $this->cadena;
				for ($i = 0; $i < sizeof($ra); $i++) {
					$pattern = '/';
					for ($j = 0; $j < strlen($ra[$i]); $j++) {
						if ($j > 0) {
							$pattern .= '(';
							$pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
							$pattern .= '|(�{0,8}([9][10][13]);?)?';
							$pattern .= ')?';
						}
						$pattern .= $ra[$i][$j];
					}
					$pattern .= '/i';
					$replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
					$this->cadena = preg_replace($pattern, $replacement, $this->cadena); // filter out the hex tags
					if ($val_before == $this->cadena) {
						// no replacements were made, so exit the loop
						$found = false;
					}
				}
			}
			return $this->cadena;
		}

		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨          FRIENDLY POST           ¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		// --> Convierte la cadena final en entidades HTML
		public function friendlyPost($variable) {
			$variable_limpia = htmlspecialchars_decode(htmlentities($variable, ENT_NOQUOTES, 'UTF-8'), ENT_NOQUOTES);
			return trim($variable_limpia);
		}
		// --> Sin convertir la cadena final en entidades HTML
		public function friendlyPost2($variable) {
			$variable_limpia = htmlspecialchars($variable, ENT_QUOTES | ENT_HTML5, 'UTF-8');
			return trim($variable_limpia);
		}

		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨               LOG                ¨¨¨¨¨¨¨
		// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
		public function log($path_logs, $ubicacion, $linea, $descripcion) {
			$body_log   = date('Y-m-d H:i:s')." - ".$ubicacion." (".$linea.") - ".$descripcion."\n";
			$archivoLOG = fopen($path_logs."dduran_log", "a") or die("ERROR! Imposible abrir dduran_log. ");
			fputs($archivoLOG, $body_log);
			fclose($archivoLOG);
		}





		public function hyphenize($string) {
			$DuranHelpers = new DuranHelpers;
		    $expresiones_special = array(
		        "I'm"      => "I am",
		        "thier"    => "their"
		    );
		    return strtolower(
		        preg_replace(
		          array( '#[\\s-]+#', '#[^A-Za-z0-9\. -]+#' ),
		          array( '-', '' ),
		          // the full cleanString() can be downloaded from http://www.unexpectedit.com/php/php-clean-string-of-utf8-chars-convert-to-similar-ascii-char
		          $DuranHelpers->cleanString(
		              str_replace( // preg_replace can be used to support more complicated replacements
		                  array_keys($expresiones_special),
		                  array_values($expresiones_special),
		                  urldecode($string)
		              )
		          )
		        )
		    );
		}

		public function cleanString($text) {
		    $utf8 = array(
		        '/[áàâãªä]/u'   =>   'a',
		        '/[ÁÀÂÃÄ]/u'    =>   'A',
		        '/[éèêë]/u'     =>   'e',
		        '/[ÉÈÊË]/u'     =>   'E',
		        '/[ÍÌÎÏ]/u'     =>   'I',
		        '/[íìîï]/u'     =>   'i',
		        '/[óòôõºö]/u'   =>   'o',
		        '/[ÓÒÔÕÖ]/u'    =>   'O',
		        '/[úùûü]/u'     =>   'u',
		        '/[ÚÙÛÜ]/u'     =>   'U',
		        '/ç/'           =>   'c',
		        '/Ç/'           =>   'C',
		        '/ñ/'           =>   'n',
		        '/Ñ/'           =>   'N',
		        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
		        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
		        '/[“”«»„]/u'    =>   ' ', // Double quote
		        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
		    );
		    return preg_replace(array_keys($utf8), array_values($utf8), $text);
		}

	} // <DuranHelpers />
