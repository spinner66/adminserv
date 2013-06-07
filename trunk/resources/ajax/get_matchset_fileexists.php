<?php
	// INCLUDES
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	
	// ISSET
	if( isset($_GET['path']) ){ $path = $_GET['path']; }else{ $path = null; }
	if( isset($_GET['name']) ){ $name = $_GET['name']; }else{ $name = null; }
	
	// CHECKING
	$out = false;
	if($path && $name){
		$struct = Folder::read($path);
		if( !strstr($name, '.txt') ){
			$name = $name.'.txt';
		}
		
		if( count($struct['files']) > 0 ){
			foreach($struct['files'] as $fileName => $fileValues){
				if($name == $fileName){
					$out = true;
					break;
				}
			}
		}
	}
	
	// OUT
	echo json_encode($out);
?>