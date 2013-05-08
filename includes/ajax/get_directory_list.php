<?php
	// INCLUDES
	session_start();
	if( !isset($_SESSION['adminserv']['sid']) ){ exit; }
	if( isset($_SESSION['adminserv']['path']) ){ $adminservPath = $_SESSION['adminserv']['path']; }
	else{ $adminservPath = null; }
	$pathConfig = '../../'.$adminservPath.'config/';
	require_once $pathConfig.'adminserv.cfg.php';
	require_once $pathConfig.'extension.cfg.php';
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