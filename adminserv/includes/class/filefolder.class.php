<?php
/********************* Class FileFolder *********************/

abstract class FileFolder {
	
	/**
	* Récupère l'extension d'un fichier
	*
	* @param  string $filename -> Le fichier à extraire l'extension
	* @return string           -> L'extension en minuscule sans le .
	*/
	public static function getFilenameExtension($filename){
		$out = null;
		// Sélection de l'extension à partir du .
		$lastdotposition = strrpos($filename, '.');
		// Parse
		if($lastdotposition === 0){ $extension = substr($filename, 1); }
		else if($lastdotposition == ''){ $extension = $filename; }
		else{ $extension = substr($filename, $lastdotposition + 1); }
		// Retour
		return strtolower($extension);
	}
	
	
	/**
	* Remplace certains caractères par des underscores: _
	* 
	* @param string $str           -> La chaine de caractère à traiter
	* @param bool   $replaceAccent -> Condition pour remplacer les accents également
	* @return string
	*/
	public static function replaceSpecificChars($str, $replaceAccent = false){
		// Si on veut remplacer les accents
		if($replaceAccent){
			$str = self::replaceAccentChars($str);
		}
		// Sinon on remplace les caractères spécifiques
		$replace_chars = array(' ', "'", '"', '&', '<', '>');
		return str_replace($replace_chars, '_', $str);
	}
	
	
	/**
	* Remplace les caractères accentués par leur caractère normal
	* 
	* @param string $str -> La chaine de caractère à traiter
	* @return string
	*/
	public static function replaceAccentChars($str){
		if( self::is_utf8($str) ){
			$str = utf8_decode($str);
			$str = strtr($str, 'ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ', 'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn');
			$str = utf8_encode($str);
		}else{
			$str = strtr($str, 'ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ', 'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn');
		}
		return $str;
	} 
	
	
	/**
	* Vérifie si la chaine de caractère est en encodage UTF-8
	*
	* @source http://w3.org/International/questions/qa-forms-utf-8.html
	* @param string $str -> La chaine de caractère à traiter
	* @return bool
	*/
	public static function is_utf8($str){
		return preg_match('%^(?:
		[\x09\x0A\x0D\x20-\x7E] # ASCII
		| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
		| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
		| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
		| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
		| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
		| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
		| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
		)*$%xs', $str);
	}
	
	
	/**
	* Tronque une chaine de caractère suivant un nombre max de caractère
	*
	* @param string $string       -> La chaîne de caractère à réduire.
	* @param int    $length       -> Nb max de caractère
	* @param string $ending       -> Fin de la chaine de caractère (compris dans le nb max de caractère)
	* @param bool   $exact        -> Si false, la chaine ne sera pas coupée au milieu d'un mot
	* @param bool   $considerHtml -> Si true, les balises html seront correctement fermées
	* @return string Trimmed string.
	*/
	public static function truncateString($string, $length = 100, $ending = '...', $exact = false, $considerHtml = true){
		if($considerHtml){
			// if the plain text is shorter than the maximum length, return the whole text
			if( strlen( preg_replace('/<.*?>/', '', $string) ) <= $length ){
				return $string;
			}
			// splits all html-tags to scanable lines
			preg_match_all('/(<.+?>)?([^<>]*)/s', $string, $lines, PREG_SET_ORDER);
			$total_length = strlen($ending);
			$open_tags = array();
			$truncate = null;
			foreach($lines as $line_matchings){
				// if there is any html-tag in this line, handle it and add it (uncounted) to the output
				if( !empty($line_matchings[1]) ){
					// if it's an "empty element" with or without xhtml-conform closing slash
					if( preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1]) ){
						// do nothing
					}
					// if tag is a closing tag
					else if( preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings) ){
						// delete tag from $open_tags list
						$pos = array_search($tag_matchings[1], $open_tags);
						if( $pos !== false ){
							unset($open_tags[$pos]);
						}
					}
					// if tag is an opening tag
					else if( preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings) ){
						// add tag to the beginning of $open_tags list
						array_unshift($open_tags, strtolower($tag_matchings[1]));
					}
					// add html-tag to $truncate'd text
					$truncate .= $line_matchings[1];
				}
				// calculate the length of the plain text part of the line; handle entities as one character
				$content_length = strlen( preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]) );
				if( $total_length + $content_length > $length ){
					// the number of characters which are left
					$left = $length - $total_length;
					$entities_length = 0;
					// search for html entities
					if( preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE) ){
						// calculate the real length of all entities in the legal range
						foreach($entities[0] as $entity){
							if( $entity[1]+1-$entities_length <= $left ){
								$left--;
								$entities_length += strlen($entity[0]);
							}
							else{
								// no more characters left
								break;
							}
						}
					}
					$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
					// maximum lenght is reached, so get off the loop
					break;
				}
				else{
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				// if the maximum length is reached, get off the loop
				if( $total_length>= $length ){
					break;
				}
			}
		}
		else{
			if( strlen($string) <= $length ){
				return $string;
			}
			else{
				$truncate = substr($string, 0, $length - strlen($ending));
			}
		}
		// if the words shouldn't be cut in the middle...
		if(!$exact){
			// ...search the last occurance of a space...
			$spacepos = strrpos($truncate, ' ');
			if( isset($spacepos) ){
				// ...and cut the text in this position
				$truncate = substr($truncate, 0, $spacepos);
			}
		}
		// add the defined ending to the text
		$truncate .= $ending;
		if($considerHtml){
			// close all unclosed html-tags
			foreach($open_tags as $tag){
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}
	
	
	/**
	* Modifie les antislashes d'un lien local en slashes pour usage web
	*
	* @param string $path_directory -> Le lien à modifier
	* @return string
	*/
	public static function changeSlashDirectory($path_directory){
		if( strstr($path_directory, '\\') ){
			$path = explode('\\', $path_directory);
			$new_path = null;
			$count_path = count($path);
			for($i = 0; $i < $count_path; $i++){
				if($i == $count_path-1){
					$new_path .= $path[$i];
				}else{
					$new_path .= $path[$i].'/';
				}
			}
			return $new_path;
		}else{
			return $path_directory;
		}
	}
	
	
	/**
	* Liste les dossiers et fichiers d'un répertoire
	*
	* @param  string $path                 -> Le chemin du répertoire à lister
	* @param  array  $hidden_folders       -> Les dossiers à masquer : array('dossier1', 'dossier2);
	* @param  array  $hidden_files         -> Les fichiers ou extension à masquer : array('Thumbs.db', 'index.php', 'exe');
	* @param  int    $recent_status_period -> Période pour afficher le statut "récent"
	* @return array ['folders'], ['files']
	*/
	public static function readDirectory($path = '.', $hidden_folders = array(), $hidden_files = array(), $recent_status_period = 86400){
		// Initialisation
		$out = array();
		$heure = date('H');
		$minute = date('i');
		$heure_minuit = time() - ($heure*3600) - ($minute*60);
		
		// Ajout d'un / a la fin, s'il n'y est pas
		if( substr($path, -1, 1) != '/'){ $path = $path.'/'; }
		
		// Si le chemin n'est pas un dossier
		if( ! @is_dir($path) ){
			return false;
		}else{
			// Pour chaque entrée
			$dir = scandir($path);
			foreach($dir as $entry){
				// Si ce n'est pas . et ..
				if($entry != '.' && $entry != '..'){
					$path_to_entry = $path.'/'.$entry;
					
					// Si c'est un dossier
					if( @is_dir($path_to_entry) ){
						// Si le dossier n'est pas parmi les dossiers masqués
						if( !in_array($entry, $hidden_folders) ){
							// Enregistrement du nom et de sa taille
							$out['folders'][$entry]['size'] = self::formatSize(self::dirSize($path_to_entry));
							$out['folders'][$entry]['nb_file'] = self::dirCountFile($path_to_entry, $hidden_files);
						}
					}
					// Si c'est un fichier different des fichiers masqués
					else if( !in_array($entry, $hidden_files) ){
						// Si le fichier est différent d'une extension masquée
						if( !in_array( self::getFilenameExtension($entry), $hidden_files) ){
							// Recupere seulement le timestamp et le poids ici
							$out['files'][$entry] = array_slice(stat($path_to_entry), 20, 3);
							// Formatage de la taille du fichier
							$out['files'][$entry]['size'] = self::formatSize($out['files'][$entry]['size']);
							// Ajout du nom
							$out['files'][$entry]['filename'] = $entry;
							// Ajout de l'extension
							$out['files'][$entry]['extension'] = self::getFilenameExtension($entry);
							// Statut
							if($out['files'][$entry]['mtime'] > ($heure_minuit-$recent_status_period)){ $recent = 1; }
							else{ $recent = 0; }
							$out['files'][$entry]['recent'] = $recent;
						}
					}
				}
			}
			// Si il n'y a aucun dossier ou fichier, on initialise les tableaux par null
			if( empty($out['folders']) ){
				$out['folders'] = null;
			}
			if( empty($out['files']) ){
				$out['files'] = null;
			}
		}
		return $out;
	}
	
	
	/**
	* Compte le nombre de fichier présent dans un dossier
	*
	* @param string $path         -> Le chemin du dossier
	* @param array  $hidden_files -> Les fichiers ou extensions à ne pas prendre en compte
	* @return int
	*/
	public static function dirCountFile($path, $hidden_files = array() ){
		$count = 0;
		// Ajout d'un / a la fin, s'il n'y est pas
		if( substr($path, -1, 1) != '/'){ $path = $path.'/'; }
		
		// Si le dossier existe
		if( file_exists($path) ){
			$dir = scandir($path);
			foreach($dir as $file){
				// Si ce n'est pas un dossier
				if( !is_dir($path.$file) ){
					// Si le fichier n'est pas masqué
					if( !in_array($file, $hidden_files) ){
						// Si l'extension n'est pas masqué
						if( !in_array( self::getFilenameExtension($file), $hidden_files) ){
							$count++;
						}
					}
				}
			}
		}
		return $count;
	}
	
	
	/**
	 * Taille d'un dossier
	 *
	 * @param string $dir -> Le chemin du dossier
	 * @return int
	 */
	public static function dirSize($dir){
		$size = 0;
		if( substr($dir, -1, 1) != '/'){ $dir = $dir.'/'; }
		
		// Si le dossier existe
		if( file_exists($dir) ){
			// Si il y a des fichiers à lire
			if( self::dirCountFile($dir) != 0){
				$pointeur = opendir($dir);
				while( $file = readdir($pointeur) ){
					if( $file != '..' && $file != '.' && !is_dir($dir.'/'.$file) ){
						$size += filesize($dir.'/'.$file);
					}
					else if( is_dir($dir.'/'.$file) && $file != '..' && $file != '.' ){
						$size += self::dirSize($dir.'/'.$file);
					}
				}
			}
		}
		return $size;
	}
	
	
	/**
	* Formatage de la taille
	* 
	* @param int $size -> Taille
	* @return string
	*/
	public static function formatSize($size){
		// unités
		$u = array('octets', 'Ko', 'Mo', 'Go', 'To');
		// compteur de passages dans la boucle
		$i = 0;
		// nombre à afficher
		$m = 0;
		// division par 1024
		while($size >= 1){
			$m = $size;
			$size /= 1024;
			$i++;
		}
		if(!$i){ $i=1; }
		$d = explode('.', $m);
		// s'il y a des décimales
		if($d[0] != $m){
			$m = number_format($m, 1, ',', ' ');
		}
		return $m.' '.$u[$i-1];
	}
	
	
	/**
	* Envoi les headers pour télécharger un fichier
	*
	* @param string $filename -> Le fichier à télécharger
	*/
	public static function sendDownloadHeaders($filename){
		// On protèges les données
		$filename = trim($filename);
		$filename_html = @htmlentities($filename, ENT_QUOTES, "UTF-8");
		
		// On récupère le content-type du fichier
		$content_type = self::getContentType($filename);
		$content_disposition = "attachment";
		if( strpos($filename, '.zip') !== false ){ $content_disposition = "inline"; }
		
		// Headers
		header("Content-Type: ".$content_type);
		header("Expires: ".gmdate("D, d M Y H:i:s") . " GMT");
		
		// IE
		if( preg_match('/MSIE/i', $_SERVER['HTTP_USER_AGENT']) ){
			header("Content-Disposition: $content_disposition; filename=\"" . $filename_html . "\"");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Pragma: public");
		}
		// Firefox & All
		else{
			header("Content-Disposition: $content_disposition; filename*=\"" . $filename_html . "\"");
			header("Pragma: no-cache");
		}
		
		// Final
		header("Content-Description: $filename_html");
		header("Connection: close");
	}
	
	
	/**
	 * Renvoi un Content-type suivant le type et l'extension du fichier
	 *
	 * @param string $filename -> Le fichier
	 * @return string content-type
	 */
	public static function getContentType($filename){
		// Récuperation des infos du fichier
		$fileExt = self::getFilenameExtension($filename);
		$fileType = self::getFileType($filename);
		
		// Content-type
		if($fileExt == 'swf'){ $content_type = "application/x-shockwave-flash"; }
		else if($fileType == 'text'){ $content_type = "text/plain"; }
		else if($fileType == 'image'){
			if($fileExt == 'jpg' || $fileExt == 'jpeg'){ $content_type = "image/jpeg"; }
			else if($fileExt == 'png'){ $content_type = "image/png"; }
			else if($fileExt == 'gif'){ $content_type = "image/gif"; }
		}
		else if($fileType == 'archive'){
			if($fileExt == 'zip'){ $content_type = "application/zip"; }
		}
		else if ($fileType == 'office'){
			if($fileExt = 'doc'){ $content_type = "application/msword"; }
			else if($fileExt = 'xls'){ $content_type = "application/vnd.ms-excel"; }
			else if($fileExt = 'ppt'){ $content_type = "application/vnd.ms-powerpoint"; }
			else if($fileExt = 'mpp'){ $content_type = "application/vnd.ms-project"; }
		}else{
			$content_type = "application/octet-stream";
		}
		
		// Retour
		return $content_type;
	}
	
	
	/**
	 * Retourne un type de fichier suivant l'extension
	 *
	 * @param  string $filename -> Le fichier à extraire le type
	 * @return string           -> Suivant le type de fichier : text, web, image, son, video, exe, office, pdf, archive ou other
	 */
	public static function getFileType($filename){
		// Récuperation de l'extension du fichier
		$last = self::getFilenameExtension($filename);
		// Test
		if(
			$last == 'bas'		||
			$last == 'bat'		||
			$last == 'batch'	||
			$last == 'c'		||
			$last == 'cfg'		||
			$last == 'cfm'		||
			$last == 'cgi'		||
			$last == 'conf'		||
			$last == 'cpp'		||
			$last == 'diz'		||
			$last == 'default'	||
			$last == 'file'		||
			$last == 'h'		||
			$last == 'hpp'		||
			$last == 'htaccess'	||
			$last == 'htpasswd'	||
			$last == 'inc'		||
			$last == 'ini'		||
			$last == 'mak'		||
			$last == 'md'		||
			$last == 'msg'		||
			$last == 'nfo'		||
			$last == 'old'		||
			$last == 'pas'		||
			$last == 'patch'	||
			$last == 'pinerc'	||
			$last == 'pl'		||
			$last == 'pm'		||
			$last == 'qmail'	||
			$last == 'readme'	||
			$last == 'setup'	||
			$last == 'sh'		|| 
			$last == 'tcl'		|| 
			$last == 'tex'		|| 
			$last == 'threads'	|| 
			$last == 'tmpl'		||
			$last == 'tpl'		|| 
			$last == 'txt'		|| 
			$last == 'ubb'		||
			$last == 'vbs' 
			){
			return 'text';
		}
		else if(
			$last == 'asp'		||
			$last == 'css'		||
			$last == 'style'	|| 
			$last == 'dhtml'	||
			$last == 'htm'		||
			$last == 'html'		||
			$last == 'xhtml'	||
			$last == 'phtml'	||
			$last == 'shtml'	|| 
			$last == 'js'		||
			$last == 'jsp'		||
			$last == 'perl'		||
			$last == 'php'		||
			$last == 'php1'		||
			$last == 'php2'		||
			$last == 'php3'		||
			$last == 'php4'		||
			$last == 'php5'		||
			$last == 'php6'		||
			$last == 'php7'		||
			$last == 'phps'		||
			$last == 'sql'		|| 
			$last == 'xml'
			){
			return 'web';
		}
		else if(
			$last == 'png'	|| 
			$last == 'jpg'	|| 
			$last == 'jpeg'	|| 
			$last == 'gif'	||
			$last == 'bmp'	||
			$last == 'dds'	||
			$last == 'tif'	||
			$last == 'tiff'
			){
			return 'image';
		}
		else if(
			$last == 'mp3'	|| 
			$last == 'ogg'	|| 
			$last == 'wav'	|| 
			$last == 'wma'	||
			$last == 'flac'
			){
			return 'son';
		}
		else if(
			$last == 'avi'	|| 
			$last == 'flv'	|| 
			$last == 'mov'	|| 
			$last == 'mpeg'	||
			$last == 'mpg'	||
			$last == 'mp4'	||
			$last == 'wmv'	||
			$last == 'mkv'
			){
			return 'video';
		}
		else if(
			$last == 'exe'	|| 
			$last == 'com'
			){
			return 'exe';
		}
		else if(
			$last == 'doc'	|| 
			$last == 'docx'	|| 
			$last == 'odt'	|| 
			$last == 'rtf'	|| 
			$last == 'xls'	|| 
			$last == 'xlsx'	|| 
			$last == 'ods'	|| 
			$last == 'ppt'	|| 
			$last == 'pptx'	|| 
			$last == 'pps'	|| 
			$last == 'mdb'	|| 
			$last == 'vsd'	|| 
			$last == 'mpp'
			){
			return 'office';
		}
		else if(
			$last == 'pdf'
			){
			return 'pdf';
		}
		else if(
			$last == 'zip'	|| 
			$last == 'tar'	|| 
			$last == 'gz'	|| 
			$last == 'tgz'	|| 
			$last == 'rar'	|| 
			$last == 'arj'	|| 
			$last == 'arc'
			){
			return 'archive';
		}
		else{
			return 'other';
		}
	}
	
	
	/**
	* Créer un fichier zip
	*
	* @require class zip.class.php
	*
	* @param string $filename     -> Le nom du fichier zip
	* @param array  $files_struct -> La structure de l'archive zip
	* $files_struct = array(
	*                   'file.txt',
	*                   'folder' => array('file_in_folder.txt')
	*                 )
	*/
	public static function createZip($filename, $files_struct, $sendHeaders = true){
		// Si la class est instanciée 
		if( class_exists('zipfile') ){
		
			// Si la structure des fichiers est bien un array
			if( is_array($files_struct) ){
			
				// Initialisation
				$zipfile = new zipfile();
				
				// Création de la structure des fichiers/dossiers
				foreach($files_struct as $folder => $file){
					if( is_string($folder) ){
						$zipfile->create_dir($folder);
						foreach($file as $file_array){
							$handle = fopen($file_array, 'rb');
							$buffer = fread($handle, filesize($file_array) );
							fclose($handle);
							$zipfile->create_file($buffer, $folder.'/'.basename($file_array));
						}
					}else{
						$handle = fopen($file, 'rb');
						$buffer = fread($handle, filesize($file) );
						fclose($handle);
						$zipfile->create_file($buffer, basename($file));
					}
					
				}
				
				// Création de l'archive
				$archive = $zipfile->zipped_file();
				$open = fopen($filename, 'wb');
				fwrite($open, $archive);
				fclose($open);
				
				// Si on envoi les headers pour le téléchargement direct
				if($sendHeaders){
					self::sendDownloadHeaders($filename);
					echo $archive;
				}else{
					return $archive;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	
	/**
	* Renvoi le code HTML pour afficher une pagination dynamique
	*
	* @require CSS pagination
	*
	* @param int    $nb_total_items    -> Le nombre total d'items
	* @param int    $nb_items_per_page -> Le nombre d'items par page
	* @param int    $current_page      -> Le numero de la page courante
	* @param string $show_param        -> Le paramètre à afficher
	* @param int    $show_nb_link      -> Le nombre de liens numérotés à afficher dans la pagination
	* @param array  $show_prevnext     -> 1:Afficher les liens "Prev" et "Next", 2:Le texte du lien "Prev", 3:Le texte du lien "Next"
	*/
	public static function displayPagination($nb_total_items, $nb_items_per_page, $current_page, $show_param = 'page', $show_nb_link = 5, $show_prevnext = array(true, 'Prev', 'Next') ){
		// Initialisation
		$nb_total_items = intval($nb_total_items);
		$nb_items_per_page = intval($nb_items_per_page);
		$current_page = intval($current_page);
		$current_pagename = basename($_SERVER['REQUEST_URI']);
		if( strstr($current_pagename, '.') ){
			if( strstr($current_pagename, '?') ){
				$current_page_exp = explode('?', $current_pagename);
				$pagename = $current_page_exp[0];
			}else{
				$pagename = $current_pagename;
			}
		}else{
			$pagename = './';
		}
		if($current_page < 0){ $current_page = 0; }
		$nb_pages_max = ceil($nb_total_items / $nb_items_per_page);
		
		// Affichage HTML
		$out = '<div class="pagination">';
		
			// Prev
			if($show_prevnext[0]){
				// Si on est à la page 0, on grise le lien "prev"
				if($current_page == 0){
					$out .= '<span class="current prev">'.$show_prevnext[1].'</span>';
				}
				// Sinon on enlève -1 à la page en cours
				else{
					$out .= '<a href="'.$pagename.'?'.$show_param.'='.($current_page-1).'" title="">'.$show_prevnext[1].'</a>';
				}
			}
			
			// Index de la 1ère et dernière page pour la liste
			if($nb_pages_max <= $show_nb_link){
				$first_page = 0;
				$last_page = $nb_pages_max;
			}else if($current_page < floor($show_nb_link/2) ){
				$first_page = 0;
				$last_page = $show_nb_link;
			}else if(($current_page > $nb_pages_max - ceil($show_nb_link/2))){
				$first_page = $nb_pages_max - $show_nb_link;
				$last_page = $nb_pages_max;
			}else{
				$first_page = $current_page - floor($show_nb_link/2);
				$last_page = $first_page + $show_nb_link;
			}
			
			// Liste des pages
			for($i = $first_page; $i < $last_page; $i++){
				// Si l'index est sur la page courant, on le selectionne en tant que "courant"
				if($i == $current_page){
					$out .= '<span class="current">'.($i+1).'</span>';
				}
				// Sinon on liste les pages
				else{
					$out .= '<a href="'.$pagename.'?'.$show_param.'='.$i.'" title="">'.($i+1).'</a>';
				}
			}
			
			// Next
			if($show_prevnext[0]){
				// Si on est à la dernière page, on grise le lien "next"
				if( ($current_page == $nb_pages_max-1) || $nb_total_items == 0){
					$out .= '<span class="current next">'.$show_prevnext[2].'</span>';
				}
				// Sinon on ajoute +1 à la page en cours
				else{
					$out .= '<a href="'.$pagename.'?'.$show_param.'='.($current_page+1).'" title="">'.$show_prevnext[2].'</a>';
				}
			}
		$out .= '</div>';
		
		return $out;
	}
}
?>