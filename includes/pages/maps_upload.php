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
		<h1>Envoyer</h1>
		<div class="title-detail path">
			<?php echo $mapsDirectoryPath.$directory; ?>
		</div>
		
		<h2>Mode de transfert</h2>
		<div class="transferMode">
			<ul>
				<li><input class="text" type="checkbox" name="" id="" value="" /><div class="name">Envoyer dans le dossier seulement</div></li>
				<li><input class="text" type="checkbox" name="" id="" value="" /><div class="name">Insérer après la map en cours</div></li>
				<li><input class="text" type="checkbox" name="" id="" value="" /><div class="name">Ajouter à la fin de la liste</div></li>
			</ul>
		</div>
		
		<h2>Upload</h2>
		<div id="formUpload" class="loader"></div>
	</section>
</section>
<?php
	AdminServTemplate::getFooter();
?>