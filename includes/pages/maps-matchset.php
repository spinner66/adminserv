<?php
	// MATCHSETLIST
	$matchsetList = AdminServ::getLocalMatchSettingList($mapsDirectoryPath.$directory);
	
	
	// ACTIONS
	if( isset($_POST['saveMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('SaveMatchSettings', $mapsDirectoryPath.$directory.$matchset) ){
				AdminServ::error();
				break;
			}
			else{
				AdminServLogs::add('action', 'Save matchsettings: '.$matchset);
			}
		}
		Utils::redirection(false, '?p='. USER_PAGE .$hasDirectory);
	}
	else if( isset($_POST['loadMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('LoadMatchSettings', $mapsDirectoryPath.$directory.$matchset) ){
				AdminServ::error();
				break;
			}
			else{
				AdminServLogs::add('action', 'Load matchsettings: '.$matchset);
			}
		}
		Utils::redirection(false, '?p='. USER_PAGE .$hasDirectory);
	}
	else if( isset($_POST['addMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('AppendPlaylistFromMatchSettings', $mapsDirectoryPath.$directory.$matchset) ){
				AdminServ::error();
				break;
			}
			else{
				AdminServLogs::add('action', 'Append playlist from matchsettings: '.$matchset);
			}
		}
	Utils::redirection(false, '?p='. USER_PAGE .$hasDirectory);
	}
	else if( isset($_POST['insertMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('InsertPlaylistFromMatchSettings', $mapsDirectoryPath.$directory.$matchset) ){
				AdminServ::error();
				break;
			}
			else{
				AdminServLogs::add('action', 'Insert playlist from matchsettings: '.$matchset);
			}
		}
		Utils::redirection(false, '?p='. USER_PAGE .$hasDirectory);
	}
	else if( isset($_POST['editMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		AdminServLogs::add('action', 'Edit matchsettings: '.$_POST['matchset'][0]);
		// Redirection sur la page de création d'un matchsettings
		Utils::redirection(false, '?p=maps-creatematchset'.$hasDirectory.'&f='.$_POST['matchset'][0]);
	}
	else if( isset($_POST['deleteMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !File::delete($mapsDirectoryPath.$directory.$matchset) ){
				AdminServ::error(Utils::t('Unable to delete the playlist').' : '.$matchset);
				break;
			}
			else{
				AdminServLogs::add('action', 'Delete matchsettings: '.$matchset);
			}
		}
		
		$hasDirectory = null;
		if($directory){
			$hasDirectory = '&d='.$directory;
		}
		Utils::redirection(false, '?p='. USER_PAGE .$hasDirectory);
	}
	
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="maps hasMenu hasFolders">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMapsMenuList(); ?>
	</section>
	
	<section class="cadre middle folders">
		<?php echo $mapsDirectoryList; ?>
	</section>
	
	<section class="cadre right matchset">
		<h1><?php echo Utils::t('MatchSettings'); ?></h1>
		<div class="title-detail">
			<ul>
				<li class="path"><?php echo $mapsDirectoryPath.$directory; ?></li>
				<li class="last"><input type="checkbox" name="checkAll" id="checkAll" value="" /></li>
			</ul>
		</div>
		
		<form method="post" action="?p=<?php echo USER_PAGE; if($directory){ echo '&amp;d='.$directory; } ?>">
		<div id="matchsetlist">
			<table>
				<thead>
					<tr>
						<th class="thleft"><a href="?sort=name"><?php echo Utils::t('Name'); ?></a></th>
						<th><a href="?sort=nbm"><?php echo Utils::t('Contains'); ?></a></th>
						<th><a href="?sort=mtime"><?php echo Utils::t('Modified'); ?></a></th>
						<th class="thright"></th>
					</tr>
				</thead>
				<tbody>
					<tr class="table-separation"><td colspan="4"></td></tr>
					<?php
						$showMatchsetList = null;
						
						// Liste des matchsettings
						if( is_array($matchsetList['lst']) && count($matchsetList['lst']) > 0 ){
							$i = 0;
							foreach($matchsetList['lst'] as $id => $matchset){
								// Ligne
								$showMatchsetList .= '<tr class="'; if($i%2){ $showMatchsetList .= 'even'; }else{ $showMatchsetList .= 'odd'; } if($matchset['Recent']){ $showMatchsetList .= ' recent'; } $showMatchsetList .= '">'
									.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/finishgrey.png" alt="" /><span title="'.$matchset['FileName'].'">'.$matchset['Name'].'</span></td>'
									.'<td>'.$matchset['Nbm'].'</td>'
									.'<td>'.date('d/m/Y', $matchset['Mtime']).'</td>'
									.'<td class="checkbox"><input type="checkbox" name="matchset[]" value="'.$matchset['FileName'].'" /></td>'
								.'</tr>';
								$i++;
							}
						}
						else{
							$showMatchsetList .= '<tr class="no-line"><td class="center" colspan="4">'.$matchsetList['lst'].'</td></tr>';
						}
						
						// Affichage
						echo $showMatchsetList;
					?>
				</tbody>
			</table>
		</div>
		
		<div class="options">
			<div class="fleft">
				<span class="nb-line"><?php echo $matchsetList['nbm']['count'].' '.$matchsetList['nbm']['title']; ?></span>
			</div>
			<div class="fright">
				<div class="selected-files-label locked">
					<span class="selected-files-title"><?php echo Utils::t('For the selection'); ?></span>
					<span class="selected-files-count">(0)</span>
					<div class="selected-files-option">
						<input class="button dark" type="submit" name="deleteMatchset" id="deleteMatchset" value="<?php echo Utils::t('Delete'); ?>" />
						<input class="button dark" type="submit" name="editMatchset" id="editMatchset" value="<?php echo Utils::t('Edit'); ?>" />
						<input class="button dark" type="submit" name="insertMatchset" id="insertMatchset" value="<?php echo Utils::t('Insert'); ?>" />
						<input class="button dark" type="submit" name="addMatchset" id="addMatchset" value="<?php echo Utils::t('Add'); ?>" />
						<input class="button dark" type="submit" name="loadMatchset" id="loadMatchset" value="<?php echo Utils::t('Load'); ?>" />
						<input class="button dark" type="submit" name="saveMatchset" id="saveMatchset" value="<?php echo Utils::t('Save '); ?>" />
					</div>
				</div>
			</div>
		</div>
		</form>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>