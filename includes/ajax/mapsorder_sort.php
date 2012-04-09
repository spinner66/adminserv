<?php
	/**
	* 
	*/
	
	// INCLUDES
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	
	// ISSET
 	if( isset($_GET['srt']) ){ $sort = $_GET['srt']; }else{ $sort = null; }
	if( isset($_GET['ord']) ){ $order = $_GET['ord']; }else{ $order = 'asc'; }
	if( isset($_GET['lst']) ){ $list = $_GET['lst']; }else{ $list = null; }
	
	// HTML
	$out = null;
	if($sort != null && $list != null){
		$list = json_decode($list, true);
		
		switch($sort){
			case 'name':
				usort($list['lst'], 'AdminServSort::sortByName');
				break;
			case 'env':
				usort($list['lst'], 'AdminServSort::sortByEnviro');
				break;
			case 'author':
				usort($list['lst'], 'AdminServSort::sortByAuthor');
				break;
			case 'rand':
				shuffle($list['lst']);
				break;
		}
		if($order == 'desc'){
			rsort($list['lst']);
		}
		$out = array(
			'cid' => $list['cid'],
			'lst' => $list['lst'],
			'nbm' => $list['nbm'],
			'cfg' => $list['cfg']
		);
	}
	
	// OUT
	echo json_encode($out);
?>