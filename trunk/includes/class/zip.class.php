<?php
/**
* Class Archive
*
* Méthodes de traitement pour les archives
*/
class Zip extends ZipArchive {
	
	public function create($filename, $data){
		$out = null;
		$zip = new ZipArchive();
		
		if( $result = $zip->open($filename, ZIPARCHIVE::CREATE) ){
			
			self::_checkStructure($filename, $data);
			
			$zip->close();
		}
		else{
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
	
	private function _checkStructure($filename, $struct){
		$zip = new ZipArchive();
		
		if( $zip->open($filename) ){
			foreach($struct as $folder => $file){
				
				if( is_array($folder) ){
					$zip->addEmptyDir($folder);
					self::_checkStructure($struct);
				}
				else{
					$zip->addFile($file);
				}
			}
			$zip->close();
		}
	}
}
?>