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
	* @return string
	*/
	public static function getExtension($filename){
		$pathinfo = pathinfo($filename);
		return strtolower($pathinfo['extension']);
	}
	
	
	/**
	* Envoi les headers pour télécharger un fichier
	*
	* @param string $pathToFile -> Le chemin du fichier à télécharger
	* @param int    $fileSize   -> Taille du fichier, si null = automatique
	*/
	public static function sendDownloadHeaders($pathToFile, $fileSize = null){
		// On protèges les données
		$path_parts = pathinfo($pathToFile);
		$filename = htmlspecialchars( trim($path_parts['basename']), ENT_QUOTES, "UTF-8");
		$path = $path_parts['dirname'].'/';
		
		// Headers
		header('Content-Disposition: attachment; filename="'.$filename);
		header('Content-Type: application/force-download');
		header('Content-Transfer-Encoding: binary');
		if($fileSize != null){
			header('Content-Length: '.$fileSize);
		}else{
			header('Content-Length: '.filesize($pathToFile));
		}
		header('Pragma: no-cache');
		if( preg_match('/MSIE/i', $_SERVER['HTTP_USER_AGENT']) ){
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		}else{
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
		exit;
	}
}
?>