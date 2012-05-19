<?php
	/**
	* Récupère la liste des maps en local
	*/
	
	// INCLUDES
	session_start();
	require_once '../../config/adminserv.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	
	// ISSET
	if( isset($_GET['path']) ){ $path = addslashes($_GET['path']); }else{ $path = null; }
	
	// DATA
	$out = AdminServ::getLocalMapList($path);
	echo json_encode($out);
?>