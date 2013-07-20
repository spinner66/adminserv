<section class="maps hasMenu hasFolders">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMapsMenuList(); ?>
	</section>
	
	<section class="cadre middle folders">
		<?php echo $mapsDirectoryList; ?>
	</section>
	
	<form method="post" action="?p=<?php echo USER_PAGE . $hasDirectory; ?>">
	<section class="cadre right creatematchset">
		<h1><?php echo $pageTitle.' '.Utils::t('a MatchSettings'); ?></h1>
		<div class="title-detail">
			<ul>
				<li class="last path"><?php echo $mapsDirectoryPath.$directory; ?></li>
			</ul>
		</div>
		
		<h2><?php echo Utils::t('MatchSettings name'); ?></h2>
		<input class="text" type="text" name="matchSettingName" id="matchSettingName" value="<?php echo $matchSetting['name']; ?>" />
		<p class="ui-state-error" id="matchSettingNameExists" hidden="hidden"><span class="ui-icon ui-icon-alert"></span><?php echo Utils::t('The MatchSettings name already exist! It will be overwritten.'); ?></p>
		
		<h2><?php echo Utils::t('Maps'); ?></h2>
		<div class="content maps">
			<fieldset>
				<div class="mapsSelection">
					<?php
						$mapsSelectList = '<select name="mapsDirectoryList" id="mapsDirectoryList">';
						$mapsSelectList .= '<option value="currentServerSelection">'.Utils::t('Server selection').'</option>';
						$mapsSelectList .= '<option value="'.$mapsDirectoryPath.'">'.Utils::t('Root').'</option>';
						if( count($directoryList) > 0 ){
							foreach($directoryList as $dir){
								$mapsSelectList .= '<option value="'.$dir['path'].'">'.$dir['level'].$dir['name'].'</option>';
							}
						}
						$mapsSelectList .= '</select>';
						echo $mapsSelectList;
					?>
					<input class="button light" type="button" name="mapImportSelection" id="mapImportSelection" value="<?php echo Utils::t('Make selection'); ?>" />
					<input class="button light" type="button" name="mapImport" id="mapImport" value="<?php echo Utils::t('Import all folder'); ?>" />
					<div id="mapImportSelectionDialog" data-title="<?php echo Utils::t('Make selection'); ?>" data-select="<?php echo Utils::t('Select'); ?>" hidden="hidden">
						<table>
							<thead>
								<tr>
									<th class="thleft"><?php echo Utils::t('Map'); ?></th>
									<th><?php echo Utils::t('Environment'); ?></th>
									<th><?php echo Utils::t('Type'); ?></th>
									<th><?php echo Utils::t('Author'); ?></th>
									<th class="thright"><input type="checkbox" name="checkAllMapImport" id="checkAllMapImport" value="" /></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="mapsSelected">
					<p><?php echo Utils::t('MatchSettings selected maps:'); ?> <span id="nbMapSelected"><?php echo $matchSetting['nbm']; ?></span></p>
					<input class="button light" type="button" name="mapSelection" id="mapSelection" value="<?php echo Utils::t('View the MatchSettings selection'); ?>" />
					<div id="mapSelectionDialog" data-title="<?php echo Utils::t('MatchSettings selection'); ?>" data-remove="<?php echo Utils::t('Remove map from the selection'); ?>" data-close="<?php echo Utils::t('Close'); ?>" hidden="hidden">
						<table>
							<thead>
								<tr>
									<th class="thleft"><?php echo Utils::t('Map'); ?></th>
									<th><?php echo Utils::t('Environment'); ?></th>
									<th><?php echo Utils::t('Type'); ?></th>
									<th><?php echo Utils::t('Author'); ?></th>
									<th class="thright"></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</fieldset>
		</div>
		
		<h2><?php echo Utils::t('Game information'); ?></h2>
		<div class="content gameinfos">
			<?php
				// Général
				echo AdminServUI::getGameInfosGeneralForm($matchSetting['gameinfos']);
				// Modes de jeux
				echo AdminServUI::getGameInfosGameModeForm($matchSetting['gameinfos']);
			?>
		</div>
		
		<h2><?php echo Utils::t('HotSeat'); ?></h2>
		<div class="content">
			<fieldset>
				<table>
					<tr>
						<td class="key"><label for="hotSeatGameMode"><?php echo Utils::t('Game mode'); ?></label></td>
						<td class="value">
							<select class="width2" name="hotSeatGameMode" id="hotSeatGameMode">
								<?php echo AdminServUI::getGameModeList($matchSetting['hotseat']['GameMode']); ?>
							</select>
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="hotSeatTimeLimit"><?php echo Utils::t('Time limit'); ?></label></td>
						<td class="value">
							<input class="text width2" type="number" min="0" name="hotSeatTimeLimit" id="hotSeatTimeLimit" value="<?php echo TimeDate::millisecToSec($matchSetting['hotseat']['TimeLimit']); ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="hotSeatCountRound"><?php echo Utils::t('Rounds count'); ?></label></td>
						<td class="value">
							<input class="text width2" type="number" min="0" name="hotSeatCountRound" id="hotSeatCountRound" value="<?php echo $matchSetting['hotseat']['RoundsCount']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
		</div>
		
		<h2><?php echo Utils::t('Filter'); ?></h2>
		<div class="content">
			<fieldset>
				<table>
					<tr>
						<td class="key"><label for="filterIsLan"><?php echo Utils::t('Lan'); ?></label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsLan" id="filterIsLan"<?php if($matchSetting['filter']['IsLan']){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterIsInternet"><?php echo Utils::t('Internet'); ?></label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsInternet" id="filterIsInternet"<?php if($matchSetting['filter']['IsInternet']){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterIsSolo"><?php echo Utils::t('Solo'); ?></label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsSolo" id="filterIsSolo"<?php if($matchSetting['filter']['IsSolo']){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterIsHotSeat"><?php echo Utils::t('HotSeat'); ?></label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsHotSeat" id="filterIsHotSeat"<?php if($matchSetting['filter']['IsHotseat']){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterSortIndex"><?php echo Utils::t('Sort index'); ?></label></td>
						<td class="value">
							<input class="text width2" type="number" min="0" name="filterSortIndex" id="filterSortIndex" value="<?php echo $matchSetting['filter']['SortIndex']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterRandomMaps"><?php echo Utils::t('Random map order'); ?></label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterRandomMaps" id="filterRandomMaps"<?php if($matchSetting['filter']['RandomMapOrder']){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterDefaultGameMode"><?php echo Utils::t('Default game mode'); ?></label></td>
						<td class="value">
							<select class="width2" name="filterDefaultGameMode" id="filterDefaultGameMode">
								<?php echo AdminServUI::getGameModeList($matchSetting['filter']['ForceDefaultGameMode']); ?>
							</select>
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
		</div>
		
		<div class="fright save">
			<input class="button light" type="submit" name="savematchsetting" id="savematchsetting" data-nomap="<?php echo Utils::t('No map selected for the MatchSettings.'); ?>" value="<?php echo Utils::t('Save'); ?>" />
		</div>
	</section>
	</form>
</section>