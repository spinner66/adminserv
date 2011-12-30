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
	const DEFAULT_LANGUAGE = 'fr';
	const AUTOSAVE_MATCHSETTINGS = true;
	
	/* ADVANCED */
	const LIMIT_PLAYERS_LIST = 250;
	const LIMIT_MAPS_LIST = 1000;
	const RECENT_STATUS_PERIOD = 86400;
	const COOKIE_EXPIRE = 15;
	
	/* PATH DIRECTORY */
	const PATH_CONFIG = './config/';
	const PATH_INCLUDES = './includes/';
	const PATH_LOGS = './logs/';
	const PATH_PLUGINS = './plugins/';
	const PATH_RESSOURCES = './ressources/';
}
?>