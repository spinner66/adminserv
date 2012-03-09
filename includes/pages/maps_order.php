<?php
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="maps hasMenu">
	<section class="cadre left menu">
		<nav class="vertical-nav">
			<?php echo $mapsMenu; ?>
		</nav>
	</section>
	
	<section class="cadre right order">
		<h1>Ordonner</h1>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>