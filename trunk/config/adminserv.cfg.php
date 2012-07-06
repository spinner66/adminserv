<?php
class OnlineConfig {
	/********************* ONLINE CONFIGURATION *********************/
	
	const ACTIVATE = true; // Active the online configuration
	const PASSWORD = '1b270a438a72b86bdba4d9fd373d1417'; // Checking password. This password is generated in MD5
	const ADDRESS = ''; // Checking address. Can be localhost, domain name or IP address
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
	const RECENT_STATUS_PERIOD = 86400; // Recent status period in second for maps/matchsettings/guestban
	const COOKIE_EXPIRE = 15; // Days of cookie expire
	const SERVER_CONNECTION_TIMEOUT = 3; // Dedicated server connection timeout in second
	
	/* FILES AND FOLDERS */
	public static $FOLDERS_OPTIONS = array( // Actions for the folders in maps page. You can be disable or limit to authorization level
		'new' => array(true, 'Admin'),
		'rename' => array(true, 'Admin'),
		'move' => array(true, 'Admin'),
		'delete' => array(true, 'SuperAdmin')
	);
	public static $MAPS_HIDDEN_FOLDERS = array('MatchSettings', 'Replays'); // Folders hidden in maps page
	public static $MAPS_HIDDEN_FILES = array('db', 'txt', 'xml', 'zip', 'php', 'log'); // Files or extensions hidden in maps page
	public static $MATCHSET_HIDDEN_FOLDERS = array('Campaigns', 'Replays'); // Folders hidden in matchsettings page
	public static $MATCHSET_HIDDEN_FILES = array('db', 'gbx', 'php', 'log'); // Files or extensions hidden in matchsettings page
	public static $PLAYLIST_HIDDEN_FILES = array('gbx', 'dedicated_cfg.txt', 'checksum.txt', 'servers.txt', 'php', 'dat', 'log', 'cfg', 'cfg~'); // Files or extensions hidden in guestban page
	
	/* UPLOAD */
	public static $ALLOWED_EXTENSIONS = array('gbx', 'zip', 'rar', '7z', 'gzip'); // Extension allowed to upload
	const SIZE_LIMIT = 'auto'; // Limit size per file in MB. If auto, the limit size in php.ini config file is used
	
	/* LOCAL */
	public static $MAP_EXTENSION = array('map.gbx', 'challenge.gbx'); // Double extension used in maps page
	public static $MATCHSET_EXTENSION = array('txt', 'xml'); // MatchSettings extension user in matchsettings page
	
	/* PLUGINS */
	public static $USE_ANOTHER_PLUGINS_LIST = array(); // This one take 2 parameters. 1# the filename of the plugin list file. In the file: $PLUGINS = array('pluginfoldername', 'etc'); #2 add or replace method. Default: replace

	/* LOGS */
	public static $LOGS = array(
		'access' => true,
		'action' => true,
		'error' => true
	);
	
	/* MULTI ADMINSERV */
	const MULTI_ADMINSERV = false; // Use more instances of AdminServ
	
	/* PATH DIRECTORY */
	const PATH_INCLUDES = './includes/'; // You can be change the location of the folders (used for multiple AdminServ)
	const PATH_PLUGINS = './plugins/';
	const PATH_RESSOURCES = './ressources/';
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