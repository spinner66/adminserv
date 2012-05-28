<?php
class ExtensionConfig {
	/********************* EXTENSION CONFIGURATION *********************/
	
	// Liste des thèmes de couleurs
	public static $THEMES = array(
		'blue' => array(
			'#5e9cd5',
			'#d9e8ff'
		),
		'orange' => array(
			'#ffa600',
			'#ffe3b0'
		),
		'green' => array(
			'#8aca1b',
			'#d1e9a8'
		),
		'purple' => array(
			'#b15cd5',
			'#e5c4f3'
		),
		'black' => array(
			'#727272',
			'#d4d4d4'
		)
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
		'maps-list' => 'Liste',
		'maps-local' => 'Local',
		'maps-upload' => 'Envoyer',
		'maps-order' => 'Ordonner',
		'maps-matchset' => 'MatchSettings',
		'maps-creatematchset' => 'Créer un MatchSettings'
	);
	
	
	// Liste des plugins installés
	public static $PLUGINS = array(
		'example',
	);
}
?>