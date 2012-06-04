<?php
abstract class DisplayServ {
	
	/**
	*
	*/
	public static function getServerConfig(){
		// Fichier à prendre
		if(DisplayServConfig::USE_ADMINSERV_SERVER_CONFIG !== null){
			$filename = DisplayServConfig::USE_ADMINSERV_SERVER_CONFIG;
		}else{
			$filename = 'config/servers.cfg.php';
		}
		
		if( file_exists($filename) ){
			require_once $filename;
		}
	}
	
	
	/**
	* Récupère et inclue les classes PHP
	*/
	public static function getClass(){
		require_once __DIR__ .'/class/GbxRemote.inc.php';
		require_once __DIR__ .'/class/tmnick.class.php';
	}
	
	
	/**
	* Récupère le CSS/JS du site
	*/
	public static function getHeadFiles(){
		// CSS
		$out = '<link rel="stylesheet" href="'. DisplayServConfig::PATH_RESSOURCES .'styles/displayserv.css" />';
		if( defined('USER_THEME') ){
			if( file_exists(DisplayServConfig::PATH_RESSOURCES .'styles/'. USER_THEME .'.css') ){
					$out .= '<link rel="stylesheet" href="'. DisplayServConfig::PATH_RESSOURCES .'styles/'. USER_THEME .'.css" />';
			}
		}
		
		// JS
		$out .= '<script src="'. DisplayServConfig::PATH_INCLUDES .'js/jquery.js"></script>'
		.'<script src="'. DisplayServConfig::PATH_INCLUDES .'js/displayserv.js"></script>';
		
		return $out;
	}
	
	
	/**
	* Intialise le client du serveur courant
	*
	* @return true si réussi, sinon une erreur
	*/
	public static function initialize(){
		global $client;
		
		// CONSTANTS
		define('SERVER_SID', $_GET['sid']);
		define('SERVER_NAME', self::getServerName(SERVER_SID) );
		define('SERVER_ADDR', ServerConfig::$SERVERS[SERVER_NAME]['address']);
		define('SERVER_XMLRPC_PORT', ServerConfig::$SERVERS[SERVER_NAME]['port']);
		
		// CONNEXION
		$client = new IXR_Client_Gbx;
		if( !$client->InitWithIp(SERVER_ADDR, SERVER_XMLRPC_PORT, DisplayServConfig::SERVER_CONNECTION_TIMEOUT) ){
			return 'Le serveur n\'est pas accessible.';
		}
		else{
			if( !$client->query('Authenticate', 'User', 'User') ){
				return 'Echec d\'authentification.';
			}
			else{
				return true;
			}
		}
	}
	
	public static function getServerName($serverId){
		$out = null;
		$servers = ServerConfig::$SERVERS;
		$countServers = count($servers);
		
		if( $countServers > 0 ){
			$i = 0;
			foreach($servers as $serverName => $serverValues){
				if($i == $serverId){
					$out = $serverName;
					break;
				}
				else{
					$i++;
				}
			}
		}
		
		return $out;
	}
}
?>