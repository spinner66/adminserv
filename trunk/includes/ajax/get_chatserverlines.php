<?php
	// INCLUDES
	session_start();
	$pathConfig = '../../'.$_SESSION['adminserv']['path'].'config/';
	require_once $pathConfig.'adminserv.cfg.php';
	require_once $pathConfig.'extension.cfg.php';
	require_once $pathConfig.'servers.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	
	// ISSET
	if( isset($_GET['s']) ){ $hideServerLines = intval($_GET['s']); }else{ $hideServerLines = 0; }
	
	// DATA
	$out = null;
	if( AdminServ::initialize(false) ){
		$out = AdminServ::getChatServerLines($hideServerLines);
	}
	
	// OUT
	$client->Terminate();
	echo json_encode($out);
?>
