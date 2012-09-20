<?php
	/**
	* Retourne une chaine de caractère en couleur html
	*/
	
	// INCLUDES
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	
	// ISSET
	if( isset($_GET['t']) ){ $text = stripslashes($_GET['t']); }else{ $text = null; }
	
	// HTML
	$out = null;
	if($text != null){
		$out['str'] = TmNick::toHtml( nl2br($text), 10, false, false, '#666');
	}
	
	// OUT
	echo json_encode($out);
?>