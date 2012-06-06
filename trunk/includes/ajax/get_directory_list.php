<?php
	// INCLUDES
	session_start();
	$pathConfig = '../../'.$_SESSION['adminserv']['path'].'config/';
	require_once $pathConfig.'adminserv.cfg.php';
	require_once $pathConfig.'servers.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	
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