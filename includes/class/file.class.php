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
	* Créer ou ajoute des données à un fichier
	*
	* @param  string $filename -> Le chemin ou nom du fichier
	* @param  string $data     -> Données à écrire
	* @return true si réussi, sinon erreur string
	*/
	public static function save($filename, $data = null){
		$out = null;
		
		if( file_exists($filename) ){
			if( $handle = fopen($filename, 'w') ){
				if($data){
					if( fwrite($handle, $data) ){
						$out = true;
					}
					else{
						$out = 'Impossible d\'écrire les données dans le fichier.';
					}
				}
				else{
					$out = true;
				}
				fclose($handle);
			}
			else{
				$out = 'Impossible d\'ouvrir le fichier.';
			}
		}
		else{
			$out = 'Le fichier n\'existe pas.';
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
				$out = 'Impossible de renommer le fichier.';
			}
		}
		else{
			$out = 'Le fichier n\'existe pas.';
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
				$out = 'Impossible de supprimer le fichier.';
			}
		}
		else{
			$out = 'Le fichier n\'existe pas.';
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
	}
}
?>