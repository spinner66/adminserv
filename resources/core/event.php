<?php

/**
* Classe pour la gestion des événements
*/
class AdminServEvent {
	
	/**
	* Retourne les variables globales d'AdminServ
	*/
	public static function getGlobals(){
		global $category, $view, $index, $id, $directory, $setTheme, $setLang;
		
		if( isset($_GET['p']) ){
			define('USER_PAGE', htmlspecialchars($_GET['p']) );
		}
		else{
			if( isset($_SESSION['adminserv']['check_password']) || isset($_SESSION['adminserv']['get_password']) ){
				define('USER_PAGE', 'servers-online-config');
			}
			else{
				define('USER_PAGE', 'index');
			}
		}
		
		if( isset($_GET['c']) ){ $category = addslashes( htmlspecialchars($_GET['c']) ); }else{ $category = null; }
		if( isset($_GET['view']) ){ $view = addslashes( htmlspecialchars($_GET['view']) ); }else{ $view = null; }
		if( isset($_GET['i']) ){ $index = intval($_GET['i']); }else{ $index = -1; }
		if( isset($_GET['id']) ){ $id = intval($_GET['id']); }else{ $id = -1; }
		if( isset($_GET['d']) ){ $directory = addslashes( urldecode($_GET['d']) ); }else{ $directory = null; }
		if( isset($_GET['th']) ){ $setTheme = addslashes($_GET['th']); }else{ $setTheme = null; }
		if( isset($_GET['lg']) ){ $setLang = addslashes($_GET['lg']); }else{ $setLang = null; }
	}
	
	
	/**
	* Permet de changer de serveur
	*/
	public static function switchServer(){
		if( isset($_GET['switch']) && $_GET['switch'] != null ){
			$_SESSION['adminserv']['sid'] = AdminServServerConfig::getServerId($_GET['switch']);
			$_SESSION['adminserv']['name'] = $_GET['switch'];
			unset($_SESSION['adminserv']['teaminfo']);
			Utils::addCookieData('adminserv', array($_SESSION['adminserv']['sid'], Utils::readCookieData('adminserv', 1)), AdminServConfig::COOKIE_EXPIRE);
			
			if(USER_PAGE && USER_PAGE != 'index'){
				Utils::redirection(false, '?p='.USER_PAGE);
			}
			else{
				Utils::redirection();
			}
		}
	}
	
	
	/**
	* Vérifie si on est connecté au serveur
	*/
	public static function isLoggedIn(){
		$out = false;
		
		if( isset($_SESSION['adminserv']['sid']) && isset($_SESSION['adminserv']['password']) && isset($_SESSION['adminserv']['adminlevel']) && !isset($_GET['error']) ){
			$out = true;
		}
		
		return $out;
	}
	
	
	/**
	* Déconnexion
	*/
	public static function logout(){
		if( isset($_GET['error']) || isset($_GET['logout']) ){
			session_unset();
			session_destroy();
			if( isset($_GET['logout']) ){
				Utils::redirection(false);
			}
		}
	}
}
?>