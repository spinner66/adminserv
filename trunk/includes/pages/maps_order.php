<?php
	// HTML
	$client->Terminate();
	AdminServTemplate::getHeader();
?>
<section class="maps">
	<section class="cadre left menu">
		<?php include_once AdminServConfig::PATH_INCLUDES .'pages/maps_menu.inc.php'; ?>
	</section>
	
	<section class="cadre right order">
		<h1>Ordonner</h1>
	</section>
</section>
<?php
	AdminServTemplate::getFooter();
?>