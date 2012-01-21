<?php
	// INCLUDES
	session_start();
	define('ADMINSERV_VERSION', '2.0');
	require_once 'config/adminserv.cfg.php';
	require_once 'config/servers.cfg.php';
	require_once 'config/extension.cfg.php';
	require_once 'includes/adminserv.inc.php';
	AdminServTemplate::getClass();
	
	
	// ISSET
	if( isset($_GET['p']) ){ $page = addslashes( htmlspecialchars($_GET['p']) ); }else{ $page = null; }
	if( isset($_GET['c']) ){ $category = addslashes( htmlspecialchars($_GET['c']) ); }else{ $category = null; }
	if( isset($_GET['view']) ){ $view = addslashes( htmlspecialchars($_GET['view']) ); }else{ $view = null; }
	if( isset($_GET['i']) ){ $index = intval($_GET['i']); }else{ $index = 0; }
	if( isset($_GET['id']) ){ $id = intval($_GET['id']); }else{ $id = 0; }
	
	
	// DÉCONNEXION
	if( isset($_GET['error']) ){
		session_unset();
		session_destroy();
	}
	else if( isset($_GET['logout']) ){
		session_unset();
		session_destroy();
		Utils::redirection(false);
	}
	
	
	// INDEX
	if( isset($_SESSION['adminserv']['sid']) && isset($_SESSION['adminserv']['password']) && isset($_SESSION['adminserv']['adminlevel']) && !isset($_GET['error']) ){
		
		// SWITCHS
		if( isset($_GET['switch']) && $_GET['switch'] != null ){
			$_SESSION['adminserv']['sid'] = AdminServ::getServerId($_GET['switch']);
			$_SESSION['adminserv']['name'] = $_GET['switch'];
			Utils::addCookieData('adminserv', array($_SESSION['adminserv']['sid'], Utils::readCookieData('adminserv', 1), Utils::readCookieData('adminserv', 2), Utils::readCookieData('adminserv', 3), Utils::readCookieData('adminserv', 4), Utils::readCookieData('adminserv', 5) ), AdminServConfig::COOKIE_EXPIRE);
			if($page){
				Utils::redirection(false, '?p='.$page);
			}else{
				Utils::redirection(false);
			}
		}
		
		// CONNEXION
		AdminServ::initialize();
		
		
		// PAGES
		if($page == 'srvopts'){
			include_once 'includes/pages/srvopts.php';
		}
		else if($page == 'gameinfos'){
			include_once 'includes/pages/gameinfos.php';
		}
		else if($page == 'chat'){
			include_once 'includes/pages/chat.php';
		}
		else if($page == 'maps'){
			include_once 'includes/pages/maps_list.php';
		}
		else if($page == 'maps-local'){
			include_once 'includes/pages/maps_local.php';
		}
		else if($page == 'maps-upload'){
			include_once 'includes/pages/maps_upload.php';
		}
		else if($page == 'maps-matchset'){
			include_once 'includes/pages/maps_matchset.php';
		}
		else if($page == 'maps-order'){
			include_once 'includes/pages/maps_order.php';
		}
		else if($page == 'plugins'){
			include_once 'includes/pages/plugins.php';
		}
		else if($page == 'planets'){
			include_once 'includes/pages/planets.php';
		}
		else if($page == 'guestban'){
			include_once 'includes/pages/guestban.php';
		}
		else{
			include_once 'includes/pages/general.php';
		}
	}
	else{
		$page_title = 'Connexion';
		include_once 'includes/pages/connection.php';
	}
?>