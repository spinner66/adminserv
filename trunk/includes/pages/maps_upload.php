<?php
	// LECTURE
	$mapsDirectoryPath = AdminServ::getMapsDirectoryPath();
	$mapsDirectoryList = AdminServTemplate::getMapsDirectoryList($mapsDirectoryPath, $directory);
	
	// HTML
	$client->Terminate();
	AdminServTemplate::getHeader();
?>
<section class="maps hasFolders">
	<section class="cadre left menu">
		<?php include_once AdminServConfig::PATH_INCLUDES .'pages/maps_menu.inc.php'; ?>
	</section>
	
	<section class="cadre middle folders">
		<?php echo $mapsDirectoryList; ?>
	</section>
	
	<section class="cadre right upload">
		<h1>Ajouter</h1>
		
	</section>
</section>
<?php
	AdminServTemplate::getFooter();
?>