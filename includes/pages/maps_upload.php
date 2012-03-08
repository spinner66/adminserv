<?php
	// HTML
	$client->Terminate();
	AdminServTemplate::getHeader();
?>
<section class="maps hasFolders">
	<section class="cadre left menu">
		<?php echo $mapsMenu; ?>
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
				<li class="selected">
					<input class="text" type="radio" name="transferMode" id="transferMode" value="add" checked="checked" />
					<div class="name"><span>Ajouter</span> à la fin de la liste</div>
				</li>
				<li>
					<input class="text" type="radio" name="transferMode" id="transferMode" value="insert" />
						<div class="name"><span>Insérer</span> après la map en cours</div>
					</li>
				<li>
					<input class="text" type="radio" name="transferMode" id="transferMode" value="local" />
					<div class="name"><span>Envoyer</span> dans le dossier seulement</div>
				</li>
			</ul>
		</div>
		
		<h2>Options</h2>
		<div class="options-checkbox">
			<ul>
			<?php if(SERVER_MATCHSET){ ?>
				<li>
					<input class="text inline" type="checkbox" name="SaveCurrentMatchSettings" id="SaveCurrentMatchSettings"<?php if(AdminServConfig::AUTOSAVE_MATCHSETTINGS === true){ echo ' checked="checked"'; } ?> value="" />
					<label for="SaveCurrentMatchSettings" title="<?php echo SERVER_MATCHSET; ?>">Sauvegarder le MatchSettings courant</label>
				</li>
			<?php } ?>
				<li>
					<input class="text inline" type="checkbox" name="GotoListMaps" id="GotoListMaps" value="maps" />
					<label for="GotoListMaps">Aller à la liste des maps une fois l'upload terminé</label>
				</li>
			</ul>
		</div>
		
		<h2>Upload</h2>
		<div id="formUpload" class="loader" data-cancel="Annuler" data-failed="Échoué" data-uploadfile="Upload a file" data-dropfiles="Drop files here to upload" data-uploadnotfinish="L'upload n'est pas terminé" data-from="de" data-kb="Ko" data-mb="Mo" data-type-error="{file} has invalid extension. Only {extensions} are allowed." data-size-error="{file} is too large, maximum file size is {sizeLimit}." data-minsize-error="{file} is too small, minimum file size is {minSizeLimit}." data-empty-error="{file} is empty, please select files again without it." data-onleave="The files are being uploaded, if you leave now the upload will be cancelled."></div>
	</section>
</section>
<?php
	AdminServTemplate::getFooter();
?>