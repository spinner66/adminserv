<?php
class ExtensionConfig {
	/********************* EXTENSION CONFIGURATION *********************/
	
	// Plugins list installed
	public static $PLUGINS = array(
		'planets',
		'coppers',
	);
	
	// Themes color list
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
		'red' => array(
			'#ea4f4f',
			'#ffc0c0'
		),
		'black' => array(
			'#727272',
			'#dedede'
		)
	);
	
	
	// Available language list
	public static $LANG = array(
		'fr' => 'Français',
		'en' => 'English',
		'de' => 'Deutsch',
		'es' => 'Español'
	);
	
	
	// Game modes list
	public static $GAMEMODES = array(
		0 => 'Script',
		1 => 'Rounds',
		2 => 'TimeAttack',
		3 => 'Team',
		4 => 'Laps',
		5 => 'Stunts',
		6 => 'Cup'
	);
	
	
	// Menu list in maps page
	public static $MAPSMENU = array(
		'maps-list' => 'Liste',
		'maps-local' => 'Local',
		'maps-upload' => 'Envoyer',
		'maps-order' => 'Ordonner',
		'maps-matchset' => 'MatchSettings',
		'maps-creatematchset' => 'Créer un MatchSettings'
	);
}
?>