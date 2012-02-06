<?php
	// INCLUDES
	session_start();
	//TODO : define('ADMINSERV_PATH', __DIR__ .'/');
	define('ADMINSERV_VERSION', '2.0');
	require_once 'config/adminserv.cfg.php';
	require_once 'config/servers.cfg.php';
	require_once 'config/extension.cfg.php';
	require_once 'includes/adminserv.inc.php';
	AdminServTemplate::getClass();
	
	
	// ISSET
	if( isset($_GET['p']) ){ define('USER_PAGE', htmlspecialchars($_GET['p']) ); }else{ define('USER_PAGE', 'index'); }
	if( isset($_GET['c']) ){ $category = addslashes( htmlspecialchars($_GET['c']) ); }else{ $category = null; }
	if( isset($_GET['view']) ){ $view = addslashes( htmlspecialchars($_GET['view']) ); }else{ $view = null; }
	if( isset($_GET['i']) ){ $index = intval($_GET['i']); }else{ $index = 0; }
	if( isset($_GET['id']) ){ $id = intval($_GET['id']); }else{ $id = 0; }
	if( isset($_GET['d']) ){ $directory = addslashes($_GET['d']); }else{ $directory = null; }
	
	
	// DÉCONNEXION
	if( isset($_GET['error']) || isset($_GET['logout']) ){
		session_unset();
		session_destroy();
		if( isset($_GET['logout']) ){
			Utils::redirection(false);
		}
	}
	
	
	// INDEX
	if( isset($_SESSION['adminserv']['sid']) && isset($_SESSION['adminserv']['password']) && isset($_SESSION['adminserv']['adminlevel']) && !isset($_GET['error']) ){
		
		// SWITCHS
		if( isset($_GET['switch']) && $_GET['switch'] != null ){
			$_SESSION['adminserv']['sid'] = AdminServ::getServerId($_GET['switch']);
			$_SESSION['adminserv']['name'] = $_GET['switch'];
			Utils::addCookieData('adminserv', array($_SESSION['adminserv']['sid'], Utils::readCookieData('adminserv', 1), Utils::readCookieData('adminserv', 2), Utils::readCookieData('adminserv', 3), Utils::readCookieData('adminserv', 4), Utils::readCookieData('adminserv', 5) ), AdminServConfig::COOKIE_EXPIRE);
			if(USER_PAGE){
				Utils::redirection(false, '?p='.USER_PAGE);
			}else{
				Utils::redirection(false);
			}
		}
		
		// CONNEXION
		AdminServ::initialize();
		
		
		// PAGES
		if(USER_PAGE == 'srvopts'){
			include_once 'includes/pages/srvopts.php';
		}
		else if(USER_PAGE == 'gameinfos'){
			include_once 'includes/pages/gameinfos.php';
		}
		else if(USER_PAGE == 'chat'){
			include_once 'includes/pages/chat.php';
		}
		else if(USER_PAGE == 'maps'){
			include_once 'includes/pages/maps_list.php';
		}
		else if(USER_PAGE == 'maps-local'){
			include_once 'includes/pages/maps_local.php';
		}
		else if(USER_PAGE == 'maps-upload'){
			include_once 'includes/pages/maps_upload.php';
		}
		else if(USER_PAGE == 'maps-matchset'){
			include_once 'includes/pages/maps_matchset.php';
		}
		else if(USER_PAGE == 'maps-order'){
			include_once 'includes/pages/maps_order.php';
		}
		else if(USER_PAGE == 'plugins'){
			include_once 'includes/pages/plugins.php';
		}
		else if(USER_PAGE == 'planets'){
			include_once 'includes/pages/planets.php';
		}
		else if(USER_PAGE == 'guestban'){
			include_once 'includes/pages/guestban.php';
		}
		else{
			include_once 'includes/pages/general.php';
		}
	}
	else{
		// CONFIG
		if(USER_PAGE == 'servers'){
			$GLOBALS['page_title'] = 'Configuration';
			include_once 'includes/pages/servers.php';
		}
		else if(USER_PAGE == 'addserver'){
			$GLOBALS['page_title'] = 'Configuration';
			include_once 'includes/pages/addserver.php';
		}
		// CONNEXION
		else{
			$GLOBALS['page_title'] = 'Connexion';
			include_once 'includes/pages/connection.php';
		}
	}
?>