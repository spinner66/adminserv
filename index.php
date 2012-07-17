<?php
	// INCLUDES
	session_start();
	define('ADMINSERV_TIMER', true);
	define('ADMINSERV_VERSION', '2.0');
	require_once 'config/adminserv.cfg.php';
	$_SESSION['adminserv']['path'] = null;
	if(AdminServConfig::MULTI_ADMINSERV){
		$_SESSION['adminserv']['path'] = basename(__DIR__).'/';
	}
	if( file_exists('config/servers.cfg.php') ){
		include_once 'config/servers.cfg.php';
	}
	require_once 'config/extension.cfg.php';
	require_once AdminServConfig::PATH_INCLUDES .'adminserv.inc.php';
	if( !AdminServ::checkPHPVersion() ){
		echo '<b>This PHP version is not compatible with AdminServ.</b><br />Your PHP version: '.phpversion().'<br />PHP version required: 5.3';
		exit;
	}
	AdminServUI::getClass();
	
	
	// LOAD TIME
	if(ADMINSERV_TIMER){
		AdminServ::startTimer();
	}
	
	
	// VÉRIFICATION DES DROITS
	$checkRightsList = array(
		'./config/adminserv.cfg.php' => 664,
		'./config/servers.cfg.php' => 664,
	);
	if( in_array(true, AdminServConfig::$LOGS) ){
		$checkRightsList['./logs/'] = 664;
	}
	AdminServ::checkRights($checkRightsList);
	
	
	// ISSET
	if( isset($_GET['p']) ){
		define('USER_PAGE', htmlspecialchars($_GET['p']) );
	}
	else{
		if( isset($_SESSION['adminserv']['check_password']) || isset($_SESSION['adminserv']['get_password']) ){
			define('USER_PAGE', 'config-servers');
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
	if( isset($_GET['th']) ){ $forceTheme = addslashes($_GET['th']); }else{ $forceTheme = null; }
	if( isset($_GET['lg']) ){ $forceLang = addslashes($_GET['lg']); }else{ $forceLang = null; }
	
	
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
	$userTheme = AdminServUI::getTheme($forceTheme);
	if($forceTheme){
		if(USER_PAGE == 'index'){
			Utils::redirection();
		}
		else{
			Utils::redirection(false, '?p='.USER_PAGE);
		}
	}
	else{
		define('USER_THEME', $userTheme);
	}
	
	
	// LANG
	$userLanguage = AdminServUI::getLang($forceLang);
	if($forceLang){
		if(USER_PAGE == 'index'){
			Utils::redirection();
		}
		else{
			Utils::redirection(false, '?p='.USER_PAGE);
		}
	}
	else{
		define('USER_LANG', $userLanguage);
		$langFile = AdminServConfig::PATH_INCLUDES .'lang/'. USER_LANG .'.php';
		if( file_exists($langFile) ){
			require_once $langFile;
		}
	}
	
	
	// PLUGINS
	define('CURRENT_PLUGIN', AdminServPlugin::getCurrent() );
	
	
	// CONFIG PAGES LIST
	$CONFIGPAGESLIST = array(
		'servers',
		'addserver',
		'servers-order',
		'serversconfigpassword',
	);
	
	
	// INDEX
	if( isset($_SESSION['adminserv']['sid']) && isset($_SESSION['adminserv']['password']) && isset($_SESSION['adminserv']['adminlevel']) && !isset($_GET['error']) ){
		
		// SWITCHS
		if( isset($_GET['switch']) && $_GET['switch'] != null ){
			$_SESSION['adminserv']['sid'] = AdminServServerConfig::getServerId($_GET['switch']);
			$_SESSION['adminserv']['name'] = $_GET['switch'];
			Utils::addCookieData('adminserv', array($_SESSION['adminserv']['sid'], Utils::readCookieData('adminserv', 1)), AdminServConfig::COOKIE_EXPIRE);
			if(USER_PAGE && USER_PAGE != 'index'){
				Utils::redirection(false, '?p='.USER_PAGE);
			}else{
				Utils::redirection();
			}
		}
		
		// CONNEXION
		AdminServ::initialize();
		
		
		// PAGES GROUPES
		if( strstr(USER_PAGE, '-') ){
			$pageEx = explode('-', USER_PAGE);
			$pageInc = AdminServConfig::PATH_INCLUDES .'pages/'.$pageEx[0].'.inc.php';
			if( file_exists($pageInc) ){
				include_once $pageInc;
			}
		}
		// PAGES UNIQUES
		$PAGESLIST = array(
			'general',
			'srvopts',
			'gameinfos',
			'chat',
			'plugins-list',
			'guestban',
		);
		$PAGESLIST = array_merge($PAGESLIST, array_keys(ExtensionConfig::$MAPSMENU) );
		
		// INCLUDES DES PAGES
		if( in_array(USER_PAGE, $PAGESLIST) ){
			unset($PAGESLIST[0]);
			foreach($PAGESLIST as $page){
				if(USER_PAGE === $page){
					$file = AdminServConfig::PATH_INCLUDES .'pages/'.$page.'.php';
					if( file_exists($file) ){
						include_once $file;
						AdminServLogs::add('access', 'Connected - Access to the page');
					}
					break;
				}
			}
		}
		else{
			if( in_array(USER_PAGE, $CONFIGPAGESLIST) ){
				session_unset();
				session_destroy();
				Utils::redirection(false, './config/');
			}
			else{
				if(!CURRENT_PLUGIN){
					include_once AdminServConfig::PATH_INCLUDES .'pages/'.$PAGESLIST[0].'.php';
				}
			}
		}
	}
	else{
		// CONFIG
		if( in_array(USER_PAGE, $CONFIGPAGESLIST) ){
			foreach($CONFIGPAGESLIST as $page){
				if(USER_PAGE === $page){
					$file = AdminServConfig::PATH_INCLUDES .'pages/'.$page.'.php';
					if( file_exists($file) ){
						$GLOBALS['page_title'] = 'Configuration';
						include_once $file;
						AdminServLogs::add('access', 'Configuration - '.ucfirst($page) );
					}
					break;
				}
			}
		}
		// CONNEXION
		else{
			$GLOBALS['page_title'] = 'Connexion';
			include_once AdminServConfig::PATH_INCLUDES .'pages/connection.php';
			AdminServLogs::add('access', 'Index - Connection');
		}
	}
?>