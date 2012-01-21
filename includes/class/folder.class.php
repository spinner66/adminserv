<?php
/**
* Class Folder
*
* Méthodes de traitement de dossier
*/
abstract class Folder {
	
	/**
	* Liste les dossiers et fichiers d'un répertoire
	*
	* @param  string $path                 -> Le chemin du répertoire à lister
	* @param  array  $hidden_folders       -> Les dossiers à masquer : array('dossier1', 'dossier2);
	* @param  array  $hidden_files         -> Les fichiers ou extension à masquer : array('Thumbs.db', 'index.php', 'exe');
	* @param  int    $recent_status_period -> Période pour afficher le statut "récent"
	* @return array ['folders'], ['files']
	*/
	public static function read($path = '.', $hiddenFolders = array(), $hiddenFiles = array(), $recentStatusPeriod = 86400){
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
					$pathToEntry = $path.'/'.$entry;
					
					// Si c'est un dossier
					if( @is_dir($pathToEntry) ){
						// Si le dossier n'est pas parmi les dossiers masqués
						if( !in_array($entry, $hiddenFolders) ){
							// Enregistrement du nom et de sa taille
							$out['folders'][$entry]['size'] = self::formatSize(self::dirSize($pathToEntry));
							$out['folders'][$entry]['nb_file'] = self::dirCountFile($pathToEntry, $hiddenFiles);
						}
					}
					// Si c'est un fichier different des fichiers masqués
					else if( !in_array($entry, $hiddenFiles) ){
						// Si le fichier est différent d'une extension masquée
						if( !in_array( self::getFilenameExtension($entry), $hiddenFiles) ){
							// Recupere seulement le timestamp et le poids ici
							$out['files'][$entry] = array_slice(stat($pathToEntry), 20, 3);
							// Formatage de la taille du fichier
							$out['files'][$entry]['size'] = self::formatSize($out['files'][$entry]['size']);
							// Ajout du nom
							$out['files'][$entry]['filename'] = $entry;
							// Ajout de l'extension
							$out['files'][$entry]['extension'] = self::getFilenameExtension($entry);
							// Statut
							if($out['files'][$entry]['mtime'] > ($heure_minuit - $recentStatusPeriod)){ $recent = 1; }else{ $recent = 0; }
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
}
?>