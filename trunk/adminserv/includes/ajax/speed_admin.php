<?php
	/**
	* Administration rapide : restart, next et endround
	*/
	
	
	// INCLUDES
	session_start();
	require_once '../../config/servers.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServTemplate::getClass();
	
	
	// SPEED ADMIN
	$out = false;
	if( isset($_POST['cmd']) ){ $cmd = addslashes( htmlspecialchars($_POST['cmd']) ); }else{ $cmd = null; }
	if($cmd != null){
		AdminServ::initialize();
		$out = AdminServ::speedAdmin($cmd);
	}
	
	
	// OUT
	echo json_encode($out);
?>