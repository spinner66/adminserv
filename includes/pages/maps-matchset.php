<?php
	// MATCHSETLIST
	$matchsetList = AdminServ::getLocalMatchSettingList($mapsDirectoryPath.$directory);
	
	
	// ACTIONS
	if( isset($_POST['saveMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('SaveMatchSettings', $mapsDirectoryPath.$matchset) ){
				AdminServ::error();
				break;
			}
		}
	}
	else if( isset($_POST['loadMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('LoadMatchSettings', $mapsDirectoryPath.$matchset) ){
				AdminServ::error();
				break;
			}
		}
	}
	else if( isset($_POST['addMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('AppendPlaylistFromMatchSettings', $mapsDirectoryPath.$matchset) ){
				AdminServ::error();
				break;
			}
		}
	}
	else if( isset($_POST['insertMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('InsertPlaylistFromMatchSettings', $mapsDirectoryPath.$matchset) ){
				AdminServ::error();
				break;
			}
		}
	}
	else if( isset($_POST['editMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		if($directory){
			$hasDirectory = '&d='.$directory;
		}
		else{
			$hasDirectory = null;
		}
		Utils::redirection(false, '?p=maps-creatematchset'.$hasDirectory.'&f='.$_POST['matchset'][0]);
	}
	
	
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
	
	<section class="cadre right matchset">
		<h1>MatchSettings</h1>
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
						<th class="thleft"><a href="?sort=name">Nom</a></th>
						<th><a href="?sort=mtime">Modifié le</a></th>
						<th class="thright"></th>
					</tr>
					<tr class="table-separation"></tr>
				</thead>
				<tbody>
				<?php
					$showMatchsetList = null;
					
					// Liste des matchsettings
					if( is_array($matchsetList['lst']) && count($matchsetList['lst']) > 0 ){
						$i = 0;
						foreach($matchsetList['lst'] as $id => $matchset){
							// Ligne
							$showMatchsetList .= '<tr class="'; if($i%2){ $showMatchsetList .= 'even'; }else{ $showMatchsetList .= 'odd'; } if($id == $matchset['Recent']){ $showMatchsetList .= ' recent'; } $showMatchsetList .= '">'
								.'<td class="imgleft"><img src="'.$matchsetList['cfg']['path_rsc'].'images/16/finishgrey.png" alt="" /><span title="'.$matchset['FileName'].'">'.$matchset['Name'].'</span></td>'
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
				<span class="nb-line"><?php echo $matchsetList['nbm']; ?></span>
			</div>
			<div class="fright">
				<div class="selected-files-label locked">
					<span class="selected-files-title">Pour la sélection</span>
					<span class="selected-files-count">(0)</span>
					<div class="selected-files-option">
						<input class="button dark" type="submit" name="editMatchset" id="editMatchset" value="Éditer" />
						<input class="button dark" type="submit" name="insertMatchset" id="insertMatchset" value="Insérer" />
						<input class="button dark" type="submit" name="addMatchset" id="addMatchset" value="Ajouter" />
						<input class="button dark" type="submit" name="loadMatchset" id="loadMatchset" value="Charger" />
						<input class="button dark" type="submit" name="saveMatchset" id="saveMatchset" value="Sauvegarder" />
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