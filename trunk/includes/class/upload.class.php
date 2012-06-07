<?php
/**
* CLASS UPLOAD
*
* Traite l'envoi de fichier en local ou FTP
*
* @example:
*         $path = '/';
*         $replaceOldFile = false;
*         $allowedExtensions = array('jpg', 'png', 'gif');
*         $sizeLimit = 4 * 1024 * 1024;
*     LOCAL:
*         echo FileUploader::saveUploadedFile($path, $replaceOldFile, $allowedExtensions, $sizeLimit);
*     FTP:
*         echo FileUploader::saveUploadedFileToFTP($ftp_stream, $path, $replaceOldFile, $allowedExtensions, $sizeLimit);
*/



/**
* Permet de traiter le fichier envoyé par XHR (XMLHttpRequest)
*/
class UploadedFileXhr {
	
	/**
	* Enregistre le fichier en local
	*
	* @param string $path -> Le chemin complet du fichier avec le filename
	* @return bool true si réussi
	*/
	public function save($path){
		// Ouverture du flux "input" de PHP et création d'un fichier temp
		$input = fopen("php://input", "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);
		if( $realSize != $this->getSize() ){
			return false;
		}
		
		// Création du fichier final
		$target = fopen($path, "w");
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);
		
		return true;
	}
	
	
	/**
	* Enregistre le fichier sur un serveur FTP
	*
	* @param resource $ftp_stream -> La ressource de connexion FTP
	* @param string   $path       -> Le chemin complet du dossier de destination
	* @param string   $filename   -> Le nom du fichier
	* @return true si réussi, sinon false
	*/
	public function saveFTP($ftp_stream, $path, $filename){
		// Ouverture du flux "input" de PHP et création d'un fichier temp
		$input = fopen("php://input", "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);
		if( $realSize != $this->getSize() ){
			return false;
		}
		
		// Enregistre le fichier sur le FTP
		fseek($temp, 0, SEEK_SET);
		return ftp_fput($ftp_stream, $path.$filename, $temp, FTP_BINARY);
	}
	
	
	/**
	* Enregistre le fichier sur un serveur FTP
	*
	* @param resource $ftp_stream -> La ressource de connexion FTP
	* @param string   $path       -> Le chemin complet du dossier de destination
	* @param string   $filename   -> Le nom du fichier
	* @return true si réussi, sinon false
	*/
	public function saveMap($client, $path, $filename, $queries){
		$out = null;
		// Ouverture du flux "input" de PHP et création d'un fichier temp
		$input = fopen("php://input", "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);
		if( $realSize != $this->getSize() ){
			return false;
		}
		fseek($temp, 0, SEEK_SET);
		$file = stream_get_contents($temp);
		$str64 = new IXR_Base64($file);
		if( !$client->query('WriteFile', $filename, $str64) ){
			$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
		}
		else{
			// Insert
			if($queries['type'] == 'insert'){
				if( !$client->query($queries['insert'], $path.$filename) ){
					$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
				}
			}
			// Add
			else{
				if( !$client->query($queries['add'], $path.$filename) ){
					$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
				}
			}
		}
		
		if($out === null){
			$out = true;
		}
		return $out;
	}
	
	
	/**
	* Récupère le nom du fichier
	*/
	public function getName(){
		return $_GET['qqfile'];
	}
	
	
	/**
	* Récupère la taille du fichier
	*/
	public function getSize(){
		if( isset($_SERVER['CONTENT_LENGTH']) ){
			return (int)$_SERVER['CONTENT_LENGTH'];            
		}else{
			throw new Exception('Getting content length is not supported.');
		}
	}
}



/**
* Permet de traiter le fichier envoyé par Formulaire HTML ($_FILES array)
*/
class UploadedFileForm {
	
	/**
	* Enregistre le fichier en local
	*
	* @param string $path -> Le chemin complet du fichier avec le filename
	* @return bool true si réussi, sinon false
	*/
	public function save($path){
		// Si il n'a pas réussi à déplacer le fichier envoyé
		if( !move_uploaded_file($_FILES['qqfile']['tmp_name'], $path) ){
			return false;
		}
		return true;
	}
	
	
	/**
	* Enregistre le fichier sur un serveur FTP
	*
	* @param resource $ftp_stream -> La ressource de connexion FTP
	* @param string   $path       -> Le chemin complet du dossier de destination
	* @param string   $filename   -> Le nom du fichier
	* @return true si réussi, sinon false
	*/
	public function saveFTP($ftp_stream, $path, $filename){
		// Enregistre le fichier sur le FTP
		return ftp_put($ftp_stream, $path.$filename, $_FILES['qqfile']['tmp_name'], FTP_BINARY);
	}
	
	
	public function writeMap($client, $path, $filename, $queries){
		$out = true;
		$str = file_get_contents($_FILES['qqfile']['tmp_name']);
		$str64 = new IXR_Base64($str);
		if( !$client->query('WriteFile', $path.$filename, $str64) ){
			$out = false;
		}
		else{
			self::saveMap($client, $path, $filename, $queries);
		}
		
		return $out;
	}
	
	
	/**
	* Enregistre le fichier sur un serveur FTP
	*
	* @param resource $ftp_stream -> La ressource de connexion FTP
	* @param string   $path       -> Le chemin complet du dossier de destination
	* @param string   $filename   -> Le nom du fichier
	* @return true si réussi, sinon false
	*/
	public function saveMap($client, $path, $filename, $queries){
		$out = true;
		$pathTofile = $path.$filename;
		// Insert
		if($queries['type'] == 'insert'){
			if( !$client->query($queries['insert'], $filename) ){
				$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
			}
		}
		// Add
		else{
			if( !$client->query($queries['add'], $filename) ){
				$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
			}
		}
		
		return $out;
	}
	
	
	/**
	* Récupère le nom du fichier
	*/
	public function getName(){
		return $_FILES['qqfile']['name'];
	}
	
	
	/**
	* Récupère la taille du fichier
	*/
	public function getSize(){
		return $_FILES['qqfile']['size'];
	}
}



/**
* Permet de gérer l'enregistrement du fichier uploadé par XHR ou Formulaire
*/
class FileUploader {
	private $allowedExtensions = array();
	private $sizeLimit = 10485760;
	private $file;
	
	function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){   
		// Ecrase les paramètres dans les variables de config
		$allowedExtensions = array_map("strtolower", $allowedExtensions);
		$this->allowedExtensions = $allowedExtensions;
		$this->sizeLimit = $sizeLimit;
		
		// Test si le serveur peut accepter la config
		$this->_checkServerSettings();       
		
		// Test si il y a bien un fichier, si oui, on selectionne le type d'enregistrement (XHR ou Formulaire)
		if( isset($_GET['qqfile']) ){
			$this->file = new uploadedFileXhr();
		}
		else if( isset($_FILES['qqfile']) ){
			$this->file = new uploadedFileForm();
		}
		else{
			$this->file = false; 
		}
	}
	
	
	/**
	* Vérifie si le serveur peut accepter la taille max configurée
	*/
	private function _checkServerSettings(){
		$postSize = $this->_toBytes( ini_get('post_max_size') );
		$uploadSize = $this->_toBytes( ini_get('upload_max_filesize') );
		
		if( $postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit ){
			$size = max(1, $this->sizeLimit / 1024 / 1024) . 'Mo';
			die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
		}
	}
	
	
	/**
	* Convertie les Go, Mo ou Ko du php.ini en octets
	*/
	private function _toBytes($str){
		$val = trim($str);
		$last = strtolower($str[strlen($str)-1]);
		switch($last){
			case 'g': $val *= 1024;
			case 'm': $val *= 1024;
			case 'k': $val *= 1024;        
		}
		return $val;
	}
	
	/**
	* Enregistre le fichier envoyé dans un dossier
	*
	* @param string   $uploadDirectory  -> Le chemin du dossier de destination
	* @param bool     $replaceOldFile   -> Remplacement des anciens fichiers qui ont le même nom ? Non par défaut
	* @param function $filenameFunction -> La fonction de traitement du filename
	* @return array('success' => true) ou array('error' => 'error message')
	*/
	public function handleUpload($uploadDirectory, $replaceOldFile = false, $filenameFunction = null){
		// Si on peut écrire dans le dossier de destination
		if( !is_writable($uploadDirectory) ){
			return array('error' => 'Erreur du serveur. Le dossier de destination des uploads n\'est pas écrivable.');
		}
		
		// Si il y a bien un fichier envoyé
		if( !$this->file ){
			return array('error' => 'Aucun fichier n\'a été uploadé.');
		}
		
		// Récuperation de la taille du fichier et test si il n'est pas vide ou supérieur à la taille configurée
		$size = $this->file->getSize();
		if($size === 0){
			return array('error' => 'Le fichier est vide.');
		}
		if($size > $this->sizeLimit){
			return array('error' => 'La taille du fichier est supérieur à la limite autorisé.');
		}
		
		// Récuperation du nom et de l'extension du fichier pour tester si l'extension est valide et si le nom du fichier n'existe pas déjà
		$pathinfo = pathinfo( $this->file->getName() );
		if($filenameFunction != null){
			$filename = $filenameFunction($pathinfo['filename']);
		}else{
			$filename = $pathinfo['filename'];
		}
		$ext = $pathinfo['extension'];
		if( $this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions) ){
			$these = implode(', ', $this->allowedExtensions);
			return array('error' => 'L\'extension du fichier est invalide (extension autorisées : '.$these.').');
		}
		if( !$replaceOldFile ){
			// Pour chaque fichier, on test si il existe, si oui, on rajoute un nombre aléatoire de 10 à 99
			while( file_exists($uploadDirectory . $filename . '.' . $ext) ){
				$filename .= rand(10, 99);
			}
		}
		
		// Enregistrement du fichier
		if( $this->file->save($uploadDirectory . $filename . '.' . $ext) ){
			return array('success' => true);
		}else{
			return array('error' => 'Le fichier n\'a pas été envoyé. L\'envoi a été annulé ou une erreur du serveur est survenue.');
		}
	}
	
	
	/**
	* Enregistre le fichier envoyé dans un dossier sur un serveur FTP
	*
	* @param resource $ftp_stream       -> La ressource de connexion FTP
	* @param string   $uploadDirectory  -> Le chemin du dossier de destination
	* @param bool     $replaceOldFile   -> Remplacement des anciens fichiers qui ont le même nom ? Non par défaut
	* @param function $filenameFunction -> La fonction de traitement du filename
	* @return array('success' => true) ou array('error' => 'error message')
	*/
	public function handleUploadFTP($ftp_stream, $uploadDirectory, $replaceOldFile = false, $filenameFunction = null){
		// Si on peut écrire dans le dossier de destination
		if( ! @ftp_chdir($ftp_stream, $uploadDirectory) ){
			return array('error' => 'Erreur du serveur. Le dossier de destination des uploads n\'est pas écrivable.');
		}
		
		// Si il y a bien un fichier envoyé
		if( !$this->file ){
			return array('error' => 'Aucun fichier n\'a été uploadé.');
		}
		
		// Récuperation de la taille du fichier et test si il n'est pas vide ou supérieur à la taille configurée
		$size = $this->file->getSize();
		if($size === 0){
			return array('error' => 'Le fichier est vide.');
		}
		if($size > $this->sizeLimit){
			return array('error' => 'La taille du fichier est supérieur à la limite autorisé.');
		}
		
		// Récuperation du nom et de l'extension du fichier pour tester si l'extension est valide et si le nom du fichier n'existe pas déjà
		$pathinfo = pathinfo( $this->file->getName() );
		if($filenameFunction != null){
			$filename = $filenameFunction($pathinfo['filename']);
		}else{
			$filename = $pathinfo['filename'];
		}
		$ext = $pathinfo['extension'];
		if( $this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions) ){
			$these = implode(', ', $this->allowedExtensions);
			return array('error' => 'L\'extension du fichier est invalide (extension autorisées : '.$these.').');
		}
		
		// Si il ne faut pas remplacer les anciens fichiers
		if( !$replaceOldFile ){
			// Fonction pour vérifier si le fichier existe sur le FTP
			function ftp_file_exists($ftp_stream, $uploadDirectory, $filename){
				$files = ftp_nlist($ftp_stream, $uploadDirectory);
				if( count($files) > 0){
					foreach($files as $file){
						if($uploadDirectory.$filename == $file){
							return true;
						}
					}
				}
				return false;
			}
			// Pour chaque fichier, on test si il existe, si oui, on rajoute un nombre aléatoire de 10 à 99
			while( ftp_file_exists($ftp_stream, $uploadDirectory, $filename.'.'.$ext) ){
				$filename .= rand(10, 99);
			}
		}
		
		// Enregistrement du fichier
		if( $this->file->saveFTP($ftp_stream, $uploadDirectory, $filename.'.'.$ext) ){
			return array('success' => true);
		}else{
			return array('error' => 'Le fichier n\'a pas été envoyé. L\'envoi a été annulé ou une erreur du serveur est survenue.');
		}
	}
	
	
	/**
	* Enregistre le fichier envoyé dans un dossier sur un serveur FTP
	*
	* @param resource $client           -> La ressource du client XMLRPC
	* @param string   $uploadDirectory  -> Le chemin du dossier de destination
	* @param bool     $replaceOldFile   -> Remplacement des anciens fichiers qui ont le même nom ? Non par défaut
	* @param function $filenameFunction -> La fonction de traitement du filename
	* @return array('success' => true) ou array('error' => 'error message')
	*/
	public function handleUploadManiaPlanet($client, $uploadDirectory, $queries, $filenameFunction = null){
		// Si le client est initialisé
		if (!$client->socket || $client->protocol == 0) {
			return array('error' => 'Client not initialized.');
		}
		
		// Si il y a bien un fichier envoyé
		if( !$this->file ){
			return array('error' => 'Aucun fichier n\'a été uploadé.');
		}
		
		// Récuperation de la taille du fichier et test si il n'est pas vide ou supérieur à la taille configurée
		$size = $this->file->getSize();
		if($size === 0){
			return array('error' => 'Le fichier est vide.');
		}
		if($size > $this->sizeLimit){
			return array('error' => 'La taille du fichier est supérieur à la limite autorisé.');
		}
		
		// Récuperation du nom et de l'extension du fichier pour tester si l'extension est valide et si le nom du fichier n'existe pas déjà
		$pathinfo = pathinfo( $this->file->getName() );
		if($filenameFunction != null){
			$filename = $filenameFunction($pathinfo['filename']);
		}else{
			$filename = $pathinfo['filename'];
		}
		$ext = $pathinfo['extension'];
		if( $this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions) ){
			$these = implode(', ', $this->allowedExtensions);
			return array('error' => 'L\'extension du fichier est invalide (extension autorisées : '.$these.').');
		}
		
		// Enregistrement du fichier
		if( $out = $this->file->saveMap($client, $uploadDirectory, $filename.'.'.$ext, $queries) ){
			return array('success' => true, 'out' => $out);
		}else{
			return array('error' => 'Le fichier n\'a pas été envoyé. L\'envoi a été annulé ou une erreur du serveur est survenue. ('.$out.')');
		}
	}
	
	
	/**
	* Enregistre le fichier envoyé en local
	*
	* @param string   $uploadDirectory   -> Le chemin du dossier de destination
	* @param bool     $replaceOldFile    -> Remplacement des anciens fichiers qui ont le même nom ? Non par défaut
	* @param array    $allowedExtensions -> Les extensions autorisées : array('jpg', 'png', 'gif');
	* @param int      $sizeLimit         -> La taille limite d'envoi (égale ou inférieure à la configuration de PHP)
	* @param function $filenameFunction  -> La fonction de traitement du filename
	* @return array('success' => true) ou array('error' => 'error message')
	*/
	public static function saveUploadedFile($uploadDirectory, $replaceOldFile = false, $allowedExtensions = array(), $sizeLimit = 10485760, $filenameFunction = null){
		// Initialisation de la classe et enregistrement du fichier
		$uploader = new FileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($uploadDirectory, $replaceOldFile, $filenameFunction);
		
		// Retourne le résultat en json
		return htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
	
	
	/**
	* Enregistre le fichier envoyé sur un serveur FTP
	*
	* @param resource $ftp_stream        -> La ressource de connexion FTP
	* @param string   $uploadDirectory   -> Le chemin du dossier de destination
	* @param bool     $replaceOldFile    -> Remplacement des anciens fichiers qui ont le même nom ? Non par défaut
	* @param array    $allowedExtensions -> Les extensions autorisées : array('jpg', 'png', 'gif');
	* @param int      $sizeLimit         -> La taille limite d'envoi (égale ou inférieure à la configuration de PHP)
	* @param function $filenameFunction  -> La fonction de traitement du filename
	* @return array('success' => true) ou array('error' => 'error message')
	*/
	public static function saveUploadedFileToFTP($ftp_stream, $uploadDirectory, $replaceOldFile = false, $allowedExtensions = array(), $sizeLimit = 10485760, $filenameFunction = null){
		// Initialisation de la classe et enregistrement du fichier sur un serveur FTP
		$uploader = new FileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUploadFTP($ftp_stream, $uploadDirectory, $replaceOldFile, $filenameFunction);
		
		// Retourne le résultat en json
		return htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
	
	
	/**
	* Enregistre le fichier envoyé sur un serveur dédié Maniaplanet
	*
	* @param resource $client            -> La ressource du client XMLRPC
	* @param string   $uploadDirectory   -> Le chemin du dossier de destination
	* @param array    $queries           -> Requêtes à executer et le type d'ajout à la liste: array('insert' => 'InsertMap', 'add' => 'AddMap', 'type' => 'add')
	* @param array    $allowedExtensions -> Les extensions autorisées : array('jpg', 'png', 'gif');
	* @param int      $sizeLimit         -> La taille limite d'envoi (égale ou inférieure à la configuration de PHP)
	* @param function $filenameFunction  -> La fonction de traitement du filename
	* @return array('success' => true) ou array('error' => 'error message')
	*/
	public static function saveUploadedFileToManiaPlanetServer($client, $uploadDirectory, $queries, $allowedExtensions = array(), $sizeLimit = 10485760, $filenameFunction = null){
		// Initialisation de la classe et enregistrement du fichier sur un serveur dédié Maniaplanet
		$uploader = new FileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUploadManiaPlanet($client, $uploadDirectory, $queries, $filenameFunction);
		
		// Retourne le résultat en json
		return htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
}