<?php
/**
* Class Archive
*
* Méthodes de traitement pour les archives
*/
class Archive {
	
	public $filename;
	
	public function _construct(){
		$out = null;
		if( class_exists('ZipArchive') ){
			$out = true;
		}
		else{
			$out = 'Class ZipArchive no exists';
		}
		
		return $out;
	}
	
	public static function create($filename, $data){
		$out = null;
		$za = new ZipArchive();
		if( File::getExtension($filename) != 'zip'){
			$this->filename = $filename.'.zip';
		}else{
			$this->filename = $filename;
		}
		
		if( !$result = $za->open($this->filename, ZIPARCHIVE::CREATE) ){
			switch($result){
				case ZIPARCHIVE::ER_EXISTS:
					$out = 'Le fichier existe déjà.';
					break;
				case ZIPARCHIVE::ER_INCONS:
					$out = 'L\'archive ZIP est inconsistante. ';
					break;
				case ZIPARCHIVE::ER_INVAL:
					$out = 'Argument invalide.';
					break;
				case ZIPARCHIVE::ER_MEMORY:
					$out = 'Erreur de mémoire.';
					break;
				case ZIPARCHIVE::ER_NOENT:
					$out = 'Le fichier n\'existe pas.';
					break;
				case ZIPARCHIVE::ER_NOZIP:
					$out = 'N\'est pas une archive ZIP.';
					break;
				case ZIPARCHIVE::ER_OPEN:
					$out = 'Impossible d\'ouvrir le fichier.';
					break;
				case ZIPARCHIVE::ER_READ:
					$out = 'Erreur lors de la lecture.';
					break;
				case ZIPARCHIVE::ER_SEEK:
					$out = 'Erreur de position.';
					break;
			}
		}
		
		return $out;
	}
	
	public static function addFiles($files){
		
	}
	
	public static function addFolders($folders){
		
	}
}
?>