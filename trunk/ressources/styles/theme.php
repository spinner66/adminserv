<?php	// Includes	session_start();	if( isset($_SESSION['adminserv']['path']) ){ $adminservPath = $_SESSION['adminserv']['path']; }	else{ $adminservPath = null; }	$pathConfig = '../../'.$adminservPath.'config/';	require_once $pathConfig.'adminserv.cfg.php';	require_once $pathConfig.'extension.cfg.php';		// Thème	if( isset($_GET['th']) ){		$themeColor = addslashes($_GET['th']);	}	else{		$themeColor = AdminServConfig::DEFAULT_THEME;	}		// Couleur	$color1 = ExtensionConfig::$THEMES[$themeColor][0];	$color2 = ExtensionConfig::$THEMES[$themeColor][1];		// CSS	header('Content-type: text/css');?>/* Color */#title .title-color,h2,h3 {	color: <?php echo $color1; ?>;}/* Background color */#header-color-line,h1,.menu .vertical-nav ul li a.active,.menu .vertical-nav ul li a:hover,.qq-upload-button-hover {	background-color: <?php echo $color1; ?>;}tbody tr.selected,.options-radio-inline li:hover,.options-radio-inline li.selected ,#sortableMapList li:hover {	background-color: <?php echo $color2; ?>;}/* Others */.button.light,.button.dark {	-webkit-box-shadow: 0 4px 0 <?php echo $color1; ?>;	-moz-box-shadow: 0 4px 0 <?php echo $color1; ?>;	-o-box-shadow: 0 4px 0 <?php echo $color1; ?>;	box-shadow: 0 4px 0 <?php echo $color1; ?>;}fieldset legend {	box-shadow: 0 3px <?php echo $color1; ?>;}.menu .vertical-nav ul li a {	border-bottom-color: <?php echo $color2; ?>;}.options-checkbox {	border-left-color: <?php echo $color1; ?>;}.folders .option-folder-list .arrow-down {	border-color: <?php echo $color1; ?> transparent transparent;}.folders .option-folder-list .arrow-up {	border-color: transparent transparent <?php echo $color1; ?>;}.content p a {	border-color: <?php echo $color1; ?>;}