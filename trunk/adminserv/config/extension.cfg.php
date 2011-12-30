<?php
class ExtensionConfig {
	/********************* EXTENSION CONFIGURATION *********************/
	
	// Liste des thèmes de couleurs
	public static $THEMES = array(
		'blue' => '#5e9cd5',
		'green' => '#44c621',
		'orange' => '#ffa800',
		'black' => '#777'
	);
	
	
	// Liste des langues utilisable
	public static $LANG = array(
		'fr',
		'en',
		'de',
		'es'
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
	
	
	// Liste des plugins installés
	public static $PLUGINS = array(
		'mysqlserverlist'
	);
}
?>