<?php
	// METHODES
	if(SERVER_VERSION_NAME == 'TmForever'){
		$methodeMapList = 'GetChallengeList';
		$methodeMapIndex = 'GetCurrentChallengeIndex';
	}
	else{
		$methodeMapList = 'GetMapList';
		$methodeMapIndex = 'GetCurrentMapIndex';
	}
	
	// MAPSLIST
	if( !$client->query($methodeMapList, AdminServConfig::LIMIT_MAPS_LIST, 0) ){
		echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
	}
	else{
		$mapsList = $client->getResponse();
		if( !$client->query($methodeMapIndex) ){
			echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
		}
		else{
			$currentMapIndex = $client->getResponse();
		}
	}
	
	// HTML
	$client->Terminate();
	AdminServTemplate::getHeader();
?>
<section class="maps">
	<section class="cadre left menu">
		<?php include_once AdminServConfig::PATH_INCLUDES .'pages/maps_menu.inc.php'; ?>
	</section>
	
	<section class="cadre right order">
		
	</section>
</section>
<?php
	AdminServTemplate::getFooter();
?>