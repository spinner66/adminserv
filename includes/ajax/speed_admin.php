<?php
	/**
	* Administration rapide : restart, next et endround
	*/
	
	// INCLUDES
	session_start();
	require_once '../../config/servers.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServTemplate::getClass();
	
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