<?php
	// INCLUDES
	session_start();
	if( isset($_SESSION['adminserv']['path']) ){ $adminservPath = $_SESSION['adminserv']['path']; }
	else{ $adminservPath = null; }
	$pathConfig = '../../'.$adminservPath.'config/';
	require_once $pathConfig.'adminserv.cfg.php';
	require_once $pathConfig.'extension.cfg.php';
	require_once $pathConfig.'servers.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	$langCode = AdminServUI::getLang();
	$langFile = '../lang/'.$langCode.'.php';
	if( file_exists($langFile) ){
		require_once $langFile;
	}
	
	// ISSET
	if( isset($_GET['s']) ){ $hideServerLines = $_GET['s']; }else{ $hideServerLines = false; }
	
	// DATA
	$out = null;
	if( AdminServ::initialize(false) ){
		$out = AdminServ::getChatServerLines($hideServerLines);
	}
	
	// OUT
	$client->Terminate();
	echo json_encode($out);
?>