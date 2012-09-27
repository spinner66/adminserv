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
	
	// ISSET
	if( isset($_POST['cmd']) ){ $cmd = addslashes( htmlspecialchars($_POST['cmd']) ); }else{ $cmd = null; }
	
	// SPEED ADMIN
	$out = false;
	if($cmd != null){
		if( AdminServ::initialize() ){
			$out = AdminServ::speedAdmin($cmd);
		}
	}
	
	// OUT
	$client->Terminate();
	echo json_encode($out);
?>