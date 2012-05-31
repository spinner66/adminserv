<?php
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="maps hasMenu hasFolders">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMenuList(ExtensionConfig::$MAPSMENU); ?>
	</section>
	
	<section class="cadre middle folders">
		<?php echo $mapsDirectoryList; ?>
	</section>
	
	<section class="cadre right upload">
		<h1><?php echo Utils::t('Send'); ?></h1>
		<div class="title-detail path">
			<?php echo $mapsDirectoryPath.$directory; ?>
		</div>
		
		<h2><?php echo Utils::t('Transfer mode'); ?></h2>
		<div class="transferMode options-radio-inline">
			<ul>
				<li class="selected">
					<input class="text" type="radio" name="transferMode" id="transferModeAdd" value="add" checked="checked" />
					<div class="name"><span><?php echo Utils::t('Add'); ?></span> <?php echo Utils::t('at end of the list'); ?></div>
				</li>
				<li>
					<input class="text" type="radio" name="transferMode" id="transferModeInsert" value="insert" />
					<div class="name"><span><?php echo Utils::t('Insert'); ?></span> <?php echo Utils::t('after the current map'); ?></div>
				</li>
				<li>
					<input class="text" type="radio" name="transferMode" id="transferModeLocal" value="local" />
					<div class="name"><span><?php echo Utils::t('Send'); ?></span> <?php echo Utils::t('only in the folder'); ?></div>
				</li>
			</ul>
		</div>
		
		<h2><?php echo Utils::t('Options'); ?></h2>
		<div class="options-checkbox">
			<ul>
			<?php if(SERVER_MATCHSET){ ?>
				<li>
					<input class="text inline" type="checkbox" name="SaveCurrentMatchSettings" id="SaveCurrentMatchSettings"<?php if(AdminServConfig::AUTOSAVE_MATCHSETTINGS === true){ echo ' checked="checked"'; } ?> value="" />
					<label for="SaveCurrentMatchSettings" title="<?php echo SERVER_MATCHSET; ?>"><?php echo Utils::t('Save the current MatchSettings'); ?></label>
				</li>
			<?php } ?>
				<li>
					<input class="text inline" type="checkbox" name="GotoListMaps" id="GotoListMaps" value="maps" />
					<label for="GotoListMaps"><?php echo Utils::t('Go to the list map after finish upload'); ?></label>
				</li>
			</ul>
		</div>
		
		<h2><?php echo Utils::t('Upload'); ?></h2>
		<div id="formUpload" class="loader" data-mapspagename="maps" data-cancel="<?php echo Utils::t('Cancel'); ?>" data-failed="<?php echo Utils::t('Failed'); ?>" data-uploadfile="<?php echo Utils::t('Upload a file'); ?>" data-dropfiles="<?php echo Utils::t('Drop files here to upload'); ?>" data-uploadnotfinish="<?php echo Utils::t('Upload not finish'); ?>" data-from="<?php echo Utils::t('from'); ?>" data-kb="<?php echo Utils::t('Kb'); ?>" data-mb="<?php echo Utils::t('Mb'); ?>" data-type-error="<?php echo Utils::t('{file} has invalid extension. Only {extensions} are allowed.'); ?>" data-size-error="<?php echo Utils::t('{file} is too large, maximum file size is {sizeLimit}.'); ?>" data-minsize-error="<?php echo Utils::t('{file} is too small, minimum file size is {minSizeLimit}.'); ?>" data-empty-error="<?php echo Utils::t('{file} is empty, please select files again without it.'); ?>" data-onleave="<?php echo Utils::t('The files are being uploaded, if you leave now the upload will be cancelled.'); ?>"></div>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>