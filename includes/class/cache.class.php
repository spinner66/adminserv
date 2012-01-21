<?php 
/**
* Classe cache
* classe de gestion de cache
* 
* Exemple d'utilisation :
* <?php
* include('path/cache.class.php');
* try{
*	$cache=new cache($cache_name);
*	$cache->initCache(3600);
* } catch (Exeption $e){
*	echo $e->getMessage();
* }
* 
* 
* //script de la page
*
*
* ?>
*
*
* @author Joris Mulliez
* @package Cache
*/ 
class Cache {
	/**
	* Extension des fichiers
	*/
	const EXTENSION = '.cache.html';
	
	/**
	* Dossier où l'on stocke les fichiers de cache
	*/
	private $dossier = './cache/';
	
	/**
	*  Variable contenant le nom du fichier
	*/
	private $file = null;
	
	
	/**
	* Initialise la variable $this->file et calcul le realpath de $this->dossier
	* 
	* @param  string $file       -> Nom du fichier de cache
	* @param  bool   $cache_date -> Ajouter ou non la date en fin de fichier cache
	* @return none  
	*/
	public function __construct($file = null, $path = null){
		if( empty($file) ){
			$this->file = $this->_clearUri($_SERVER['REQUEST_URI']);
		}else{
			$this->file = $this->_clearUri($file);
		}
		
		if( !empty($path) && is_dir($path) ){
			$this->dossier = realpath($path);
		}else{
			throw new Exception('Le dossier '.$path.' n\'existe pas.');
		}
	}
	
	
	/**
	* Si le fichier de cache correspondant à $this->file existe et n'est pas périmé on l'inclu, et on arrête tout
	* sinon on démarre la temporisation...
	*
	* @param  mixed  $time -> Temps de validité du fichier de cache
	* @param  string $mod  -> Type de comparaison de temps
	* @return none  
	*/
	public function initCache($time = 0){
		$path = $this->dossier .'/'. $this->file .self::EXTENSION;
		if( file_exists($path) ){
			if( is_numeric($time) && $time >= 0 ){
				if( $time == 0 || (time() - filemtime($path)) < $time ){
					readfile($path);
					exit();
				}
				else{
					ob_start(array($this, 'ob_end'));
				}
			}
			else if($time == 'ONEDAY'){
				if( date('Ymd', filemtime($path)) == date('Ymd') ){
					readfile($path);
					exit();
				}
				else{
					ob_start(array($this, 'ob_end'));
				}
			}
		}
		else{
			ob_start(array($this, 'ob_end'));
		}
	}
	
	
	/**
	* Supprime le contenu du dossier $dir
	*
	* @param  string $dir -> Nom du dossier de cache
	* @return none
	*/
	static function clearCache($dir){
		if( is_dir($dir) ){
			$d = dir($dir);
			while( false !== ($entry = $d->read()) ){
				if( $entry !== '..' && $entry !== '.' ){
					if( !unlink($dir.$entry) ){
						throw new Exception('Le fichier suivant '.$dir.$entry.' n\'a pas pu être supprimé.');
					}
				}
			}
		}
		else{
			throw new Exception('Le dossier '.$dir.' n\'existe pas.');
		}
	}
	
	
	/**
	* Supprime le fichier de cache, si le caractère * est trouvé dans l'url,
	* fonctionnera comme un masque, exemple photos_*.html supprimera tout les
	* fichiers photos_1.html,photos_2.html, etc...
	*
	* @param  string $file -> Nom du fichier de cache
	* @return none  
	*/
	public function clearFileCache($file){
		if( strpos($file,'*') !== false ){
			$files = glob($this->dossier .'/'.$file.self::EXTENSION);
		}
		else{
			$files = array($this->dossier .'/'.$file.self::EXTENSION);
		}
		foreach($files as $file){
			if( file_exists($file) ){
				if( !unlink($file) ){
					throw new Exception('Le fichier suivant '.$file.' n\'a pas pu être supprimé.');
				}
			}
		}
	}
	
	
	/**
	* Fonction appellée à la fin de la bufferisation
	*
	* @param   string    $content     contenu du buffer
	* @return  string    $content
	*/
	public function ob_end($content){
		$file = $this->dossier .'/'. $this->file .self::EXTENSION;
		if( !file_put_contents($file,$content.'<!-- fichier de cache généré le '.date("d/m/Y \à H:i:s").' -->') ){
			throw new Exception('Le fichier suivant '. $this->dossier .'/'.$this->file . self::EXTENSION .' n\'a pas pu être créé ou n\'est pas ouvert à l\'écriture.');
		}
		return $content;
	}
	
	
	/**
	* Réduit la valeur de $uri aux simples caractères autorisé
	*
	* @param  string $uri -> Url du fichier à simplifier
	* @return string
	*/
	private function _clearUri($uri){
		return preg_replace('#[^a-zA-Z0-9_-]#', '', $uri);
	}
}
?>