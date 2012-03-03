<?php
	// INCLUDES
	session_start();
	require_once '../../config/adminserv.cfg.php';
	require_once '../../config/servers.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServTemplate::getClass();
	
	// ISSET
	if( isset($_GET['path']) ){ $path = $_GET['path']; }else{ $path = null; }
	if( substr($path, -1, 1) != '/'){ $path = $path.'/'; }
	
	// DATA
	if($path != null){
		$struct = Folder::getArborescence($path, AdminServConfig::$MAPS_HIDDEN_FOLDERS, substr_count($path, '/'));
		echo json_encode($struct);
	}
?>