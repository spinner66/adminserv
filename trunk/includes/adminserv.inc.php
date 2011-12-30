<?php

/**
* Classe pour l'interface d'AdminServ
*/
abstract class AdminServTemplate {
	
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
	* Récupère la liste des thèmes
	*/
	public static function getThemeList(){
		$i = 0;
		$out = null;
		$count_themes = count(ExtensionConfig::$THEMES);
		
		if($count_themes > 0){
			$out .= '<ul>';
			foreach(ExtensionConfig::$THEMES as $name => $color){
				if($i == 0){ $class = ' class="first"'; }
				else if($i == $count_themes-1){ $class = ' class="last"'; }
				else{ $class = null; }
				$out .= '<li'.$class.'><a class="theme-color" style="background-color: '.$color.';" href=""></a></li>';
				$i++;
			}
			$out .= '</ul>';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des langues
	*/
	public static function getLangList(){
		$i = 0;
		$out = null;
		$count_lang = count(ExtensionConfig::$LANG);
		
		if($count_lang > 0){
			$out .= '<ul>';
			foreach(ExtensionConfig::$LANG as $code){
				if($i == 0){ $class = ' class="first"'; }
				else if($i == $count_lang-1){ $class = ' class="last"'; }
				else{ $class = null; }
				$out .= '<li'.$class.'><a class="lang-flag" style="background-image: url('. AdminServConfig::PATH_RESSOURCES .'images/lang/'.$code.'.png);" href=""></a></li>';
				$i++;
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
		require_once __DIR__ .'/class/filefolder.class.php';
	}
	
	
	/**
	* Récupère le header/footer du site
	*/
	public static function getHeader(){
		global $page_title, $body_class;
		
		// Classes CSS body
		if( defined('SERVER_NAME') ){
			$page_title = SERVER_NAME;
			$body_class = ' not-front';
		}
		else{
			$body_class = ' front';
		}
		$body_class .= ' section-'.USER_PAGE;
		$body_class = trim($body_class);
		
		require_once __DIR__ .'/header.inc.php';
	}
	public static function getFooter(){
		require_once __DIR__ .'/footer.inc.php';
	}
	
	/**
	* Récupère le CSS/JS du site
	*/
	public static function getCss($path = AdminServConfig::PATH_RESSOURCES){
		return ''
		.'<link type="text/css" rel="stylesheet" media="screen" href="'. $path .'styles/global.css" />';
	}
	public static function getJS($path = AdminServConfig::PATH_INCLUDES){
		return '<script type="text/javascript" src="'. $path .'js/jquery.js"></script>'
		.'<script type="text/javascript" src="'. $path .'js/functions.js"></script>'
		.'<script type="text/javascript" src="'. $path .'js/adminserv.js"></script>';
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
			if( isset(ServerConfig::$SERVERS) && count(ServerConfig::$SERVERS) > 0 && !isset(ServerConfig::$SERVERS['new server name']) ){
				
				// Id du serveur utilisé dernièrement
				$currentServerId = Utils::readCookieData('adminserv', 0);
				
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
	* @return string
	*/
	public static function getGameModeList($currentGameMode){
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
	* Intialise le client du serveur courant
	*
	* @param bool $full_init -> Intialisation complète ? oui par défaut.
	* Si non, ça ne recupère aucune info de base, seulement la connexion
	* au serveur dédié et son authentication.
	* @return true si réussi, sinon une erreur
	*/
	public static function initialize($full_init = true){
		global $client;
		
		if( isset($_SESSION['adminserv']) ){
			// CONSTANTS
			if( isset($_GET['p']) ){ define('USER_PAGE', htmlspecialchars($_GET['p']) ); }else{ define('USER_PAGE', 'index'); }
			define('USER_ADMINLEVEL', $_SESSION['adminserv']['adminlevel']);
			define('SERVER_ID', $_SESSION['adminserv']['sid']);
			define('SERVER_NAME', $_SESSION['adminserv']['name']);
			define('SERVER_ADDR', ServerConfig::$SERVERS[SERVER_NAME]['address']);
			define('SERVER_XMLRPC_PORT', ServerConfig::$SERVERS[SERVER_NAME]['port']);
			define('SERVER_MATCHSET', ServerConfig::$SERVERS[SERVER_NAME]['matchsettings']);
			define('SERVER_ADMINLEVEL', serialize( ServerConfig::$SERVERS[SERVER_NAME]['adminlevel']) );
			
			// CONNEXION
			$client = new IXR_Client_Gbx;
			if( !$client->InitWithIp(SERVER_ADDR, SERVER_XMLRPC_PORT) ){
				Utils::redirection(false, '?error='.urlencode('Le serveur n\'est pas accessible.') );
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
						if($full_init){
							if( !$client->query('GetSystemInfo') ){
								return '['.$client->getErrorCode().'] '.$client->getErrorMessage();
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
									return '['.$client->getErrorCode().'] '.$client->getErrorMessage();
								}
								else{
									define('IS_RELAY', $client->getResponse() );
									if( !$client->query('GetVersion') ){
										return '['.$client->getErrorCode().'] '.$client->getErrorMessage();
									}
									else{
										$getVersion = $client->getResponse();
										define('SERVER_VERSION_NAME', $getVersion['Name']);
										define('SERVER_VERSION', $getVersion['Version']);
										define('SERVER_BUILD', $getVersion['Build']);
										define('API_VERSION', $getVersion['ApiVersion']);
										if(SERVER_VERSION_NAME == 'ManiaPlanet'){ TmNick::$linkProtocol = 'maniaplanet'; }
										define('LINK_PROTOCOL', TmNick::$linkProtocol);
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
		
		// On cherche la position du serveur à partir de son nom
		foreach($servers as $server_name => $server_values){
			if($server_name == $serverName){
				break;
			}
			else{
				$id++;
			}
		}
		
		// Si l'id = le nb total de serveur -> pas trouvé
		if($id == count($servers) ){
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
			if($serverLevel == null){
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
	* Récupère les informations du serveur actuel (map, serveur, stats, joueurs)
	*
	* @global resource $client -> Le client doit être initialisé
	* @return array
	*/
	public static function getCurrentServerInfo(){
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
			$out['srv']['game_mode'] = self::getGameModeName( $client->getResponse() );
			
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
					$nickname = htmlspecialchars($player['NickName'], ENT_QUOTES, 'UTF-8');
					$out['ply'][$i]['NickName'] = TmNick::toHtml($nickname, 10, true);
					$out['ply'][$i]['Login'] = $player['Login'];
					
					// PlayerStatus
					if($player['IsSpectator'] != 0){ $playerStatus = 'Spectateur'; }else{ $playerStatus = 'Joueur'; }
					$out['ply'][$i]['PlayerStatus'] = $playerStatus;
					
					// Autres
					$out['ply'][$i]['PlayerId'] = $player['PlayerId'];
					$out['ply'][$i]['TeamId'] = $player['TeamId'];
					if($player['TeamId'] == 0){ $teamName = 'Blue'; }else if($player['TeamId'] == 1){ $teamName = 'Red'; }else{ $teamName = 'Spectator'; }
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
			if(SERVER_VERSION_NAME == 'ManiaPlanet'){
				$methodRestart = 'RestartMap';
				$methodNext = 'NextMap';
			}else{
				$methodRestart = 'RestartChallenge';
				$methodNext = 'NextChallenge';
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
}
?>