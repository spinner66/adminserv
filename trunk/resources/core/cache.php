<?php

/**
* Classe pour la gestion du cache
*/
class AdminServCache {
	private $folder;
	
	/**
	* Initialisation du cache
	*/
	function __construct(){
		$this->folder = AdminServConfig::$PATH_RESOURCES.'cache/';
		
		if( !file_exists($this->folder) ){
			Folder::create($this->folder);
		}
	}
	
	
	/**
	* Enregistre la valeur dans un fichier
	*
	* @param string $key   -> Clef du cache
	* @param array  $value -> Valeur à enregistrer
	* @return bool
	*/
	public function set($key, $value){
		$out = false;
		$file = $this->folder . $key . '.json';
		$data = json_encode($value);
		
		if( file_exists($file) ){
			if( File::save($file, $data) ){
				$out = true;
			}
		}
		else{
			if( File::save($file) ){
				self::set($key, $value);
			}
		}
		
		self::getErrorMsg('set');
		
		return $out;
	}
	
	
	/**
	* Récupère la valeur depuis un fichier
	*
	* @param string $key -> Clef du cache à récupérer
	* @return array()
	*/
	public function get($key){
		$out = array();
		$file = $this->folder . $key . '.json';
		
		if( file_exists($file) ){
			$data = file_get_contents($file);
			$out = json_decode($data, true);
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