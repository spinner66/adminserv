<?php
	// INCLUDES
	require_once '../adminserv.inc.php';
	require_once '../class/tmnick.class.php';
	
	// ISSET
 	if( isset($_POST['srt']) ){ $sort = $_POST['srt']; }else{ $sort = null; }
	if( isset($_POST['ord']) ){ $order = $_POST['ord']; }else{ $order = 'asc'; }
	if( isset($_POST['lst']) ){ $list = $_POST['lst']; }else{ $list = null; }
	
	// DATA
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
		);
	}
	
	// OUT
	echo json_encode($out);
?>