<?php
class OnlineConfig {
	/********************* ONLINE CONFIGURATION *********************/
	
	const ACTIVATE = true;
	const CHECK_PASSWORD = 'SuperAdmin';
	const CHECK_IP = 'localhost';
	const ADD_ONLY = false;
}

class AdminServConfig {
	/********************* OPTIONAL CONFIGURATION *********************/
	
	/* GENERAL */
	const TITLE = 'Admin,Serv';
	const SUBTITLE = 'For maniaplanet servers';
	const LOGO = 'logo.png';
	const DEFAULT_THEME = 'blue';
	const DEFAULT_LANGUAGE = 'auto'; // Can be fr, en, de, es or auto = automaticaly detection
	const AUTOSAVE_MATCHSETTINGS = true;
	
	/* ADVANCED */
	const MD5_PASSWORD = false;
	const LIMIT_PLAYERS_LIST = 250; //lignes
	const LIMIT_MAPS_LIST = 1000; //lignes
	const RECENT_STATUS_PERIOD = 86400; //sec
	const COOKIE_EXPIRE = 15; // jours
	const SERVER_CONNECTION_TIMEOUT = 3; //sec
	
	/* FILES AND FOLDERS */
	public static $FOLDERS_OPTIONS = array(
		'new' => array(true, 'Admin'),
		'rename' => array(true, 'Admin'),
		'move' => array(true, 'Admin'),
		'delete' => array(true, 'SuperAdmin')
	);
	public static $MAPS_HIDDEN_FOLDERS = array('MatchSettings', 'Replays');
	public static $MAPS_HIDDEN_FILES = array('db', 'txt', 'xml', 'zip', 'php', 'log');
	public static $MATCHSET_HIDDEN_FOLDERS = array('Campaigns', 'Replays');
	public static $MATCHSET_HIDDEN_FILES = array('db', 'gbx', 'php', 'log');
	public static $PLAYLIST_HIDDEN_FILES = array('gbx', 'dedicated_cfg.txt', 'checksum.txt', 'servers.txt', 'php', 'dat', 'log', 'cfg', 'cfg~');
	
	/* UPLOAD */
	public static $ALLOWED_EXTENSIONS = array('gbx', 'zip', 'rar', '7z', 'gzip');
	const SIZE_LIMIT = 'auto'; // mo
	
	/* LOCAL */
	public static $MAP_EXTENSION = array('map.gbx', 'challenge.gbx');
	public static $MATCHSET_EXTENSION = array('txt', 'xml');
	
	/* PLUGINS */
	public static $USE_ANOTHER_PLUGINS_LIST = array();
	
	/* LOGS */
	public static $LOGS = array(
		'access' => true,
		'action' => true,
		'error' => true
	);
	
	/* PATH DIRECTORY */
	const PATH_INCLUDES = './includes/';
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
	
	public static $DB_TABLE_COLUMNS = array(
		'id' => 'serverId',
		'name' => 'serverName',
		'addr' => 'serverAddr',
		'port' => 'serverPort',
		'matchsettings' => 'serverMatchSet',
		'adminlevel' => 'serverAdminLevel',
		'active' => 'serverActive'
	);
}
?>