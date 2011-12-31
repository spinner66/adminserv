<?php
	// INCLUDES
	session_start();
	require_once '../../config/servers.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServTemplate::getClass();
	
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
