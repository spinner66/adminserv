<?php
	/**
	* Récupère la liste des maps en local
	*/
	
	// INCLUDES
	session_start();
	require_once '../../config/adminserv.cfg.php';
	require_once '../adminserv.inc.php';
	
	// DATA
	if( !isset($_SESSION['adminserv']['matchset_maps_selected']) ){
		// Retourne "Aucune map"
		AdminServ::saveMatchSettingSelection();
	}
	else{
		// Enlever une map de la sélection
		if( isset($_GET['remove']) ){
			$removeSelection = intval($_GET['remove']);
			$mapsSelection = $_SESSION['adminserv']['matchset_maps_selected'];
			
			// Liste
			if( count($mapsSelection['lst']) ){
				foreach($mapsSelection['lst'] as $id => $values){
					if($id == $removeSelection){
						unset($mapsSelection['lst'][$id]);
						break;
					}
				}
			}
			
			$_SESSION['adminserv']['matchset_maps_selected'] = $mapsSelection;
			AdminServ::saveMatchSettingSelection();
		}
	}
	
	echo json_encode($_SESSION['adminserv']['matchset_maps_selected']);
?>