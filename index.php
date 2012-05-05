<?php
	// INCLUDES
	session_start();
	define('ADMINSERV_VERSION', '2.0');
	require_once 'config/adminserv.cfg.php';
	require_once 'config/servers.cfg.php';
	require_once 'config/extension.cfg.php';
	require_once AdminServConfig::PATH_INCLUDES .'adminserv.inc.php';
	AdminServUI::getClass();
	
	// ISSET
	if( isset($_GET['p']) ){
		define('USER_PAGE', htmlspecialchars($_GET['p']) );
	}
	else{
		if( isset($_SESSION['adminserv']['check_password']) ){
			define('USER_PAGE', 'check-password');
		}
		else{
			define('USER_PAGE', 'index');
		}
	}
	if( isset($_GET['c']) ){ $category = addslashes( htmlspecialchars($_GET['c']) ); }else{ $category = null; }
	if( isset($_GET['view']) ){ $view = addslashes( htmlspecialchars($_GET['view']) ); }else{ $view = null; }
	if( isset($_GET['i']) ){ $index = intval($_GET['i']); }else{ $index = -1; }
	if( isset($_GET['id']) ){ $id = intval($_GET['id']); }else{ $id = -1; }
	if( isset($_GET['d']) ){ $directory = addslashes($_GET['d']); }else{ $directory = null; }
	if( isset($_GET['th']) ){ $theme = addslashes($_GET['th']); }else{ $theme = null; }
	if( isset($_GET['lg']) ){ $lang = addslashes($_GET['lg']); }else{ $lang = null; }
	
	
	// DÉCONNEXION
	if( isset($_GET['error']) || isset($_GET['logout']) ){
		session_unset();
		session_destroy();
		if( isset($_GET['logout']) ){
			Utils::redirection(false);
		}
	}
	
	
	// LOGS
	AdminServLogs::initialize();
	
	
	// THEME
	define('USER_THEME', AdminServUI::getTheme($theme) );
	
	
	// LANG
	define('USER_LANG', AdminServUI::getLang($lang) );
	if( file_exists('includes/lang/'. USER_LANG .'.php') ){
		require_once 'includes/lang/'. USER_LANG .'.php';
	}
	
	
	// INDEX
	if( isset($_SESSION['adminserv']['sid']) && isset($_SESSION['adminserv']['password']) && isset($_SESSION['adminserv']['adminlevel']) && !isset($_GET['error']) ){
		
		// SWITCHS
		if( isset($_GET['switch']) && $_GET['switch'] != null ){
			$_SESSION['adminserv']['sid'] = AdminServ::getServerId($_GET['switch']);
			$_SESSION['adminserv']['name'] = $_GET['switch'];
			Utils::addCookieData('adminserv', array($_SESSION['adminserv']['sid'], Utils::readCookieData('adminserv', 1)), AdminServConfig::COOKIE_EXPIRE);
			if(USER_PAGE){
				Utils::redirection(false, '?p='.USER_PAGE);
			}else{
				Utils::redirection(false);
			}
		}
		
		// CONNEXION
		AdminServ::initialize();
		
		
		// PAGES GROUPES
		if( strstr(USER_PAGE, '-') ){
			$pageEx = explode('-', USER_PAGE);
			$pageInc = 'includes/pages/'.$pageEx[0].'.inc.php';
			if( file_exists($pageInc) ){
				include_once $pageInc;
			}
		}
		// PAGES UNIQUES
		$pages = array(
			'general',
			'srvopts',
			'gameinfos',
			'chat',
			'maps-list',
			'maps-local',
			'maps-upload',
			'maps-matchset',
			'maps-creatematchset',
			'maps-order',
			'plugins',
			'guestban',
		);
		if( in_array(USER_PAGE, $pages) ){
			unset($pages[0]);
			foreach($pages as $page){
				if(USER_PAGE === $page){
					include_once 'includes/pages/'.$page.'.php';
					break;
				}
			}
		}
		else{
			if(USER_PAGE == 'servers' || USER_PAGE == 'addserver'){
				session_unset();
				session_destroy();
				Utils::redirection(false, '?p='.USER_PAGE);
			}
			else{
				include_once 'includes/pages/'.$pages[0].'.php';
			}
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