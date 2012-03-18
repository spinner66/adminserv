<?php
class MenuConfig {
	/********************* MENU CONFIGURATION *********************/
	
	// Menu général gauche
	public static $GENERAL_LEFT = array(
		'Liste' => 'maps-list',
		'Local' => 'maps-local',
		'Envoyer' => 'maps-upload',
		'Ordonner' => 'maps-order',
		'MatchSettings' => 'maps-matchset',
		'Créer un MatchSetting' => 'maps-creatematchset',
	);
	
	
	// Menu général droit
	public static $GENERAL_RIGHT = array(
		'Liste' => 'maps',
		'Local' => 'maps-local',
		'Envoyer' => 'maps-upload',
		'Ordonner' => 'maps-order',
		'MatchSettings' => 'maps-matchset',
		'Créer un MatchSetting' => 'maps-creatematchset',
	);
	
	
	// Menu maps
	public static $MAPS = array(
		'Liste' => 'maps',
		'Local' => 'maps-local',
		'Envoyer' => 'maps-upload',
		'Ordonner' => 'maps-order',
		'MatchSettings' => 'maps-matchset',
		'Créer un MatchSetting' => 'maps-creatematchset',
	);
	
	
	// Page par défaut
	public static $DEFAULT = array(
		'General' => 'general'
	);
}
?>