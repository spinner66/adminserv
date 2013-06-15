<?php

/**
* Classe pour la gestion du cache
*/
class AdminServCache {
	
	/**
	* Enregistre la valeur dans un fichier
	*
	* @param string $name  -> Nom du cache
	* @param array  $value -> Valeur à enregistrer
	* @return bool
	*/
	public static function set($name, $value){
		$out = false;
		$file = AdminServConfig::$PATH_RESOURCES.'cache/' . $name . '.json';
		$data = json_encode($value);
		
		if( file_exists($file) ){
			if( File::save($file, $data) ){
				$out = true;
			}
		}
		else{
			if( File::save($file) ){
				self::set($name, $value);
			}
		}
		
		self::getErrorMsg('set');
		
		return $out;
	}
	
	
	/**
	* Récupère la valeur depuis un fichier
	*
	* @param string $name -> Nom du cache à récupérer
	* @return array()
	*/
	public static function get($name){
		$out = array();
		$file = AdminServConfig::$PATH_RESOURCES.'cache/' . $name . '.json';
		
		if( file_exists($file) ){
			$data = file_get_contents($file);
			$out = json_decode($data, true);
		}
		else{
			if( File::save($file) ){
				self::get($name);
			}
		}
		
		self::getErrorMsg('get');
		
		return $out;
	}
	
	
	/**
	* Récupère le message d'erreur lors de l'encodage/décodage du JSON
	*/
	public static function getErrorMsg($type){
		switch( json_last_error() ){
			case JSON_ERROR_DEPTH:
				AdminServ::error('JSON ('.$type.') - Profondeur maximale atteinte');
				break;
			case JSON_ERROR_STATE_MISMATCH:
				AdminServ::error('JSON ('.$type.') - Inadéquation des modes ou underflow');
				break;
			case JSON_ERROR_CTRL_CHAR:
				AdminServ::error('JSON ('.$type.') - Erreur lors du contrôle des caractères');
				break;
			case JSON_ERROR_SYNTAX:
				AdminServ::error('JSON ('.$type.') - Erreur de syntaxe ; JSON malformé');
				break;
			case JSON_ERROR_UTF8:
				AdminServ::error('JSON ('.$type.') - Caractères UTF-8 malformés, probablement une erreur d\'encodage');
				break;
		}
	}
}
?>