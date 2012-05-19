<?php
	/**
	* Récupère la liste des maps en local
	*/
	
	// INCLUDES
	session_start();
	
	// DATA
	if( !isset($_SESSION['adminserv']['matchset_maps_selected']) ){
		$out = array();
		$out['lst'] = 'Aucune map';
		$out['nbm'] = '0 map';
		$_SESSION['adminserv']['matchset_maps_selected'] = $out;
	}
	echo json_encode($_SESSION['adminserv']['matchset_maps_selected']);
?>