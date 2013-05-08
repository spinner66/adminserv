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
	
	// ISSET
	if( isset($_POST['nic']) ){ $nickname = $_POST['nic']; }else{ $nickname = null; }
	if( isset($_POST['clr']) ){ $color = $_POST['clr']; }else{ $color = null; }
	if( isset($_POST['msg']) ){ $message = $_POST['msg']; }else{ $message = null; }
	if( isset($_POST['dst']) ){ $destination = $_POST['dst']; }else{ $destination = null; }
	
	// DATA
	$out = null;
	if($message != null && $destination != null){
		if( AdminServ::initialize(false) ){
			$out = AdminServ::addChatServerLine($message, $nickname, $color, $destination, true);
		}
		$client->Terminate();
	}
	
	// OUT
	echo json_encode($out);
?>