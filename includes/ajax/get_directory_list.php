<?php
	// INCLUDES
	session_start();
	require_once '../../config/adminserv.cfg.php';
	require_once '../../config/servers.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServTemplate::getClass();
	
	// DATA
	$out = array();
	if( AdminServ::initialize() ){
		$path = AdminServ::getMapsDirectoryPath();
		$out = Folder::getArborescence($path, AdminServConfig::$MAPS_HIDDEN_FOLDERS, substr_count($path, '/'));
	}
	
	// OUT
	$client->Terminate();
	echo json_encode($out);
?>