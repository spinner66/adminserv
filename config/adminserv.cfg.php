<?php
class OnlineConfig {
	/********************* ONLINE CONFIGURATION *********************/
	
	const ACTIVATE = true; // Active the online configuration
	const PASSWORD = '0b28a5799a32c687dad2c5183718ceac'; // Checking password. This password is generated in MD5
	const ADDRESS = ''; // Checking address. Can be localhost or IP address
	const ADD_ONLY = false; // Add only server. Unable to modify or delete
}

class AdminServConfig {
	/********************* OPTIONAL CONFIGURATION *********************/
	
	/* GENERAL */
	const TITLE = 'Admin,Serv'; // The comma seperate the color
	const SUBTITLE = 'For maniaplanet servers'; // if null it's hidden
	const LOGO = 'logo.png'; // if null it's hidden
	const DEFAULT_THEME = 'blue'; // The first theme to loading
	const DEFAULT_LANGUAGE = 'auto'; // Can be fr, en, de, es or auto = automaticaly detection
	const USE_DISPLAYSERV = true; // Show DisplayServ tool on connection page
	const AUTOSAVE_MATCHSETTINGS = true; // Save the MatchSettings in the server config (possibility to disable online)
	
	/* ADVANCED */
	const MD5_PASSWORD = false; // if true, the dedicated server password is checked in MD5
	const LIMIT_PLAYERS_LIST = 250; // Limit to display lines in player list
	const LIMIT_MAPS_LIST = 1000; // Limit to display lines in maps list
	const LOCAL_GET_MAPS_ON_SERVER = true; // if true, the current server maps list is loaded in local maps page for change icone in lines. But if you have a lot of maps in server, will better to disable
	const RECENT_STATUS_PERIOD = 24; // Recent status period in hour for maps/matchsettings/guestban
	const SERVER_CONNECTION_TIMEOUT = 3; // Dedicated server connection timeout in second
	const COOKIE_EXPIRE = 30; // Days of cookie expire
	
	/* FILES AND FOLDERS */
	public static $FOLDERS_OPTIONS = array( // Actions for the folders in maps page. You can be disable or limit to authorization level
		'new' => array(true, 'Admin'),
		'rename' => array(true, 'Admin'),
		'move' => array(true, 'Admin'),
		'delete' => array(true, 'SuperAdmin')
	);
	public static $MAPS_HIDDEN_FOLDERS = array('MatchSettings', 'Replays'); // Folders hidden in maps page
	public static $MATCHSET_HIDDEN_FOLDERS = array('Campaigns', 'Replays'); // Folders hidden in matchsettings page
	public static $MAP_EXTENSION = array('map.gbx', 'challenge.gbx'); // Double extension used in maps page
	public static $MATCHSET_EXTENSION = array('txt'); // MatchSettings extension used in matchsettings page
	public static $PLAYLIST_EXTENSION = array('playlist.txt'); // Playlists extension used in guestban page
	
	/* UPLOAD */
	public static $ALLOWED_EXTENSIONS = array('gbx'); // Extension allowed to upload
	const SIZE_LIMIT = 'auto'; // Limit size per file in MB. If auto, the limit size in php.ini config file is used
	
	/* LOGS */
	public static $LOGS = array(
		'access' => true,
		'action' => true,
		'error' => true
	);
	
	/* MULTI ADMINSERV */
	const MULTI_ADMINSERV = false; // Use more instances of AdminServ
	const PATH_INCLUDES = './includes/'; // You can be change the location of the folders
	const PATH_PLUGINS = './plugins/';
	const PATH_RESSOURCES = './ressources/';
	
	/* PLUGINS */
	const PLUGINS_LIST = ''; // The filename of other plugin list file in php. In the file: $PLUGINS = array('pluginfoldername', 'etc');
	const PLUGINS_LIST_TYPE = 'replace'; // add or replace method
}

class DataBaseConfig {
	/********************* DATABASE CONFIGURATION *********************/
	
	const DB_HOST = '';
	const DB_USER = '';
	const DB_PASS = '';
	const DB_NAME = '';
	const DB_TABLE_PREFIX = 'srv_';
}

class FTPConfig {
	/********************* FTP CONFIGURATION *********************/
	
	const FTP_HOST = '';
	const FTP_USER = '';
	const FTP_PASS = '';
	const FTP_PORT = 21;
}
?>