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
	
	
}
?>