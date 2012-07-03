<?php
	// INCLUDES
	session_start();
	define('ADMINSERV_VERSION', '2.0');
	require_once 'config/adminserv.cfg.php';
	if(AdminServConfig::MULTI_ADMINSERV){
		$_SESSION['adminserv']['path'] = basename(__DIR__).'/';
	}
	else{
		$_SESSION['adminserv']['path'] = null;
	}
	if( file_exists('config/servers.cfg.php') ){
		require_once 'config/servers.cfg.php';
	}
	require_once 'config/extension.cfg.php';
	require_once AdminServConfig::PATH_INCLUDES .'adminserv.inc.php';
	AdminServUI::getClass();
	
	
	$result = AdminServServerConfig::checkRights();
	AdminServ::dsm($result);
	
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
	$userTheme = AdminServUI::getTheme($theme);
	if($theme){
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
	$userLang = AdminServUI::getLang($lang);
	if($lang){
		if(USER_PAGE == 'index'){
			Utils::redirection();
		}
		else{
			Utils::redirection(false, '?p='.USER_PAGE);
		}
	}
	else{
		define('USER_LANG', AdminServUI::getLang($lang) );
		$langFile = AdminServConfig::PATH_INCLUDES .'lang/'. USER_LANG .'.php';
		if( file_exists($langFile) ){
			require_once $langFile;
		}
	}
	
	
	// PLUGINS
	define('CURRENT_PLUGIN', AdminServPlugin::getCurrent() );
	
	
	// INDEX
	if( isset($_SESSION['adminserv']['sid']) && isset($_SESSION['adminserv']['password']) && isset($_SESSION['adminserv']['adminlevel']) && !isset($_GET['error']) ){
		
		// SWITCHS
		if( isset($_GET['switch']) && $_GET['switch'] != null ){
			$_SESSION['adminserv']['sid'] = AdminServServerConfig::getServerId($_GET['switch']);
			AdminServ::dsm($_SESSION['adminserv']['sid']);
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
			if(USER_PAGE == 'servers' || USER_PAGE == 'addserver'){
				session_unset();
				session_destroy();
				Utils::redirection(false, '?p='.USER_PAGE);
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
		if(USER_PAGE == 'servers'){
			$GLOBALS['page_title'] = 'Configuration';
			include_once AdminServConfig::PATH_INCLUDES .'pages/servers.php';
			AdminServLogs::add('access', 'Configuration - Server list');
		}
		else if(USER_PAGE == 'addserver'){
			$GLOBALS['page_title'] = 'Configuration';
			include_once AdminServConfig::PATH_INCLUDES .'pages/addserver.php';
			AdminServLogs::add('access', 'Configuration - Add server');
		}
		else if(USER_PAGE == 'serversconfigpassword'){
			$GLOBALS['page_title'] = 'Configuration';
			include_once AdminServConfig::PATH_INCLUDES .'pages/serversconfigpassword.php';
			AdminServLogs::add('access', 'Configuration - Change server config password');
		}
		// CONNEXION
		else{
			$GLOBALS['page_title'] = 'Connexion';
			include_once AdminServConfig::PATH_INCLUDES .'pages/connection.php';
			AdminServLogs::add('access', 'Index - Connection');
		}
	}
?>