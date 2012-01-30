<?php
/**
* Class Str
*
* Méthodes de traitement de chaines de caractères
*/
abstract class Str {
	
	/**
	* Formatage de la taille
	* 
	* @param int $size -> Taille
	* @return string
	*/
	public static function formatSize($size){
		// unités
		$u = array('octets', 'Ko', 'Mo', 'Go', 'To');
		$i = 0;
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
	* Modifie les antislashes d'un lien local en slashes pour usage web
	*
	* @param string $path -> Le chemin à modifier
	* @return string
	*/
	public static function toSlash($path){
		$out = null;
		
		if( strstr($path, '\\') ){
			$newPath = explode('\\', $path);
			foreach($newPath as $part){
				$out .= $part.'/';
			}
			$out = substr($out, 0, -1);
		}
		else{
			$out = $path;
		}
		
		return $out;
	}
}
?>