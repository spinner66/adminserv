<?php
	// LECTURE
	$mapsDirectoryPath = AdminServ::getMapsDirectoryPath();
	
	// HTML
	$client->Terminate();
	AdminServTemplate::getHeader();
?>
<section class="maps hasFolders">
	<section class="cadre left menu">
		<?php include_once AdminServConfig::PATH_INCLUDES .'pages/maps_menu.inc.php'; ?>
	</section>
	
	<section class="cadre middle folders">
		<?php echo AdminServTemplate::getMapsDirectoryList($mapsDirectoryPath); ?>
	</section>
	
	<section class="cadre right matchset">
		
	</section>
</section>
<?php
	AdminServTemplate::getFooter();
?>