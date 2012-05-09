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
		self::getServerConfig();
		require_once __DIR__ .'/class/GbxRemote.inc.php';
	}
	
	
	/**
	* Récupère le CSS/JS du site
	*/
	public static function getHeadFiles($path = DisplayServConfig::PATH_RESSOURCES){
		// CSS
		$out = '<link rel="stylesheet" href="'.$path.'styles/displayserv.css" />';
		if( defined('USER_THEME') ){
			if( file_exists($path.'styles/'. USER_THEME .'.css') ){
					$out .= '<link rel="stylesheet" href="'.$path.'styles/'. USER_THEME .'.css" />';
			}
		}
		
		// JS
		$out .= '<script src="'.$path.'js/jquery.js"></script>'
		.'<script src="'.$path.'js/displayserv.js"></script>';
		
		return $out;
	}
	
	
	
	public static function initialize(){
		
	}
}
?>