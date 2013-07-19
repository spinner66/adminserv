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
	
	
	// INDEX
	unset($setTheme, $userTheme, $setLang, $userLang);
	if( AdminServEvent::isLoggedIn() ){
		
		// SWITCH SERVER
		AdminServEvent::switchServer();
		
		// SERVER CONNECTION
		AdminServ::initialize();
		
		// PAGES BACKOFFICE
		AdminServUI::initBackPage();
	}
	else{
		// PAGES FRONTOFFICE
		AdminServUI::initFrontPage();
	}
?>