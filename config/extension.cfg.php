<?php
class ExtensionConfig {
	/********************* EXTENSION CONFIGURATION *********************/
	
	// Liste des thèmes de couleurs
	public static $THEMES = array(
		'blue' => '#5e9cd5',
		'orange' => '#ffa600',
		'green' => '#8aca1b',
		'purple' => '#b15cd5',
		'black' => '#656267'
	);
	
	
	// Liste des langues utilisable
	public static $LANG = array(
		'fr' => 'Français',
		'en' => 'English',
		'de' => 'Deutsch',
		'es' => 'Español'
	);
	
	
	// Liste des modes de jeu
	public static $GAMEMODES = array(
		0 => 'Script',
		1 => 'Rounds',
		2 => 'TimeAttack',
		3 => 'Team',
		4 => 'Laps',
		5 => 'Stunts',
		6 => 'Cup'
	);
	
	
	// Liste du menu des pages "maps"
	public static $MAPSMENU = array(
		'Liste' => 'maps-list',
		'Local' => 'maps-local',
		'Envoyer' => 'maps-upload',
		'Ordonner' => 'maps-order',
		'MatchSettings' => 'maps-matchset',
		'Créer un MatchSetting' => 'maps-creatematchset',
	);
	
	
	// Liste des plugins installés
	public static $PLUGINS = array();
}
?>