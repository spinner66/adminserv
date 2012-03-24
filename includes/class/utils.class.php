<?php
/*
* Classe utilitaire générale
*/
abstract class Utils {
	
	/**
	* Redirection
	*
	* @param bool   $auto  -> Mode automatique, redirige vers la page en cours
	* @param string $page  -> Page à rediriger
	* @param int    $sleep -> Temps en seconde à attendre avec d'exécuter la redirection
	*/
	public static function redirection($auto = true, $page = null, $sleep = null){
		$host = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		if($auto){
			$page = basename($_SERVER['PHP_SELF']);
		}
		// Redirection
		if($sleep != null){
			header("Refresh: $sleep; URL=http://$host$uri/$page");
		}else{
			header("Location: http://$host$uri/$page");
		}
		exit;
	}
	
	
	/**
	* Remplace une url texte en url cliquable
	*
	* @param string $str -> La chaine de caractères contenant une ou plusieurs url
	* @param bool $bbcode -> Convertit en HTML par defaut, true pour le BBcode
	* @return la chaine de caractères avec les url remplacées
	*/
	public static function replaceTextURL($str, $bbcode = false){
		$regex = '$(?:https?|ftp)://(?:www\.|ssl\.)?[a-z0-9._%-]+(?:/[a-z0-9._/%-]*(?:\?[a-z0-9._/%-]+=[a-z0-9._/%+-]+(?:&(?:amp;)?[a-z0-9._/%-]+=[a-z0-9._/%+-]+)*)?)?(?:#[a-z0-9._-]*)?$i';
		if($bbcode){ $code = '[url]$0[/url]'; }else{ $code = '<a href="$0">$0</a>'; }
		return preg_replace($regex, $code, $str);
	}
	
	
	/**
	* Vérifie si l'adresse email est dans un format valide
	*
	* @param str $email -> L'adresse email à tester
	* @return true si adresse valide sinon false
	*/
	public static function isValidEmail($email){
		if($email != null){
			$valid_email = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$#';
			if( preg_match($valid_email, $email) ){
				return true;
			}else{
				return false;
			}
		}
	}
	
	
	/**
	* Vérifie si le numero de téléphone est dans un format valide
	*
	* @param str $tel -> Le numero de téléphone à tester
	* @return true si numero valide sinon false
	*/
	public static function isValidTel($tel){
		if($tel != null){
			$valid_tel = '#^0[0-9]([ .-]?[0-9]{2}){4}$#';
			if( preg_match($valid_tel, $tel) ){
				return true;
			}else{
				return false;
			}
		}
	}
	
	
	/**
	* Retourne l'image du Captcha
	*
	* @require showcaptcha.php dans un dossier "./includes/captcha/"
	*/
	public static function showCaptcha(){
		// Chaine de caractères pour créer le code
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
		$code = null;
		for($i = 0; $i < 4; $i++) {
			$code .= $chars[mt_rand(0, 35)];
		}
		// On stocke le code dans la session
		$_SESSION['captcha'] = $code;
		// On ferme la session pour forcer l'écriture immédiate
		session_write_close();
		return '<img src="./includes/captcha/showcaptcha.php" height="40" width="145" alt="Impossible d\'afficher le code !" />';
	}
	
	
	/**
	* Lis les données d'un cookie
	*
	* @param string $cookie_name -> Le nom du cookie
	* @param int    $data_pos    -> La position de la donnée à retourner, null pour toutes les données
	* @return string si une position est choisie, sinon return array de toutes les données
	*/
	public static function readCookieData($cookie_name, $data_pos = null){
		$separator = '|';
		// Lecture
		if( isset($_COOKIE[$cookie_name]) ){
			$out =  explode($separator, $_COOKIE[$cookie_name]);
			// Si une position est choisie
			if( is_numeric($data_pos) ){
				if($out[$data_pos] != null){
					return $out[$data_pos];
				}
			}
			// Sinon toutes les valeurs
			else{
				if($out[0] != null){
					return $out;
				}
			}
		}else{
			return null;
		}
	}
	
	
	/**
	* Ajoute des données dans un cookie
	*
	* @param string $cookie_name   -> Le nom du cookie
	* @param array  $data          -> Les données du cookie
	* @param int    $cookie_expire -> Le nombre de jours avant que le cookie expire
	* @return true si l'écriture du cookie à réussi, sinon false
	*/
	public static function addCookieData($cookie_name, $data, $cookie_expire = 15){
		$separator = '|';
		// Liste des données
		$newCookieData = null;
		for($i = 0; $i < count($data); $i++){
			if($i == count($data)-1){
				$newCookieData .= $data[$i];
			}else{
				$newCookieData .= $data[$i].$separator;
			}
		}
		// On écrit le cookie
		if( setcookie($cookie_name, $newCookieData, time()+60*60*24*$cookie_expire, '/') ){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	* Récupère la langue du navigateur
	*
	* @param string $forceLang -> Forcer l'utilisation du langue
	* @return $_SESSION['lang']
	*/
	public static function getLang($forceLang = null){
		// Si on choisi une langue
		if($forceLang){
			$_SESSION['lang'] = $forceLang;
		}
		else{
			if( !isset($_SESSION['lang']) ){
				// On récupère la langue du navigateur
				$_SESSION['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			}
		}
		
		return $_SESSION['lang'];
	}
	
	
	/**
	* Retourne la valeur du terme
	*/
	public static function t($key){
		global $translate;
		
		if( isset($_SESSION['lang']) ){
			if($_SESSION['lang'] == 'en'){
				return $key;
			}else{
				if( isset($translate[$key]) ){
					return $translate[$key];
				}else{
					return $key;
				}
			}
		}else{
			return $key;
		}
	}
	
	
	/**
	* Récupère le navigateur internet du visiteur
	*/
	public static function getBrowser(){
		$out = null;
		$http_user_agent = $_SERVER['HTTP_USER_AGENT'];
		if( strstr($http_user_agent, 'Firefox') ){ $out = 'Firefox'; }
		else if( strstr($http_user_agent, 'Chrome') ){ $out = 'Chrome'; }
		else if( strstr($http_user_agent, 'Opera') ){ $out = 'Opera'; }
		else if( strstr($http_user_agent, 'MSIE') ){ $out = 'IE'; }
		else if( strstr($http_user_agent, 'Safari') ){ $out = 'Safari'; }
		else if( strstr($http_user_agent, 'Konqueror') ){ $out = 'Konqueror'; }
		else if( strstr($http_user_agent, 'Netscape') ){ $out = 'Netscape'; }
		else{ $out = 'Others'; }
		return $out;
	}
	
	
	/**
	* Récupère le système d'exploitation du visiteur
	*/
	public static function getOperatingSystem(){
		$out = null;
		$http_user_agent = $_SERVER['HTTP_USER_AGENT'];
		if( strstr($http_user_agent, 'Win') ){ $out = 'Windows'; }
		else if( (strstr($http_user_agent, 'Mac')) || (strstr('PPC', $http_user_agent)) ){ $out = 'Mac'; }
		else if( strstr($http_user_agent, 'Linux') ){ $out = 'Linux'; }
		else if( strstr($http_user_agent, 'FreeBSD') ){ $out = 'FreeBSD'; }
		else if( strstr($http_user_agent, 'SunOS') ){ $out = 'SunOS'; }
		else if( strstr($http_user_agent, 'IRIX') ){ $out = 'IRIX'; }
		else if( strstr($http_user_agent, 'BeOS') ){ $out = 'BeOS'; }
		else if( strstr($http_user_agent, 'OS/2') ){ $out = 'OS/2'; }
		else if( strstr($http_user_agent, 'AIX') ){ $out = 'AIX'; }
		else{ $out = 'Others'; }
		return $out;
	}
}
?>