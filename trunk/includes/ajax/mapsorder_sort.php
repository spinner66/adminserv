<?php
	/**
	* 
	*/
	
	// INCLUDES
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	
	// ISSET
	if( isset($_POST['tri']) ){ $tri = $_POST['tri']; }else{ $tri = 'name'; }
	if( isset($_POST['ord']) ){ $order = $_POST['ord']; }else{ $order = 'asc'; }
	if( isset($_POST['lst']) ){ $list = $_POST['lst']; }else{ $list = null; }
	
	// HTML
	$out = null;
	if($list != null){
		
		if($tri == 'name'){
			
		}
		
	}
	
	// OUT
	echo json_encode($out);
?>