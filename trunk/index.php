<?php
	// INCLUDES
	session_start();
	require_once 'config/adminserv.cfg.php';
	require_once 'config/servers.cfg.php';
	require_once 'config/extension.cfg.php';
	require_once 'config/adminlevel.cfg.php';
	require_once AdminServConfig::$PATH_RESOURCES .'core/adminserv.php';
	
	// LOAD TIMER
	if(ADMINSERV_TIMER){
		AdminServ::startTimer();
	}
	
	// INITIALIZE
	AdminServ::checkPHPVersion('5.3.0');
	define('PATH_ROOT', basename(__DIR__).'/');
	$_SESSION['adminserv']['path'] = null;
	if(AdminServConfig::MULTI_ADMINSERV){
		$_SESSION['adminserv']['path'] = PATH_ROOT;
	}
	AdminServ::getClass();
	
	// GLOBALS
	AdminServEvent::getGlobals();
	
	// THEME
	$userTheme = AdminServUI::theme($setTheme);
	define('USER_THEME', $userTheme);
	
	// LANG
	$userLang = AdminServUI::lang($setLang);
	define('USER_LANG', $userLang);
	
	// VÉRIFICATION DES DROITS
	$checkRightsList = array(
		'./config/adminserv.cfg.php' => 666,
		'./config/servers.cfg.php' => 666,
	);
	if( in_array(true, AdminServConfig::$LOGS) ){
		if( Utils::isWinServer() ){ $checkRightsList['./logs/'] = 666; }
		else{ $checkRightsList['./logs/'] = 777; }
	}
	AdminServ::checkRights($checkRightsList);
	
	// LOGOUT
	AdminServEvent::logout();
	
	// LOGS
	AdminServLogs::initialize();
	
	// PLUGINS
	$userPlugin = AdminServPlugin::getCurrent();
	define('USER_PLUGIN', $userPlugin);
	
	
	// CONFIG PAGES LIST
	$configPagesList = array(
		'servers',
		'addserver',
		'servers-order',
		'serversconfigpassword',
	);
	
	
	// INDEX
	unset($setTheme, $userTheme, $setLang, $userLang);
	if( AdminServEvent::isLoggedIn() ){
		
		// SWITCH SERVER
		AdminServEvent::switchServer();
		
		// SERVER CONNECTION
		AdminServ::initialize();
		
		// PAGES GROUPES
		if( strstr(USER_PAGE, '-') ){
			$pageEx = explode('-', USER_PAGE);
			$pageInc = AdminServConfig::$PATH_RESOURCES .'pages/'.$pageEx[0].'.inc.php';
			if( file_exists($pageInc) ){
				include_once $pageInc;
			}
		}
		// PAGES UNIQUES
		$pagesList = array(
			'general',
			'srvopts',
			'gameinfos',
			'chat',
			'plugins-list',
			'guestban',
		);
		$pagesList = array_merge($pagesList, array_keys(ExtensionConfig::$MAPSMENU) );
		
		// INCLUDES DES PAGES
		if( in_array(USER_PAGE, $pagesList) ){
			unset($pagesList[0]);
			foreach($pagesList as $page){
				if(USER_PAGE === $page){
					$file = AdminServConfig::$PATH_RESOURCES .'pages/'.$page.'.php';
					if( file_exists($file) ){
						include_once $file;
						AdminServLogs::add('access', 'Control');
					}
					break;
				}
			}
		}
		else{
			if( in_array(USER_PAGE, $configPagesList) ){
				session_unset();
				session_destroy();
				Utils::redirection(false, './config/');
			}
			else{
				if(!USER_PLUGIN){
					include_once AdminServConfig::$PATH_RESOURCES .'pages/'.$pagesList[0].'.php';
					AdminServLogs::add('access', 'Control');
				}
			}
		}
	}
	else{
		// CONFIG
		if( in_array(USER_PAGE, $configPagesList) ){
			foreach($configPagesList as $page){
				if(USER_PAGE === $page){
					$file = AdminServConfig::$PATH_RESOURCES .'pages/'.$page.'.php';
					if( file_exists($file) ){
						$GLOBALS['page_title'] = 'Configuration';
						include_once $file;
						AdminServLogs::add('access', 'Configuration');
					}
					break;
				}
			}
		}
		// CONNEXION
		else{
			$GLOBALS['page_title'] = 'Connexion';
			include_once AdminServConfig::$PATH_RESOURCES .'pages/connection.php';
			AdminServLogs::add('access', 'Connection');
		}
	}
?>