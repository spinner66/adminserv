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
				<li><input class="text" type="checkbox" name="" id="" value="" /><div class="name"><span>Ajouter</span> à la fin de la liste</div></li>
				<li><input class="text" type="checkbox" name="" id="" value="" /><div class="name"><span>Insérer</span> après la map en cours</div></li>
				<li><input class="text" type="checkbox" name="" id="" value="" /><div class="name"><span>Envoyer</span> dans le dossier seulement</div></li>
			</ul>
		</div>
		<div class="fclear"></div>
		
		<?php if(SERVER_MATCHSET){ ?>
			<h2>Options</h2>
			<div class="options-checkbox">
				<input class="text inline" type="checkbox" name="SaveCurrentMatchSettings" id="SaveCurrentMatchSettings"<?php if(AdminServConfig::AUTOSAVE_MATCHSETTINGS === true){ echo ' checked="checked"'; } ?> value="" /><label for="SaveCurrentMatchSettings" title="<?php echo SERVER_MATCHSET; ?>">Sauvegarder le MatchSettings courant</label>
			</div>
		<?php } ?>
		
		<h2>Upload</h2>
		<div id="formUpload" class="loader"></div>
	</section>
</section>
<?php
	AdminServTemplate::getFooter();
?>