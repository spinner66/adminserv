<?php
	/**
	* Prévisualise le nom du serveur et son commentaire
	*/
	
	// INCLUDES
	require_once '../adminserv.inc.php';
	AdminServTemplate::getClass();
	
	
	// HTML
	$out = null;
	if( isset($_GET['t']) ){ $text = stripslashes($_GET['t']); }else{ $text = null; }
	if($text != null){
		$out['str'] = TmNick::toHtml( nl2br($text), 10, false, false, '#666');
	}
	
	
	// OUT
	echo json_encode($out);
?>