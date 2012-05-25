<?php
	// LECTURE
	$directoryList = Folder::getArborescence($mapsDirectoryPath, AdminServConfig::$MAPS_HIDDEN_FOLDERS, substr_count($mapsDirectoryPath, '/'));
	$matchSetting = array();
	// Édition
	if( isset($_GET['f']) && $_GET['f'] != null ){
		$pageTitle = 'Éditer';
		$matchSetting['name'] = $_GET['f'];
		$matchSetting += AdminServ::getMatchSettingsData($mapsDirectoryPath.$directory.$matchSetting['name']);
		if( isset($matchSetting['maps']) ){
			$maps = AdminServ::getMapListFromMatchSetting($matchSetting['maps']);
			$matchSetting['nbm'] = $maps['nbm']['count'];
			$_SESSION['adminserv']['matchset_maps_selected'] = $maps;
		}
		else{
			$matchSetting['nbm'] = 0;
		}
	}
	else{
		$pageTitle = 'Créer';
		$matchSetting['name'] = 'match_settings';
		$gameInfos = AdminServ::getGameInfos();
		$matchSetting['gameinfos'] = $gameInfos['next'];
		$matchSetting['hotseat'] = array(
			'GameMode' => 1,
			'TimeLimit' => 300000,
			'RoundsCount' => 5
		);
		$matchSetting['filter'] = array(
			'IsLan' => 1,
			'IsInternet' => 1,
			'IsSolo' => 0,
			'IsHotseat' => 1,
			'SortIndex' => 1000,
			'RandomMapOrder' => 0,
			'ForceDefaultGameMode' => 1
		);
		$matchSetting['StartIndex'] = 0;
		$matchSetting['nbm'] = 0;
	}
	
	
	// ENREGISTREMENT
	if( isset($_POST['savematchsetting']) ){
		// Jeu
		if(SERVER_VERSION_NAME == 'TmForever'){
			$CupRoundsPerMap = 'cup_roundsperchallenge';
			$StructMap = 'challenge';
		}
		else{
			$CupRoundsPerMap = 'CupRoundsPerMap';
			$StructMap = 'map';
		}
		
		// Filename
		$matchSettingName = Str::replaceChars($_POST['matchSettingName']);
		$matchSettingExtension = File::getExtension($matchSettingName);
		if($matchSettingExtension == 'txt' || $matchSettingExtension == 'xml'){
			$filename = $mapsDirectoryPath.$matchSettingName;
		}
		else{
			$filename = $mapsDirectoryPath.$matchSettingName.'.txt';
		}
		
		$struct = array();
		
		// Gameinfos
		$gameinfos = AdminServ::getGameInfosStructFromPOST();
		$struct['gameinfos'] = array(
			'game_mode' => $gameinfos['GameMode'],
			'chat_time' => $gameinfos['ChatTime'],
			'finishtimeout' => $gameinfos['FinishTimeout'],
			'allwarmupduration' => $gameinfos['AllWarmUpDuration'],
			'disablerespawn' => $gameinfos['DisableRespawn'],
			'forceshowallopponents' => $gameinfos['ForceShowAllOpponents'],
			'script_name' => $gameinfos['ScriptName'],
			'rounds_pointslimit' => $gameinfos['RoundsPointsLimit'],
			'rounds_usenewrules' => $gameinfos['RoundsUseNewRules'],
			'rounds_forcedlaps' => $gameinfos['RoundsForcedLaps'],
			'rounds_pointslimitnewrules' => $gameinfos['RoundsPointsLimitNewRules'],
			'team_pointslimit' => $gameinfos['TeamPointsLimit'],
			'team_maxpoints' => $gameinfos['TeamMaxPoints'],
			'team_usenewrules' => $gameinfos['TeamUseNewRules'],
			'team_pointslimitnewrules' => $gameinfos['TeamPointsLimitNewRules'],
			'timeattack_limit' => $gameinfos['TimeAttackLimit'],
			'timeattack_synchstartperiod' => $gameinfos['TimeAttackSynchStartPeriod'],
			'laps_nblaps' => $gameinfos['LapsNbLaps'],
			'laps_timelimit' => $gameinfos['LapsTimeLimit'],
			'cup_pointslimit' => $gameinfos['CupPointsLimit'],
			$CupRoundsPerMap => $gameinfos['CupRoundsPerMap'],
			'cup_nbwinners' => $gameinfos['CupNbWinners'],
			'cup_warmupduration' => $gameinfos['CupWarmUpDuration']
		);
		if(SERVER_VERSION_NAME == 'TmForever'){
			unset($struct['gameinfos']['script_name']);
		}
		
		// HotSeat
		$struct['hotseat'] = array(
			'game_mode' => intval($_POST['hotSeatGameMode']),
			'time_limit' => TimeDate::secToMillisec( intval($_POST['hotSeatTimeLimit']) ),
			'rounds_count' => intval($_POST['hotSeatCountRound'])
		);
		
		// Filter
		$struct['filter'] = array(
			'is_lan' => array_key_exists('filterIsLan', $_POST),
			'is_internet' => array_key_exists('filterIsInternet', $_POST),
			'is_solo' => array_key_exists('filterIsSolo', $_POST),
			'is_hotseat' => array_key_exists('filterIsHotSeat', $_POST),
			'sort_index' => intval($_POST['filterSortIndex']),
			'random_map_order' => array_key_exists('filterRandomMaps', $_POST),
			'force_default_gamemode' => intval($_POST['filterDefaultGameMode']),
		);
		
		// Maps
		$struct['startindex'] = 1;
		if( isset($_SESSION['adminserv']['matchset_maps_selected']) ){
			$maps = $_SESSION['adminserv']['matchset_maps_selected']['lst'];
			if( isset($maps) && is_array($maps) && count($maps) > 0 ){
				foreach($maps as $id => $values){
					$struct[$StructMap][$values['FileName']] = $values['UId'];
				}
			}
		}
		
		
		// Enregistrement
		if( ($result = AdminServ::saveMatchSettings($filename, $struct)) !== true ){
			AdminServ::error('Impossible d\'enregistrer le MatchSettings : '.$matchSettingName.' ('.$result.')');
		}
		else{
			AdminServ::info('Le MatchSettings "'.$matchSettingName.'" a bien été créé dans le dossier : '.$mapsDirectoryPath);
			Utils::redirection(false, '?p='.USER_PAGE);
		}
	}
	else{
		if( !isset($_GET['f']) ){
			unset($_SESSION['adminserv']['matchset_maps_selected']);
		}
	}
	
	
	// HTML
	AdminServUI::getHeader();
?>
<section class="maps hasMenu hasFolders">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMenuList(ExtensionConfig::$MAPSMENU); ?>
	</section>
	
	<section class="cadre middle folders">
		<?php echo $mapsDirectoryList; ?>
	</section>
	
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
	<section class="cadre right creatematchset">
		<h1><?php echo $pageTitle; ?> un MatchSettings</h1>
		<div class="title-detail">
			<ul>
				<li class="last path"><?php echo $mapsDirectoryPath.$directory; ?></li>
			</ul>
		</div>
		
		<h2>Nom du MatchSettings</h2>
		<input class="text width3" type="text" name="matchSettingName" id="matchSettingName" value="<?php echo $matchSetting['name']; ?>" />
		
		<h2>Maps</h2>
		<div class="content maps">
			<fieldset>
				<div class="mapsSelection">
					<?php
						$mapsSelectList = '<select name="mapsDirectoryList" id="mapsDirectoryList">';
						$mapsSelectList .= '<option value="'.$mapsDirectoryPath.'">Racine</option>';
						if( count($directoryList) > 0 ){
							foreach($directoryList as $dir){
								$mapsSelectList .= '<option value="'.$dir['path'].'">'.$dir['level'].$dir['name'].'</option>';
							}
						}
						$mapsSelectList .= '</select>';
						echo $mapsSelectList;
					?>
					<input class="button light" type="button" name="mapImportSelection" id="mapImportSelection" value="Faire une sélection" />
					<input class="button light" type="button" name="mapImport" id="mapImport" value="Importer tout le dossier" />
					<div id="mapImportSelectionDialog" data-title="Faire une sélection" data-select="Sélectionner" hidden="hidden">
						<table>
							<thead>
								<tr>
									<th class="thleft">Map</th>
									<th>Environnement</th>
									<th>Auteur</th>
									<th class="thright"><input type="checkbox" name="checkAllMapImport" id="checkAllMapImport" value="" /></th>
								</tr>
								<tr class="table-separation"></tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="mapsSelected">
					<p>Maps sélectionnées pour le MatchSettings : <span id="nbMapSelected"><?php echo $matchSetting['nbm']; ?></span></p>
					<input class="button light" type="button" name="mapSelection" id="mapSelection" value="Voir la sélection du MatchSettings" />
					<div id="mapSelectionDialog" data-title="Sélection du MatchSettings" data-remove="Enlever cette map de la sélection" data-close="Fermer" hidden="hidden">
						<table>
							<thead>
								<tr>
									<th class="thleft">Map</th>
									<th>Environnement</th>
									<th>Auteur</th>
									<th class="thright"></th>
								</tr>
								<tr class="table-separation"></tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</fieldset>
		</div>
		
		<h2>Informations de jeu</h2>
		<div class="content gameinfos">
			<?php
				// Général
				echo AdminServUI::getGameInfosGeneralForm(null, $matchSetting['gameinfos']);
				// Modes de jeux
				echo AdminServUI::getGameInfosGameModeForm(null, $matchSetting['gameinfos']);
			?>
		</div>
		
		<h2>HotSeat</h2>
		<div class="content">
			<fieldset>
				<table>
					<tr>
						<td class="key"><label for="hotSeatGameMode">Mode de jeu</label></td>
						<td class="value">
							<select class="width2" name="hotSeatGameMode" id="hotSeatGameMode">
								<?php echo AdminServUI::getGameModeList($matchSetting['hotseat']['GameMode']); ?>
							</select>
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="hotSeatTimeLimit">Limite de temps</label></td>
						<td class="value">
							<input class="text width2" type="text" name="hotSeatTimeLimit" id="hotSeatTimeLimit" value="<?php echo TimeDate::millisecToSec($matchSetting['hotseat']['TimeLimit']); ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="hotSeatCountRound">Nombre de round</label></td>
						<td class="value">
							<input class="text width2" type="text" name="hotSeatCountRound" id="hotSeatCountRound" value="<?php echo $matchSetting['hotseat']['RoundsCount']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
		</div>
		
		<h2>Filtres</h2>
		<div class="content">
			<fieldset>
				<table>
					<tr>
						<td class="key"><label for="filterIsLan">Lan</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsLan" id="filterIsLan"<?php if($matchSetting['filter']['IsLan']){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterIsInternet">Internet</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsInternet" id="filterIsInternet"<?php if($matchSetting['filter']['IsInternet']){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterIsSolo">Solo</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsSolo" id="filterIsSolo"<?php if($matchSetting['filter']['IsSolo']){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterIsHotSeat">HotSeat</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsHotSeat" id="filterIsHotSeat"<?php if($matchSetting['filter']['IsHotseat']){ echo ' checked="checked"'; } ?>" value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterSortIndex">Index de tri</label></td>
						<td class="value">
							<input class="text width2" type="text" name="filterSortIndex" id="filterSortIndex" value="<?php echo $matchSetting['filter']['SortIndex']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterRandomMaps">Ordre des maps aléatoire</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterRandomMaps" id="filterRandomMaps"<?php if($matchSetting['filter']['RandomMapOrder']){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterDefaultGameMode">Mode de jeu par défaut</label></td>
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
			<input class="button light" type="submit" name="savematchsetting" id="savematchsetting" value="Enregistrer" />
		</div>
	</section>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>