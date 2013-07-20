<section class="maps hasMenu hasFolders">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMapsMenuList(); ?>
	</section>
	
	<section class="cadre middle folders">
		<?php echo $mapsDirectoryList; ?>
	</section>
	
	<section class="cadre right local">
		<h1><?php echo Utils::t('Local'); ?></h1>
		<div class="title-detail">
			<ul>
				<li><div class="path"><?php echo $mapsDirectoryPath.$directory; ?></div></li>
				<li class="last"><input type="checkbox" name="checkAll" id="checkAll" value=""<?php if( !is_array($mapsList['lst']) ){ echo ' disabled="disabled"'; } ?> /></li>
			</ul>
		</div>
		
		<form method="post" action="?p=<?php echo USER_PAGE; if($directory){ echo '&amp;d='.$directory; } ?>">
		<div id="maplist">
			<table>
				<thead>
					<tr>
						<th class="thleft"><a href="?p=<?php echo USER_PAGE; if($directory){ echo '&amp;d='.$directory; } ?>&amp;sort=name"><?php echo Utils::t('Map'); ?></a></th>
						<th><a href="?p=<?php echo USER_PAGE; if($directory){ echo '&amp;d='.$directory; } ?>&amp;sort=env"><?php echo Utils::t('Environment'); ?></a></th>
						<th><a href="?p=<?php echo USER_PAGE; if($directory){ echo '&amp;d='.$directory; } ?>&amp;sort=type"><?php echo Utils::t('Type'); ?></a></th>
						<th><a href="?p=<?php echo USER_PAGE; if($directory){ echo '&amp;d='.$directory; } ?>&amp;sort=author"><?php echo Utils::t('Author'); ?></a></th>
						<th class="thright"></th>
					</tr>
				</thead>
				<tbody>
					<tr class="table-separation"><td colspan="4"></td></tr>
					<?php
						$showMapList = null;
						
						// Liste des maps local
						if( is_array($mapsList['lst']) && count($mapsList['lst']) > 0 ){
							$pathRessources = AdminServConfig::$PATH_RESOURCES;
							$i = 0;
							foreach($mapsList['lst'] as $id => $map){
								// Map sur le serveur
								if($map['OnServer']){
									$mapImg = 'loadmap';
									$mapClass = ' onserver';
								}
								else{
									$mapImg = 'map';
									$mapClass = null;
								}
								
								// Lignes
								$showMapList .= '<tr class="'; if($i%2){ $showMapList .= 'even'; }else{ $showMapList .= 'odd'; } if($map['Recent']){ $showMapList .= ' recent'; } $showMapList .= $mapClass.'">'
									.'<td class="imgleft"><img src="'.$pathRessources.'images/16/'.$mapImg.'.png" alt="" /><span title="'.$map['FileName'].'">'.$map['Name'].'</span></td>'
									.'<td class="imgcenter"><img src="'.$pathRessources.'images/env/'.strtolower($map['Environnement']).'.png" alt="" />'.$map['Environnement'].'</td>'
									.'<td><span title="'.$map['Type']['FullName'].'">'.$map['Type']['Name'].'</span></td>'
									.'<td>'.$map['Author'].'</td>'
									.'<td class="checkbox"><input type="checkbox" name="map[]" value="'.$map['FileName'].'" /></td>'
								.'</tr>';
								$i++;
							}
						}
						else{
							$showMapList .= '<tr class="no-line"><td class="center" colspan="4">'; if( is_array($mapsList) ){ $showMapList .= $mapsList['lst']; }else{ $showMapList .= $mapsList; } $showMapList .= '</td></tr>';
						}
						
						
						// Affichage
						echo $showMapList;
					?>
				</tbody>
			</table>
		</div>
		
		<div class="options" data-mapisused="<?php echo Utils::t('The map,is currently used by the server.'); ?>">
			<div class="fleft">
				<span class="nb-line"><?php if( is_array($mapsList) ){ echo $mapsList['nbm']['count'].' '.$mapsList['nbm']['title']; } ?></span>
			</div>
			<div class="fright">
				<div class="selected-files-label locked">
					<span class="selected-files-title"><?php echo Utils::t('For the selection'); ?></span>
					<span class="selected-files-count">(0)</span>
					<div class="selected-files-option">
						<input class="button dark" type="submit" name="deleteMap" id="deleteMap" value="<?php echo Utils::t('Delete'); ?>" data-confirm="<?php echo Utils::t('Do you really want to remove this selection?'); ?>" />
						<input class="button dark" type="button" name="moveMap" id="moveMap" value="<?php echo Utils::t('Move'); ?>" />
						<input class="button dark" type="button" name="renameMap" id="renameMap" value="<?php echo Utils::t('Rename'); ?>" />
						<input class="button dark" type="submit" name="downloadMap" id="downloadMap" value="<?php echo Utils::t('Download'); ?>" />
						<input class="button dark" type="submit" name="insertMap" id="insertMap" value="<?php echo Utils::t('Insert'); ?>" />
						<input class="button dark" type="submit" name="addMap" id="addMap" value="<?php echo Utils::t('Add'); ?>" />
					</div>
				</div>
			</div>
			<div id="form-rename-map" class="option-form" hidden="hidden" data-cancel="<?php echo Utils::t('Cancel'); ?>" data-rename="<?php echo Utils::t('Rename'); ?>" data-autorename="<?php echo Utils::t('Replace the special characters'); ?>"></div>
			<div id="form-move-map" class="option-form" hidden="hidden" data-cancel="<?php echo Utils::t('Cancel'); ?>" data-move="<?php echo Utils::t('Move'); ?>" data-inthefolder="<?php echo Utils::t('in the folder:'); ?>" data-root="<?php echo Utils::t('Root'); ?>"></div>
		</div>
		<?php if(SERVER_MATCHSET){ ?>
			<div class="fleft options-checkbox">
				<input class="text inline" type="checkbox" name="SaveCurrentMatchSettings" id="SaveCurrentMatchSettings"<?php if(AdminServConfig::AUTOSAVE_MATCHSETTINGS === true){ echo ' checked="checked"'; } ?> value="" /><label for="SaveCurrentMatchSettings" title="<?php echo SERVER_MATCHSET; ?>"><?php echo Utils::t('Save the current MatchSettings'); ?></label>
			</div>
		<?php } ?>
		</form>
	</section>
</section>