<?php
/**
* Class File
*
* Méthodes de traitement de fichier
*/
abstract class File {
	
	/**
	* Récupère l'extension d'un fichier
	*
	* @param  string $filename -> Le chemin ou nom du fichier
	* @return string "php"
	*/
	public static function getExtension($filename){
		$out = null;
		
		if( strstr($filename, '.') ){
			$pathinfo = pathinfo($filename);
			$out = strtolower($pathinfo['extension']);
		}
		
		return $out;
	}
	
	
	/**
	* Récupère l'extension d'un fichier Nadeo
	*
	* @param string $filename -> Le chemin ou nom du fichier
	* @return string "class.php"
	*/
	public static function getDoubleExtension($filename){
		$out = null;
		$filenameEx = explode('.', $filename);
		
		if( count($filenameEx) > 2 ){
			$ext = $filenameEx[count($filenameEx)-2];
			$ext .= '.'.$filenameEx[count($filenameEx)-1];
			$out = strtolower($ext);
		}
		
		return $out;
	}
	
	
	/**
	* Créer ou ajoute des données à un fichier
	*
	* @param  string $filename -> Le chemin ou nom du fichier
	* @param  string $data     -> Données à écrire
	* @return true si réussi, sinon erreur string
	*/
	public static function save($filename, $data = null, $appendData = true){
		$out = null;
		
		if( file_exists($filename) ){
			if($appendData){ $append = FILE_APPEND; }else{ $append = 0; }
			if( file_put_contents($filename, $data, $append) ){
				$out = true;
			}
			else{
				$out = 'No such file or file is not writable';
			}
		}
		else{
			if( $handle = fopen($filename, 'w') ){
				$out = true;
				fclose($handle);
			}
			else{
				$out = 'No such file or file is not writable';
			}
		}
		
		return $out;
	}
	
	
	/**
	* Enregistre les données dans un fichier au pointeur
	*
	* @param  string $filename -> Le chemin ou nom du fichier
	* @param  string $data     -> Données à écrire
	* @param  string $seek     -> Le pointeur pour l'endroit à écrire
	*/
	public static function saveAtSeek($filename, $data, $seek = 0){
		$out = null;
		
		if( file_exists($filename) && is_writable($filename) ){
			$handle = fopen($filename, 'r+');
			fseek($handle, $seek, SEEK_CUR);
			if( fwrite($handle, $data) ){
				$out = true;
			}
			fclose($handle);
		}
		else{
			$out = 'No such file or file is not writable';
		}
		
		return $out;
	}
	
	
	/**
	* Renomme un fichier
	*
	* @param string $filename    -> Chemin du fichier à renommer
	* @param string $newfilename -> Chemin du nouveau fichier renommé
	*/
	public static function rename($filename, $newfilename){
		$out = null;
		
		if( file_exists($filename) ){
			if( @rename($filename, $newfilename) ){
				$out = true;
			}
			else{
				$out = 'Unable to rename file';
			}
		}
		else{
			$out = 'No such file';
		}
		
		return $out;
	}
	
	
	/**
	* Supprime un fichier
	*
	* @param string $filename -> Chemin du fichier à supprimer
	*/
	public static function delete($filename){
		$out = null;
		
		if( file_exists($filename) ){
			if( @unlink($filename) ){
				$out = true;
			}
			else{
				$out = 'Unable to delete file';
			}
		}
		else{
			$out = 'No such file';
		}
		
		return $out;
	}
	
	
	/**
	* Envoi les headers pour télécharger un fichier
	*
	* @param string $pathToFile -> Le chemin du fichier à télécharger
	* @param int    $fileSize   -> Taille du fichier, si null = automatique
	*/
	public static function sendDownloadHeaders($pathToFile, $fileSize = null){
		$path_parts = pathinfo($pathToFile);
		$filename = htmlspecialchars( trim($path_parts['basename']), ENT_QUOTES, 'UTF-8');
		$path = $path_parts['dirname'].'/';
		
		// Headers
		header('Content-Disposition: attachment; filename="'.$filename);
		header('Content-Type: application/force-download');
		header('Content-Transfer-Encoding: binary');
		if($fileSize != null){
			header('Content-Length: '.$fileSize);
		}
		else{
			header('Content-Length: '.filesize($pathToFile));
		}
		header('Pragma: no-cache');
		if( preg_match('/MSIE/i', $_SERVER['HTTP_USER_AGENT']) ){
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		}
		else{
			header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		}
		header('Expires: 0');
	}
	
	
	/**
	* Permet de télécharger un fichier
	*
	* @param string $pathToFile -> Le chemin du fichier à télécharger
	* @param int    $fileSize   -> Taille du fichier, si null = automatique
	*/
	public static function download($pathToFile, $fileSize = null){
		self::sendDownloadHeaders($pathToFile, $fileSize);
		flush();
		readfile($pathToFile);
	}
}
?>