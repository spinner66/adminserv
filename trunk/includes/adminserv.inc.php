<?php

/**
* Classe pour l'interface d'AdminServ
*/
abstract class AdminServUI {
	
	/**
	* Récupère le titre de l'application
	*
	* @param string $type -> Retourner "str" ou "html"
	* @return string
	*/
	public static function getTitle($type = 'str'){
		$out = null;
		$title = AdminServConfig::TITLE;
		
		// Si aucun titre n'est spécifié, on met "AdminServ" par défaut
		if(!$title){
			$title = 'Admin,Serv';
		}
		
		// Si il y a une séparation
		if( strstr($title, ',') ){
			if($type == 'str'){
				$out = str_replace(',', '', $title);
			}
			else if($type == 'html'){
				$titleEx = explode(',', $title);
				$out = $titleEx[0].'<span class="title-color">'.$titleEx[1].'</span>';
			}
		}
		// Sinon, on renvoi le titre simple
		else{
			$out = $title;
		}
		
		return $out;
	}
	
	
	/**
	* Récupère le thème courant
	*
	* @param string $forceTheme -> Forcer l'utilisation du thème
	* @return $_SESSION['theme']
	*/
	public static function getTheme($forceTheme = null){
		$saveCookie = false;
		// Si on choisi un thème
		if($forceTheme){
			$_SESSION['theme'] = $forceTheme;
			$saveCookie = true;
		}
		else{
			if( !isset($_SESSION['theme']) ){
				// On récupère le thème dans le cookie
				if( isset($_COOKIE['adminserv_user']) ){
					$_SESSION['theme'] = Utils::readCookieData('adminserv_user', 0);
				}
				// Sinon thème par défaut
				else{
					if(AdminServConfig::DEFAULT_THEME){
						$_SESSION['theme'] = AdminServConfig::DEFAULT_THEME;
					}
					else{
						$_SESSION['theme'] = 'blue';
					}
					$saveCookie = true;
				}
			}
		}
		
		if($saveCookie){
			Utils::addCookieData('adminserv_user', array($_SESSION['theme'], self::getLang(), Utils::readCookieData('adminserv_user', 2), Utils::readCookieData('adminserv_user', 3)), AdminServConfig::COOKIE_EXPIRE);
		}
		
		return $_SESSION['theme'];
	}
	
	
	/**
	* Récupère la liste des thèmes
	*/
	public static function getThemeList($currentTheme = array() ){
		$out = null;
		
		// Thème courant
		if( count($currentTheme) > 0 ){
			$currentThemeName = key($currentTheme);
			$currentThemeColor = current($currentTheme);
			unset(ExtensionConfig::$THEMES[$currentThemeName]);
		}
		
		if(count(ExtensionConfig::$THEMES) > 0){
			$out .= '<ul>';
			// Si il y a un thème courant, on le place en 1er
			if( count($currentTheme) > 0 ){
				$out .= '<li><a class="theme-color" style="background-color: '.$currentThemeColor.';" href="?th='.$currentThemeName.'" title="'.ucfirst($currentThemeName).'"></a></li>';
			}
			foreach(ExtensionConfig::$THEMES as $name => $color){
				$out .= '<li><a class="theme-color" style="background-color: '.$color.';" href="?th='.$name.'" title="'.ucfirst($name).'"></a></li>';
			}
			$out .= '</ul>';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la langue courante
	*
	* @param string $forceLang -> Forcer l'utilisation du langue
	* @return $_SESSION['lang']
	*/
	public static function getLang($forceLang = null){
		$saveCookie = false;
		// Si on choisi une langue
		if($forceLang){
			$_SESSION['lang'] = $forceLang;
			$saveCookie = true;
		}
		else{
			if( !isset($_SESSION['lang']) ){
				// On récupère la langue dans le cookie
				if( isset($_COOKIE['adminserv_user']) ){
					$_SESSION['lang'] = Utils::readCookieData('adminserv_user', 1);
				}
				else{
					// On récupère la langue du navigateur
					if( AdminServConfig::DEFAULT_LANGUAGE == 'auto' ){
						$_SESSION['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
					}
					// Sinon langue par défaut
					else{
						if(AdminServConfig::DEFAULT_LANGUAGE){
							$_SESSION['lang'] = AdminServConfig::DEFAULT_LANGUAGE;
						}
						else{
							$_SESSION['lang'] = 'en';
						}
					}
					$saveCookie = true;
				}
			}
		}
		
		if($saveCookie){
			Utils::addCookieData('adminserv_user', array(self::getTheme(), $_SESSION['lang'], Utils::readCookieData('adminserv_user', 2), Utils::readCookieData('adminserv_user', 3)), AdminServConfig::COOKIE_EXPIRE);
		}
		
		return $_SESSION['lang'];
	}
	
	
	/**
	* Récupère la liste des langues
	*/
	public static function getLangList($currentLang = array() ){
		$out = null;
		
		// Langue courante
		if( count($currentLang) > 0 ){
			$currentLangCode = key($currentLang);
			$currentLangName = current($currentLang);
			unset(ExtensionConfig::$LANG[$currentLangCode]);
		}
		
		// Liste de toutes les langues
		if(count(ExtensionConfig::$LANG) > 0){
			$out .= '<ul>';
			// Si il y a une langue courante, on la place en 1er
			if( count($currentLang) > 0 ){
				$out .= '<li><a class="lang-flag" style="background-image: url('. AdminServConfig::PATH_RESSOURCES .'images/lang/'.$currentLangCode.'.png);" href="?lg='.$currentLangCode.'" title="'.$currentLangName.'"></a></li>';
			}
			foreach(ExtensionConfig::$LANG as $code => $name){
				$out .= '<li><a class="lang-flag" style="background-image: url('. AdminServConfig::PATH_RESSOURCES .'images/lang/'.$code.'.png);" href="?lg='.$code.'" title="'.$name.'"></a></li>';
			}
			$out .= '</ul>';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère et inclue les classes PHP
	*/
	public static function getClass(){
		require_once __DIR__ .'/class/GbxRemote.inc.php';
		require_once __DIR__ .'/class/gbxdatafetcher.inc.php';
		require_once __DIR__ .'/class/utils.class.php';
		require_once __DIR__ .'/class/tmnick.class.php';
		require_once __DIR__ .'/class/upload.class.php';
		require_once __DIR__ .'/class/timedate.class.php';
		require_once __DIR__ .'/class/file.class.php';
		require_once __DIR__ .'/class/folder.class.php';
		require_once __DIR__ .'/class/str.class.php';
		require_once __DIR__ .'/class/zip.class.php';
	}
	
	
	/**
	* Récupère le header/footer du site
	*/
	public static function getHeader(){
		global $GLOBALS;
		
		// Classes CSS body
		if( defined('SERVER_NAME') ){
			$GLOBALS['page_title'] = SERVER_NAME;
			$GLOBALS['body_class'] = ' not-front';
		}
		else{
			$GLOBALS['body_class'] = ' front';
		}
		$GLOBALS['body_class'] .= ' section-'.USER_PAGE;
		$GLOBALS['body_class'] = trim($GLOBALS['body_class']);
		
		require_once __DIR__ .'/header.inc.php';
	}
	public static function getFooter(){
		require_once __DIR__ .'/footer.inc.php';
	}
	
	
	/**
	* Récupère le CSS/JS du site
	*/
	public static function getCss($path = AdminServConfig::PATH_RESSOURCES){
		$out = '<link rel="stylesheet" href="'.$path.'styles/jquery-ui.css" />';
		if(USER_PAGE == 'maps-upload'){
			$out .= '<link rel="stylesheet" href="'.$path.'styles/fileuploader.css" />';
		}
		$out .= '<link rel="stylesheet" href="'.$path.'styles/global.css" />';
		if( defined('USER_THEME') ){
			if( file_exists($path.'styles/'. USER_THEME .'.css') ){
				$out .= '<link rel="stylesheet" href="'.$path.'styles/'. USER_THEME .'.css" />';
			}
		}
		
		return $out;
	}
	public static function getJS($path = AdminServConfig::PATH_INCLUDES){
		$out = '<script src="'.$path.'js/jquery.js"></script>'
		.'<script src="'.$path.'js/jquery-ui.js"></script>';
		if(USER_PAGE == 'maps-upload'){
			$out .= '<script src="'.$path.'js/fileuploader.js"></script>';
		}
		$out .= '<script src="'.$path.'js/functions.js"></script>'
		.'<script src="'.$path.'js/adminserv.js"></script>';
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des serveurs configurés
	*
	* @return string
	*/
	public static function getServerList(){
		$out = null;
		
		// On vérifie qu'une configuration existe
		if( class_exists('ServerConfig') ){
			
			// Si la configuration contient au moins 1 serveur et qu'il n'est pas l'exemple
			if( isset(ServerConfig::$SERVERS) && count(ServerConfig::$SERVERS) > 0 && !isset(ServerConfig::$SERVERS['new server name']) && !isset(ServerConfig::$SERVERS['']) ){
				
				if( isset($_GET['server']) && $_GET['server'] != null ){
					$currentServerId = intval($_GET['server']);
				}
				else{
					// Id du serveur utilisé dernièrement
					$currentServerId = Utils::readCookieData('adminserv', 0);
				}
				
				// Liste des serveurs
				foreach(ServerConfig::$SERVERS as $server => $values){
					if( AdminServ::getServerId($server) == $currentServerId ){
						$selected = ' selected="selected"';
					}else{
						$selected = null;
					}
					$out .= '<option value="'.$server.'"'.$selected.'>'.$server.'</option>';
				}
			}
			else{
				$out = -1;
			}
		}
		else{
			$out = -1;
		}
		
		
		// Retour
		if($out === -1){
			$out = '<option value="null">Aucun serveur disponible</option>';
		}
		return $out;
	}
	
	
	/**
	* Récupère la liste des modes de jeu
	*
	* @param int $currentGameMode -> Le mode de jeu à sélectionner
	* @return string
	*/
	public static function getGameModeList($currentGameMode = null){
		$out = null;
		
		// On vérifie qu'une configuration existe
		if( class_exists('ExtensionConfig') ){
			
			// Si la configuration contient au moins 1 mode de jeu
			if( isset(ExtensionConfig::$GAMEMODES) && count(ExtensionConfig::$GAMEMODES) > 0 ){
				
				// Pour TMF
				if(SERVER_VERSION_NAME != 'ManiaPlanet'){
					unset(ExtensionConfig::$GAMEMODES[0]);
					$newGameModes = array();
					foreach(ExtensionConfig::$GAMEMODES as $gameModeId => $gameModeName){
						$newGameModes[] = $gameModeName;
					}
					ExtensionConfig::$GAMEMODES = $newGameModes;
				}
				
				// Liste des modes de jeu
				foreach(ExtensionConfig::$GAMEMODES as $gameModeId => $gameModeName){
					if( $gameModeId == $currentGameMode ){
						$selected = ' selected="selected"';
					}else{
						$selected = null;
					}
					$out .= '<option value="'.$gameModeId.'"'.$selected.'>'.$gameModeName.'</option>';
				}
			}
			else{
				$out = -1;
			}
		}
		else{
			$out = -1;
		}
		
		// Retour
		if($out === -1){
			$out = '<option value="null">Aucun mode de jeu disponible</option>';
		}
		return $out;
	}
	
	
	/**
	* Récupère la liste des joueurs
	*
	* @param string $currentPlayerLogin -> Le login joueur à sélectionner
	* @return string
	*/
	public static function getPlayerList($currentPlayerLogin = null){
		global $client;
		$out = null;
		
		if( !$client->query('GetPlayerList', AdminServConfig::LIMIT_PLAYERS_LIST, 0) ){
			$out = -1;
		}
		else{
			$playerList = $client->getResponse();
			if( count($playerList) > 0 ){
				foreach($playerList as $player){
					if($currentPlayerLogin == $player['Login']){ $selected = ' selected="selected"'; }
					else{ $selected = null; }
					$out .= '<option value="'.$player['Login'].'"'.$selected.'>'.TmNick::toText($player['NickName']).'</option>';
				}
			}
			else{
				$out = -1;
			}
		}
		
		// Retour
		if($out === -1){
			$out = '<option value="null">Aucun joueur disponible</option>';
		}
		return $out;
	}
	
	
	/**
	* Retourne une liste html pour un menu
	*
	* @param array $list -> array('Nom du lien' => 'nom_de_la_page')
	* @return html
	*/
	public static function getMenuList($list){
		global $directory;
		$out = null;
		
		if( count($list) > 0 ){
			$out = '<nav class="vertical-nav">'
				.'<ul>';
					foreach($list as $title => $page){
						$out .= '<li><a '; if(USER_PAGE == $page){ $out .= 'class="active" '; } $out .= 'href="?p='.$page; if($directory){ $out .= '&amp;d='.$directory; } $out .= '">'.$title.'</a></li>';
					}
			$out .= '</ul>'
			.'</nav>';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la liset des dossiers du répertoire "Maps"
	*
	* @require class "Folder", "File", "Str"
	*
	* @param string $path -> Le chemin du dossier "Maps"
	* @param string $currentPath -> Le chemin à partir de "Maps"
	* @return string
	*/
	public static function getMapsDirectoryList($path, $currentPath = null){
		$out = null;
		
		if( class_exists('Folder') ){
			// Titre + nouveau dossier
			$out .= '<form id="createFolderForm" method="post" action="?p=maps.inc&amp;d='.$currentPath.'&amp;goto='. USER_PAGE .'">'
				.'<h1>Dossiers'
					.'<div id="form-new-folder" hidden="hidden">'
						.'<input class="text" type="text" name="newFolderName" id="newFolderName" value="" />'
						.'<input class="button light" type="submit" name="newFolderValid" id="newFolderValid" value="ok" />'
					.'</div>'
				.'</h1>'
				.'<div class="title-detail"><a href="." id="newfolder" data-cancel="Annuler" data-new="Nouveau">Nouveau</a></div>'
			.'</form>';
			
			// Liste des dossiers
			if( file_exists($path) ){
				if(USER_PAGE == 'maps-matchset'){
					$directory = Folder::read($path.$currentPath, AdminServConfig::$MATCHSET_HIDDEN_FOLDERS, AdminServConfig::$MATCHSET_HIDDEN_FILES, AdminServConfig::RECENT_STATUS_PERIOD);
				}
				else{
					$directory = Folder::read($path.$currentPath, AdminServConfig::$MAPS_HIDDEN_FOLDERS, AdminServConfig::$MAPS_HIDDEN_FILES, AdminServConfig::RECENT_STATUS_PERIOD);
				}
				
				if( is_array($directory) ){
					$out .= '<div class="folder-list">'
					.'<ul>';
					
					// Dossier parent
					if($currentPath){
						$params = null;
						$parentPathEx = explode('/', $currentPath);
						array_pop($parentPathEx);
						array_pop($parentPathEx);
						if( count($parentPathEx) > 0 ){
							$parentPath = null;
							foreach($parentPathEx as $part){
								$parentPath .= $part.'/';
							}
							if($parentPath){
								$params = '&amp;d='.$parentPath;
							}
						}
						
						$out .= '<li>'
							.'<a href="./?p='. USER_PAGE . $params.'">'
								.'<img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/back.png" alt="" />'
								.'<span class="dir-name">Dossier parent</span>'
							.'</a>'
						.'</li>';
					}
					
					// Dossiers
					if( count($directory['folders']) > 0 ){
						foreach($directory['folders'] as $dir => $values){
							$out .= '<li>'
								.'<a href="./?p='. USER_PAGE .'&amp;d='.$currentPath.$dir.'/">'
									.'<span class="dir-name">'.$dir.'</span>'
									.'<span class="dir-info">'.$values['nb_file'].'</span>'
								.'</a>'
							.'</li>';
						}
					}
					$out .= '</ul>'
					.'</div>';
				}
				else{
					AdminServ::error($directory);
				}
			}
			else{
				AdminServ::error('Path not exists');
			}
			
			// Options de dossier
			if($currentPath){
				$out .= '<form id="optionFolderForm" method="post" action="?p=maps.inc&amp;d='.$currentPath.'&amp;goto='. USER_PAGE .'">'
					.'<div class="option-folder-list">'
						.'<h3>Options du dossier<span class="arrow-down">&nbsp;</span></h3>'
						.'<ul hidden="hidden">'
							.'<li><a class="button light rename" href="">Renommer</a></li>'
							.'<li><a class="button light move" href="">Déplacer</a></li>'
							.'<li><a class="button light delete" href="">Supprimer</a></li>'
					.'</div>'
				.'</form>';
			}
		}
		else{
			AdminServ::error('Class "Folder" not exists');
		}
		return $out;
	}
	
	
	/**
	* Récupère le template de liste pour la page Maps-order
	*/
	public static function getTemplateMapsOrderList($list){
		$out = null;
		
		if( is_array($list) && count($list) > 0 ){
			foreach($list['lst'] as $id => $map){
				if($list['cid'] != $id){
					$out .= '<li class="ui-state-default">'
						.'<div class="ui-icon ui-icon-arrowthick-2-n-s"></div>'
						.'<div class="order-map-name" title="'.$map['FileName'].'">'.$map['Name'].'</div>'
						.'<div class="order-map-env"><img src="'.$list['cfg']['path_rsc'].'images/env/'.strtolower($map['Environnement']).'.png" alt="" />'.$map['Environnement'].'</div>'
						.'<div class="order-map-author"><img src="'.$list['cfg']['path_rsc'].'images/16/challengeauthor.png" alt="" />'.$map['Author'].'</div>'
					.'</li>';
				}
			}
		}
		
		return $out;
	}
}



/**
* Classe pour le fonctionnement d'AdminServ
*/
abstract class AdminServ {
	
	/**
	* Méthodes de debug
	*/
	public static function dsm($val){
		echo '<pre>';
		print_r($val);
		echo '</pre>';
	}
	public static function debug($val = null){
		$const = get_defined_constants(true);
		return self::dsm( array('ADMINSERV' => $const['user'], 'DEBUG' => $val) );
	}
	
	/**
	* Erreurs et infos
	*/
	public static function error($text = null){
		global $client;
		// Tente de récupérer le message d'erreur du dédié
		if($text === null){
			$text = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
		}
		$GLOBALS['error'] = $text;
	}
	public static function info($text){
		$GLOBALS['info'] = $text;
	}
	
	
	/**
	* Intialise le client du serveur courant
	*
	* @param bool $fullInit -> Intialisation complète ? oui par défaut.
	* Si non, ça ne recupère aucune info de base, seulement la connexion
	* au serveur dédié et son authentication.
	* @return true si réussi, sinon une erreur
	*/
	public static function initialize($fullInit = true){
		global $client;
		
		if( isset($_SESSION['adminserv']) ){
			// CONSTANTS
			define('USER_ADMINLEVEL', $_SESSION['adminserv']['adminlevel']);
			define('SERVER_ID', $_SESSION['adminserv']['sid']);
			define('SERVER_NAME', $_SESSION['adminserv']['name']);
			define('SERVER_ADDR', ServerConfig::$SERVERS[SERVER_NAME]['address']);
			define('SERVER_XMLRPC_PORT', ServerConfig::$SERVERS[SERVER_NAME]['port']);
			define('SERVER_MATCHSET', ServerConfig::$SERVERS[SERVER_NAME]['matchsettings']);
			define('SERVER_ADMINLEVEL', serialize( ServerConfig::$SERVERS[SERVER_NAME]['adminlevel']) );
			
			// CONNEXION
			$client = new IXR_Client_Gbx;
			if( !$client->InitWithIp(SERVER_ADDR, SERVER_XMLRPC_PORT, AdminServConfig::CONNECTION_TIMEOUT) ){
				Utils::redirection(false, './?error='.urlencode('Le serveur n\'est pas accessible.') );
			}
			else{
				if( !self::userAllowedInAdminLevel(SERVER_NAME, USER_ADMINLEVEL) ){
					Utils::redirection(false, '?error='.urlencode('Vous n\êtes pas autorisé dans ce niveau admin.'));
				}
				else{
					if( !$client->query('Authenticate', USER_ADMINLEVEL, $_SESSION['adminserv']['password']) ){
						Utils::redirection(false, '?error='.urlencode('Mauvais mot de passe'));
					}
					else{
						if($fullInit){
							if( !$client->query('GetSystemInfo') ){
								self::error();
							}
							else{
								$serverInfo =  $client->getResponse();
								define('SERVER_LOGIN', $serverInfo['ServerLogin']);
								define('SERVER_PUBLISHED_IP', $serverInfo['PublishedIp']);
								define('SERVER_PORT', $serverInfo['Port']);
								define('SERVER_P2P_PORT', $serverInfo['P2PPort']);
								define('IS_SERVER', $serverInfo['IsServer']);
								define('IS_DEDICATED', $serverInfo['IsDedicated']);
								
								if( !$client->query('IsRelayServer') ){
									self::error();
								}
								else{
									define('IS_RELAY', $client->getResponse() );
									if( !$client->query('GetVersion') ){
										self::error();
									}
									else{
										$getVersion = $client->getResponse();
										define('SERVER_VERSION_NAME', $getVersion['Name']);
										define('SERVER_VERSION', $getVersion['Version']);
										define('SERVER_BUILD', $getVersion['Build']);
										define('API_VERSION', $getVersion['ApiVersion']);
										if(SERVER_VERSION_NAME == 'ManiaPlanet'){ TmNick::$linkProtocol = 'maniaplanet'; }
										define('LINK_PROTOCOL', TmNick::$linkProtocol);
										if( !isset($_SESSION['adminserv_mode']) ){
											define('USER_MODE', 'simple');
										}
										else{
											define('USER_MODE', $_SESSION['adminserv_mode']);
										}
										return true;
									}
								}
							}
						}
						else{
							return true;
						}
					}
				}
			}
		}
		else{
			return 'no session';
		}
	}
	
	
	/**
	* Retourne l'identifiant du serveur dans la config au format int
	*
	* @param string $serverName -> Le nom du serveur dans la config
	* @return int
	*/
	public static function getServerId($serverName){
		$id = 0;
		$servers = ServerConfig::$SERVERS;
		$countServers = count($servers);
		
		// On cherche la position du serveur à partir de son nom
		if( $countServers > 0 ){
			foreach($servers as $server_name => $server_values){
				if($server_name == $serverName){
					break;
				}
				else{
					$id++;
				}
			}
		}
		
		// Si l'id = le nb total de serveur -> pas trouvé
		if($id == $countServers ){
			return -1;
		}else{
			return $id;
		}
	}
	
	
	/**
	* Vérifie si l'ip de l'utilisateur est autorisé dans le niveau admin
	*
	* @param string $serverName -> Le nom du serveur dans la config
	* @param string $level      -> Le niveau admin correspondant à tester
	* @return true si autorisé, sinon false
	*/
	public static function userAllowedInAdminLevel($serverName, $level){
		$userIP = $_SERVER['REMOTE_ADDR'];
		$serverLevel = ServerConfig::$SERVERS[$serverName]['adminlevel'][$level];
		
		// Si la liste est un array
		if( is_array($serverLevel) ){
			// Si l'adresse ip est dans la liste des autorisées
			if( in_array($userIP, $serverLevel) ){
				return true;
			}else{
				return false;
			}
		}
		// Sinon, c'est local ou null
		else{
			// Si c'est null -> autorisé à tous
			if($serverLevel === null){
				return true;
			}
			// Sinon -> autorisé au réseau local
			else{
				// On récupère l'adresse IP du serveur, et on liste les 3 premières valeurs
				$server_ip_list = explode('.', $_SERVER['SERVER_ADDR']);
				$server_ip_substr = $server_ip_list[0].'.'.$server_ip_list[1].'.'.$server_ip_list[2];
				
				// De même pour l'utilisateur
				$user_ip_list = explode('.', $userIP);
				$user_ip_substr = $user_ip_list[0].'.'.$user_ip_list[1].'.'.$user_ip_list[2];
				
				// Si les valeurs sont identiques -> on est dans le réseau local
				if($user_ip_substr == $server_ip_substr){
					return true;
				}else{
					return false;
				}
			}
		}
		return false;
	}
	
	
	/**
	* Vérifie les accès à différent niveau d'admin
	*
	* @param  string $level -> Level minimum à tester
	* @return true si autorisé, sinon false
	*/
	public static function isAdminLevel($level){
		$out = false;
		$adminLevel = $_SESSION['adminserv']['adminlevel'];
		
		if($level == 'User'){
			if($adminLevel == 'SuperAdmin' || $adminLevel == 'Admin' || $adminLevel == 'User'){
				$out = true;
			}
		}
		else if($level == 'Admin'){
			if($adminLevel == 'SuperAdmin' || $adminLevel == 'Admin'){
				$out = true;
			}
		}
		else if($level == 'SuperAdmin'){
			if($adminLevel == 'SuperAdmin'){
				$out = true;
			}
		}
		
		return $out;
	}
	
	
	/**
	* Récupère le nom du game mode
	*
	* @param int $gameMode -> La réponse de GetGameMode()
	* @return string
	*/
	public static function getGameModeName($gameMode){
		$out = null;
		
		// On vérifie qu'une configuration existe
		if( class_exists('ExtensionConfig') ){
			
			// Si la configuration contient au moins 1 mode de jeu
			if( isset(ExtensionConfig::$GAMEMODES) && count(ExtensionConfig::$GAMEMODES) > 0 ){
				$out = ExtensionConfig::$GAMEMODES[$gameMode];
			}
			else{
				$out = -1;
			}
		}
		else{
			$out = -1;
		}
		
		// Retour
		if($out === -1){
			$out = 'Aucun mode de jeu disponible';
		}
		return $out;
	}
	
	
	/**
	*
	*/
	public static function isTeamGameMode($currentGameMode){
		// ID du mode
		if(SERVER_VERSION_NAME == 'TmForever'){
			$teamModeId = 2;
		}else{
			$teamModeId = 3;
		}
		
		if($currentGameMode == $teamModeId){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	* Récupère l'extension d'un fichier Nadeo
	*
	* @param string $filename -> Le fichier à extraire l'extension
	* @return string "map.gbx"
	*/
	public static function getNadeoExtension($filename){
		$out = null;
		$filenameEx = explode('.', $filename);
		
		if( count($filenameEx) > 2 ){
			$ext = $filenameEx[count($filenameEx)-2];
			$ext .= '.'.$filenameEx[count($filenameEx)-1];
			$out = strtolower($ext);
		}
		
		return $out;
	}
	
	
	/**
	* Récupère les informations du serveur actuel (map, serveur, stats, joueurs)
	*
	* @global resource $client -> Le client doit être initialisé
	* @return array
	*/
	public static function getCurrentServerInfo($sortBy = null){
		global $client;
		$out = array();
		
		if( $client->query('GetCurrentMapInfo') ){
			// CurrentMapInfo
			$currentMapInfo = $client->getResponse();
			$out['map']['name'] = TmNick::toHtml($currentMapInfo['Name'], 10, true, false, '#999');
			$out['map']['uid'] = $currentMapInfo['UId'];
			$out['map']['author'] = $currentMapInfo['Author'];
			$out['map']['enviro'] = $currentMapInfo['Environnement'];
			
			// MapThumbnail
			if( self::isAdminLevel('Admin') ){
				$client->query('GetMapsDirectory');
				$mapsDirectory = $client->getResponse();
				$Gbx = new GBXChallengeFetcher($mapsDirectory.$currentMapInfo['FileName'], true, true);
				$out['map']['thumb'] = base64_encode($Gbx->thumbnail);
			}
			else{
				$out['map']['thumb'] = null;
			}
			
			// GameMode
			$client->query('GetGameMode');
			$out['srv']['gameModeId'] = $client->getResponse();
			$out['srv']['gameModeName'] = self::getGameModeName($out['srv']['gameModeId']);
			
			// TeamScores
			if( self::isTeamGameMode($out['srv']['gameModeId']) ){
				$client->query('GetCurrentRanking', 2, 0);
				$currentRanking = $client->getResponse();
				$out['map']['scores']['blue'] = $currentRanking[0]['Score'];
				$out['map']['scores']['red'] = $currentRanking[1]['Score'];
			}
			
			// ServerName
			$client->query('GetServerName');
			$out['srv']['name'] = TmNick::toHtml($client->getResponse(), 10, true, false, '#999');
			
			// Status
			$client->query('GetStatus');
			$status = $client->getResponse();
			$out['srv']['status'] = $status['Name'];
			
			// NetworkStats
			if( self::isAdminLevel('SuperAdmin') ){
				$client->query('GetNetworkStats');
				$networkStats = $client->getResponse();
				$out['net']['uptime'] = TimeDate::secToStringTime($networkStats['Uptime'], false);
				$out['net']['nbrconnection'] = $networkStats['NbrConnection'];
				$out['net']['meanconnectiontime'] = TimeDate::secToStringTime($networkStats['MeanConnectionTime'], false);
				$out['net']['meannbrplayer'] = $networkStats['MeanNbrPlayer'];
				$out['net']['recvnetrate'] = $networkStats['RecvNetRate'];
				$out['net']['sendnetrate'] = $networkStats['SendNetRate'];
				$out['net']['totalreceivingsize'] = $networkStats['TotalReceivingSize'];
				$out['net']['totalsendingsize'] = $networkStats['TotalSendingSize'];
			}
			else{
				$out['net'] = null;
			}
			
			// PlayerList
			$client->query('GetPlayerList', AdminServConfig::LIMIT_PLAYERS_LIST, 0);
			$playerList = $client->getResponse();
			$countPlayerList = count($playerList);
			
			if( $countPlayerList > 0 ){
				$i = 0;
				foreach($playerList as $player){
					// Nickname et Playerlogin
					$out['ply'][$i]['NickName'] = TmNick::toHtml(htmlspecialchars($player['NickName'], ENT_QUOTES, 'UTF-8'), 10, true);
					$out['ply'][$i]['Login'] = $player['Login'];
					
					// PlayerStatus
					if($player['IsSpectator'] != 0){ $playerStatus = 'Spectateur'; }else{ $playerStatus = 'Joueur'; }
					$out['ply'][$i]['PlayerStatus'] = $playerStatus;
					
					// Autres
					$out['ply'][$i]['PlayerId'] = $player['PlayerId'];
					$out['ply'][$i]['TeamId'] = $player['TeamId'];
					if($player['TeamId'] == 0){ $teamName = Utils::t('Blue'); }else if($player['TeamId'] == 1){ $teamName = Utils::t('Red'); }else{ $teamName = Utils::t('Spectator'); }
					$out['ply'][$i]['TeamName'] = $teamName;
					$out['ply'][$i]['IsSpectator'] = $player['IsSpectator'];
					$out['ply'][$i]['IsInOfficialMode'] = $player['IsInOfficialMode'];
					$out['ply'][$i]['LadderRanking'] = $player['LadderRanking'];
					$i++;
				}
			}
			else{
				$out['ply'] = 'Aucun joueur';
			}
			
			// Nombre de joueurs
			if($countPlayerList > 1){
				$out['nbp'] = $countPlayerList.' joueurs';
			}
			else{
				$out['nbp'] = $countPlayerList.' joueur';
			}
			
			// Config
			$out['cfg']['path_rsc'] = AdminServConfig::PATH_RESSOURCES;
			
			
			// TRI
			if( is_array($out['ply']) && count($out['ply']) > 0 ){
				if( self::isTeamGameMode($out['srv']['gameModeId']) ){
					// Si on est en mode équipe, on force en mode détail et on tri par équipe
					$_SESSION['adminserv_mode'] = 'detail';
					uasort($out['ply'], 'AdminServSort::sortByTeam');
				}
				else{
					if($sortBy != null){
						switch($sortBy){
							case 'nickname':
								uasort($out['ply'], 'AdminServSort::sortByNickName');
								break;
							case 'ladder':
								uasort($out['ply'], 'AdminServSort::sortByLadderRanking');
								break;
							case 'login':
								uasort($out['ply'], 'AdminServSort::sortByLogin');
								break;
							case 'status':
								uasort($out['ply'], 'AdminServSort::sortByStatus');
								break;
						}
					}
				}
			}
		}
		else{
			$out['error'] = 'client not initialized';
		}
		
		return $out;
	}
	
	
	/**
	* Administration rapide (restart, next, endround)
	*
	* @global resource $client -> Le client doit être initialisé
	* @param  string   $cmd    -> Le nom de la méthode ManiaPlanet à utiliser
	* @return null si réussi, sinon un message d'erreur
	*/
	public static function speedAdmin($cmd){
		global $client;
		$out = null;
		
		// Méthode en fonction du jeu
		if($cmd != 'ForceEndRound'){
			if(SERVER_VERSION_NAME == 'TmForever'){
				$methodRestart = 'RestartChallenge';
				$methodNext = 'NextChallenge';
			}else{
				$methodRestart = 'RestartMap';
				$methodNext = 'NextMap';
			}
		}
		
		// Suivant la commande demandée
		if($cmd == 'RestartMap'){
			if( !$client->query($methodRestart) ){
				$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
			}
		}
		else if($cmd == 'NextMap'){
			if( !$client->query($methodNext) ){
				$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
			}
		}
		else if($cmd == 'ForceEndRound'){
			if( !$client->query('ForceEndRound') ){
				$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
			}
		}
		else{
			$out = 'command not recognized';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère les lignes du chat serveur
	*
	* @param bool $hideServerLines -> Masquer les lignes provenant d'un gestionnaire de serveur
	* @return string
	*/
	public static function getChatServerLines($hideServerLines = false){
		global $client;
		$out = null;
		
		// ChatLines
		if( !$client->query('GetChatLines') ){
			$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
		}
		else{
			$chatLines = $client->getResponse();
			foreach($chatLines as $line){
				// On masque les lignes du serveur si c'est demandé
				if($hideServerLines == true){
					$line = self::clearChatServerLine($line);
				}
				
				// TODO : On traduit le texte
				/*if($i18n == 'fr'){
					if($line == '$99FThis is a draw round.'){ $line = 'Match nul.'; }
					if($line == '$99FThe $<$00FBlue team$> wins this round.'){ $line = 'L\'équipe bleue remporte ce tour.'; }
					if($line == '$99FThe $<$F00Red team$> wins this round.'){ $line = 'L\'équipe rouge remporte ce tour.'; }
				}*/
				
				// On enlève les codes nadeo $s, $o, $w, etc
				$line = TmNick::stripNadeoCode($line);
				$line = str_replace('$>', '$z', $line);
				$line = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
				
				// Affichage des lignes
				if($line != null){
					// Convertie les codes nadeo restant en html
					$out .= TmNick::toHtml($line, 10, false, true, '#666');
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Masque les lignes générées par le gestionnaire de serveur
	*
	* @param string $line -> La ligne de la réponse GetChatLines
	* @return string
	*/
	public static function clearChatServerLine($line){
		$char = substr(utf8_decode($line), 0, 1);
		if($char == '[' || $char == '/' || substr($line, 0, 11) == '$99F[Admin]' || substr($line, 0, 12) == 'Invalid time' || $char == '?'){
			return $line;
		}
	}
	
	
	/**
	* Extrait les données d'un MatchSettings et renvoi un tableau
	*
	* @param string $filename -> L'url du MatchSettings
	* @return array si le fichier existe, sinon false
	*/
	public static function getMatchSettingsData($filename){
		if( @file_exists($filename) ){
			if( !$xml = @simplexml_load_file($filename) ){
				return false;
			}
		}else{
			$xml = null;
			return false;
		}
		if($xml){
			/** Récuperation des valeurs du MatchSettings **/
			$matchsettings = array();
			// Gameinfos
			foreach($xml->gameinfos as $gameinfos){
				$matchsettings['gameinfos']['game_mode'][] = (string)$gameinfos->game_mode;
				$matchsettings['gameinfos']['chat_time'][] = (string)$gameinfos->chat_time;
				$matchsettings['gameinfos']['finishtimeout'][] = (string)$gameinfos->finishtimeout;
				$matchsettings['gameinfos']['allwarmupduration'][] = (string)$gameinfos->allwarmupduration;
				$matchsettings['gameinfos']['disablerespawn'][] = (string)$gameinfos->disablerespawn;
				$matchsettings['gameinfos']['forceshowallopponents'][] = (string)$gameinfos->forceshowallopponents;
				$matchsettings['gameinfos']['rounds_pointslimit'][] = (string)$gameinfos->rounds_pointslimit;
				$matchsettings['gameinfos']['rounds_usenewrules'][] = (string)$gameinfos->rounds_usenewrules;
				$matchsettings['gameinfos']['rounds_forcedlaps'][] = (string)$gameinfos->rounds_forcedlaps;
				$matchsettings['gameinfos']['rounds_pointslimitnewrules'][] = (string)$gameinfos->rounds_pointslimitnewrules;
				$matchsettings['gameinfos']['team_pointslimit'][] = (string)$gameinfos->team_pointslimit;
				$matchsettings['gameinfos']['team_maxpoints'][] = (string)$gameinfos->team_maxpoints;
				$matchsettings['gameinfos']['team_usenewrules'][] = (string)$gameinfos->team_usenewrules;
				$matchsettings['gameinfos']['team_pointslimitnewrules'][] = (string)$gameinfos->team_pointslimitnewrules;
				$matchsettings['gameinfos']['timeattack_limit'][] = (string)$gameinfos->timeattack_limit;
				$matchsettings['gameinfos']['timeattack_synchstartperiod'][] = (string)$gameinfos->timeattack_synchstartperiod;
				$matchsettings['gameinfos']['laps_nblaps'][] = (string)$gameinfos->laps_nblaps;
				$matchsettings['gameinfos']['laps_timelimit'][] = (string)$gameinfos->laps_timelimit;
				$matchsettings['gameinfos']['cup_pointslimit'][] = (string)$gameinfos->cup_pointslimit;
				$matchsettings['gameinfos']['cup_roundsperchallenge'][] = (string)$gameinfos->cup_roundsperchallenge;
				$matchsettings['gameinfos']['cup_nbwinners'][] = (string)$gameinfos->cup_nbwinners;
				$matchsettings['gameinfos']['cup_warmupduration'][] = (string)$gameinfos->cup_warmupduration;
			}
			// Hotseat
			foreach($xml->hotseat as $hotseat){
				$matchsettings['hotseat']['game_mode'][] = (string)$hotseat->game_mode;
				$matchsettings['hotseat']['time_limit'][] = (string)$hotseat->time_limit;
				$matchsettings['hotseat']['rounds_count'][] = (string)$hotseat->rounds_count;
			}
			// Filter
			foreach($xml->filter as $filter){
				$matchsettings['filter']['is_lan'][] = (string)$filter->is_lan;
				$matchsettings['filter']['is_internet'][] = (string)$filter->is_internet;
				$matchsettings['filter']['is_solo'][] = (string)$filter->is_solo;
				$matchsettings['filter']['is_hotseat'][] = (string)$filter->is_hotseat;
				$matchsettings['filter']['sort_index'][] = (string)$filter->sort_index;
				$matchsettings['filter']['random_map_order'][] = (string)$filter->random_map_order;
				$matchsettings['filter']['force_default_gamemode'][] = (string)$filter->force_default_gamemode;
			}
			// Challenges
			$matchsettings['startindex'] = (string)$xml->startindex;
			foreach($xml->challenge as $challenge){
				$matchsettings['challenge'][(string)$challenge->file][] = (string)$challenge->ident;
			}
			/** Création du tableau de sortie **/
			if( isset($matchsettings) ){
				$out = array();
				// Gameinfos
				if( isset($matchsettings['gameinfos']) ){
					foreach($matchsettings['gameinfos'] as $key => $values){
						$out['gameinfos'][$key] = $matchsettings['gameinfos'][$key][0];
					}
				}
				// Hotseat
				if( isset($matchsettings['hotseat']) ){
					foreach($matchsettings['hotseat'] as $key => $values){
						$out['hotseat'][$key] = $matchsettings['hotseat'][$key][0];
					}
				}
				// Filter
				if( isset($matchsettings['filter']) ){
					foreach($matchsettings['filter'] as $key => $values){
						$out['filter'][$key] = $matchsettings['filter'][$key][0];
					}
				}
				// Challenges
				$out['startindex'] = $matchsettings['startindex'];
				if( isset($matchsettings['challenge']) ){
					foreach($matchsettings['challenge'] as $challenge_key => $challenge_values){
						$out['challenge'][$challenge_key] = $challenge_values[0];
					}
				}
				return $out;
			}else{
				return false;
			}
		}
	}
	
	
	/**
	* Création d'un MatchSettings
	*
	* @param string $filename -> L'url du dossier dans lequel le MatchSettings sera crée
	* @param array  $struct   -> La structure du MatchSettings avec ses données
	* $struct = Array
	* (
	*  [gameinfos] => Array
	*   (
	*    [game_mode] => 0
	*    etc...
	*   )
	*  [hotseat] => Array()
	*  [filter] => Array()
	*  [startindex] => 1
	*  [challenge] => Array
	*   (
	*    [name.Challenge.Gbx] => 8bDoQMwzUllV0D9eu7hSth3rQs6
	*    etc...
	*   )
	* )
	* @return true si le MatchSettings a été crée, sinon false
	*/
	public static function createMatchSettings($filename, $struct){
		// Génération du XML
		$matchSettings = '<?xml version="1.0" encoding="utf-8" ?>'."\n"
		."<playlist>\n";
			// Gameinfos
			if($struct['gameinfos']){
				$matchSettings .= "\t<gameinfos>\n";
					foreach($struct['gameinfos'] as $name => $data){
						$matchSettings .= "\t\t<$name>$data</$name>\n";
					}
				$matchSettings .= "\t</gameinfos>\n\n";
			}
			// Hotseat
			if($struct['hotseat']){
				$matchSettings .= "\t<hotseat>\n";
					foreach($struct['hotseat'] as $name => $data){
						$matchSettings .= "\t\t<$name>$data</$name>\n";
					}
				$matchSettings .= "\t</hotseat>\n\n";
			}
			// Filter
			if($struct['filter']){
				$matchSettings .= "\t<filter>\n";
					foreach($struct['filter'] as $name => $data){
						$matchSettings .= "\t\t<$name>$data</$name>\n";
					}
				$matchSettings .= "\t</filter>\n\n";
			}
			// Challenges
			$matchSettings .= "\t<startindex>".$struct['startindex']."</startindex>\n";
			if($struct['challenge']){
				foreach($struct['challenge'] as $file => $ident){
					$matchSettings .= "\t<challenge>\n"
						."\t\t<file>$file</file>\n"
						."\t\t<ident>$ident</ident>\n"
					."\t</challenge>\n";
				}
			}
		$matchSettings .= "</playlist>\n";
		// Création XML
		if( @!$newXMLObject = simplexml_load_string($matchSettings) ){
			return false;
		}
		if( !$newXMLObject->asXML($filename) ){
			return false;
		}else{
			return true;
		}
	}
	
	
	/**
	* Extrait les données d'une playlist (blacklist ou guestlist) et renvoi un tableau
	*
	* @param string $filename -> L'url de la playlist
	* @return array si le fichier existe, sinon false
	*/
	public static function getPlaylistData($filename){
		if( @file_exists($filename) ){
			if( !$xml = @simplexml_load_file($filename) ){
				return false;
			}
		}else{
			return false;
		}
		$playlist = array();
		$playlist['type'] = @$xml->getName();
		foreach($xml->player as $player){
			$playlist['logins'][] = (string)$player->login;
		}
		return $playlist;
	}
	
	
	/**
	* Récupère le chemin du dossier "Maps"
	*
	* @global resource $client -> Le client doit être initialisé
	* @return string
	*/
	public static function getMapsDirectoryPath(){
		global $client;
		$out = null;
		
		// Version
		if(SERVER_VERSION_NAME == 'TmForever'){
			$queryName = 'GetTracksDirectory';
		}
		else{
			$queryName = 'GetMapsDirectory';
		}
		
		// Requête
		if( !$client->query($queryName) ){
			$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
		}
		else{
			$out = Str::toSlash( $client->getResponse() );
			if( substr($out, -1, 1) != '/'){ $out = $out.'/'; }
		}
		return $out;
	}
	
	
	/**
	* Récupère la liste des maps sur le serveur
	*
	* @global resource $client -> Le client doit être initialisé
	* @return array
	*/
	public static function getMapList($sortBy = null){
		global $client;
		$out = array();
		
		// Méthodes
		if(SERVER_VERSION_NAME == 'TmForever'){
			$methodeMapList = 'GetChallengeList';
			$methodeMapIndex = 'GetCurrentChallengeIndex';
		}
		else{
			$methodeMapList = 'GetMapList';
			$methodeMapIndex = 'GetCurrentMapIndex';
		}
		
		// MAPSLIST
		if( $client->query($methodeMapList, AdminServConfig::LIMIT_MAPS_LIST, 0) ){
			$mapList = $client->getResponse();
			$countMapList = count($mapList);
			$client->query($methodeMapIndex);
			$out['cid'] = $client->getResponse();
			
			if( $countMapList > 0 ){
				$i = 0;
				foreach($mapList as $map){
					// Name
					$name = htmlspecialchars($map['Name'], ENT_QUOTES, 'UTF-8');
					$out['lst'][$i]['Name'] = TmNick::toHtml($name, 10, true);
					
					// Environnement
					$env = $map['Environnement'];
					if($env == 'Speed'){ $env = 'Desert'; }else if($env == 'Alpine'){ $env = 'Snow'; }
					$out['lst'][$i]['Environnement'] = $env;
					
					// Autres
					$out['lst'][$i]['UId'] = $map['UId'];
					$out['lst'][$i]['FileName'] = $map['FileName'];
					$out['lst'][$i]['Author'] = $map['Author'];
					$out['lst'][$i]['GoldTime'] = TimeDate::format($map['GoldTime']);
					$out['lst'][$i]['CopperPrice'] = $map['CopperPrice'];
					$i++;
				}
			}
			else{
				$out['lst'] = 'Aucune map';
			}
			
			// Nombre de maps
			if($countMapList > 1){
				$out['nbm'] = $countMapList.' maps';
			}
			else{
				$out['nbm'] = $countMapList.' map';
			}
			
			// Config
			$out['cfg']['path_rsc'] = AdminServConfig::PATH_RESSOURCES;
			
			
			// TRI
			if($sortBy != null){
				if( is_array($out['lst']) && count($out['lst']) > 0 ){
					switch($sortBy){
						case 'name':
							uasort($out['lst'], 'AdminServSort::sortByName');
							break;
						case 'env':
							uasort($out['lst'], 'AdminServSort::sortByEnviro');
							break;
						case 'author':
							uasort($out['lst'], 'AdminServSort::sortByAuthor');
							break;
						case 'goldtime':
							uasort($out['lst'], 'AdminServSort::sortByGoldTime');
							break;
						case 'cost':
							uasort($out['lst'], 'AdminServSort::sortByPrice');
							break;
					}
				}
				$out['lst'] = array_values($out['lst']);
			}
		}
		else{
			$out['error'] = 'client not initialized';
		}
		
		return $out;
	}
	
	
	public static function getLocalMapList($path){
		$out = array();
		
		if( class_exists('Folder') && class_exists('GBXChallengeFetcher') ){
			$directory = Folder::read($path, AdminServConfig::$MAPS_HIDDEN_FOLDERS, AdminServConfig::$MAPS_HIDDEN_FILES, AdminServConfig::RECENT_STATUS_PERIOD);
			if( is_array($directory) ){
				$countMapList = count($directory['files']);
				if($countMapList > 0){
					$i = 0;
					foreach($directory['files'] as $file => $values){
						if( in_array(self::getNadeoExtension($file), AdminServConfig::$MAP_EXTENSION) ){
							// Données
							$Gbx = new GBXChallengeFetcher($path.$file, true);
							
							// Name
							$name = htmlspecialchars($Gbx->name, ENT_QUOTES, 'UTF-8');
							$out['lst'][$i]['Name'] = TmNick::toHtml($name, 10, true);
							
							// Environnement
							$env = $Gbx->envir;
							if($env == 'Speed'){ $env = 'Desert'; }else if($env == 'Alpine'){ $env = 'Snow'; }
							$out['lst'][$i]['Environnement'] = $env;
							
							// Autres
							$out['lst'][$i]['FileName'] = $file;
							$out['lst'][$i]['Author'] = $Gbx->author;
							$out['lst'][$i]['Recent'] = $values['recent'];
							$i++;
						}
						else{
							if( !isset($out['lst']) ){
								$out['lst'] = 'Aucune map';
							}
						}
					}
				}
				else{
					$out['lst'] = 'Aucune map';
				}
				
				// Nombre de maps
				if( is_array($out['lst']) ){
					if( count($out['lst']) > 1){
					$out['nbm'] = $countMapList.' maps';
					}
					else{
						$out['nbm'] = $countMapList.' map';
					}
				}
				else{
					$out['nbm'] = '0 map';
				}
				
				// Config
				$out['cfg']['path_rsc'] = AdminServConfig::PATH_RESSOURCES;
			}
			else{
				// Retour des erreurs de la méthode read
				$out = $directory;
			}
		}
		else{
			$out['error'] = 'class "Folder" or "GBXChallengeFetcher" not found';
		}
		
		return $out;
	}
	
	
	public static function getLocalMatchSettingList($path){
		$out = array();
		
		if( class_exists('Folder') && class_exists('File') ){
			$directory = Folder::read($path, AdminServConfig::$MATCHSET_HIDDEN_FOLDERS, AdminServConfig::$MATCHSET_HIDDEN_FILES, AdminServConfig::RECENT_STATUS_PERIOD);
			if( is_array($directory) ){
				$countMatchsetList = count($directory['files']);
				if($countMatchsetList > 0){
					$i = 0;
					foreach($directory['files'] as $file => $values){
						if( in_array(File::getExtension($file), AdminServConfig::$MATCHSET_EXTENSION) ){
							// Données
							//TOTO: extraire les données pour le nb de maps
							
							$out['lst'][$i]['Name'] = substr($file, 0, -4);
							$out['lst'][$i]['FileName'] = $file;
							$out['lst'][$i]['Mtime'] = $values['mtime'];
							$out['lst'][$i]['Recent'] = $values['recent'];
							$i++;
						}
						else{
							if( !isset($out['lst']) ){
								$out['lst'] = 'Aucun matchsetting';
							}
						}
					}
				}
				else{
					$out['lst'] = 'Aucun matchsetting';
				}
				
				// Nombre de maps
				if( is_array($out['lst']) ){
					if( count($out['lst']) > 1){
					$out['nbm'] = $countMatchsetList.' matchsettings';
					}
					else{
						$out['nbm'] = $countMatchsetList.' matchsetting';
					}
				}
				else{
					$out['nbm'] = '0 matchsetting';
				}
				
				// Config
				$out['cfg']['path_rsc'] = AdminServConfig::PATH_RESSOURCES;
			}
			else{
				// Retour des erreurs de la méthode read
				$out = $directory;
			}
		}
		else{
			$out['error'] = 'class "Folder" or "File" not found';
		}
		
		return $out;
	}
}



/**
* Classe pour le traitement des tris AdminServ
*/
abstract class AdminServSort {
	
	/* General */
	public static function sortByNickName($a, $b){
		// Modification
		$a['NickName'] = TmNick::toText($a['NickName']);
		$b['NickName'] = TmNick::toText($b['NickName']);
		
		// Comparaison
		if($a['NickName'] == $b['NickName']){
			return 0;
		}
		if($a['NickName'] < $b['NickName']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByLadderRanking($a, $b){
		if($a['LadderRanking'] == $b['LadderRanking']){
			return 0;
		}
		if($a['LadderRanking'] < $b['LadderRanking']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByLogin($a, $b){
		if($a['Login'] == $b['Login']){
			return 0;
		}
		if($a['Login'] < $b['Login']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByStatus($a, $b){
		if($a['IsSpectator'] == $b['IsSpectator']){
			return 0;
		}
		if($a['IsSpectator'] < $b['IsSpectator']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByTeam($a, $b){
		// Modification
		if($a['TeamId'] == 0){
			$a['TeamId'] = 'blue';
		}else if($a['TeamId'] == 1){
			$a['TeamId'] = 'red';
		}else{
			$a['TeamId'] = 'spectator';
		}
		if($b['TeamId'] == 0){
			$b['TeamId'] = 'blue';
		}else if($b['TeamId'] == 1){
			$b['TeamId'] = 'red';
		}else{
			$b['TeamId'] = 'spectator';
		}
		
		// Comparaison
		if($a['TeamId'] == $b['TeamId']){
			return 0;
		}
		if($a['TeamId'] < $b['TeamId']){
			return -1;
		}else{
			return 1;
		}
	}
	
	/* Maps-list */
	public static function sortByName($a, $b){
		// Modification
		$a['Name'] = TmNick::toText($a['Name']);
		$b['Name'] = TmNick::toText($b['Name']);
		
		// Comparaison
		if($a['Name'] == $b['Name']){
			return 0;
		}
		if($a['Name'] < $b['Name']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByEnviro($a, $b){
		// Modification
		if($a['Environnement'] == 'Speed'){
			$a['Environnement'] = 'Desert';
		}
		if($b['Environnement'] == 'Speed'){
			$b['Environnement'] = 'Desert';
		}
		if($a['Environnement'] == 'Alpine'){
			$a['Environnement'] = 'Snow';
		}
		if($b['Environnement'] == 'Alpine'){
			$b['Environnement'] = 'Snow';
		}
		
		// Comparaison
		if($a['Environnement'] == $b['Environnement']){
			return 0;
		}
		if($a['Environnement'] < $b['Environnement']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByAuthor($a, $b){
		if($a['Author'] == $b['Author']){
			return 0;
		}
		if($a['Author'] < $b['Author']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByGoldTime($a, $b){
		if($a['GoldTime'] == $b['GoldTime']){
			return 0;
		}
		if($a['GoldTime'] < $b['GoldTime']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByPrice($a, $b){
		if($a['CopperPrice'] == $b['CopperPrice']){
			return 0;
		}
		if($a['CopperPrice'] < $b['CopperPrice']){
			return -1;
		}else{
			return 1;
		}
	}
}
?>