<?php
	// GAME
	if(SERVER_VERSION_NAME == 'TmForever'){
		$queries = array(
			'removeMap' => 'RemoveChallengeList',
			'chooseNextMap' => 'ChooseNextChallengeList'
		);
	}
	else{
		$queries = array(
			'removeMap' => 'RemoveMapList',
			'chooseNextMap' => 'ChooseNextMapList'
		);
	}
	
	// ACTIONS
	if( isset($_POST['removeMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		if( !$client->query($queries['removeMap'], $_POST['map']) ){
			AdminServ::error();
		}
		else{
			AdminServLogs::add('action', 'Remove map ('.count($_POST['map']).')');
			Utils::redirection(false, '?p='.USER_PAGE);
		}
	}
	else if( isset($_POST['chooseNextMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		if( !$client->query($queries['chooseNextMap'], $_POST['map']) ){
			AdminServ::error();
		}
		else{
			AdminServLogs::add('action', 'Choose next map ('.count($_POST['map']).')');
			Utils::redirection(false, '?p='.USER_PAGE);
		}
	}
	
	// MAPLIST
	$mapsList = AdminServ::getMapList();
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="maps hasMenu">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMapsMenuList(); ?>
	</section>
	
	<section class="cadre right list">
		<h1><?php echo Utils::t('List'); ?></h1>
		<div class="title-detail">
			<ul>
				<?php if(is_array($mapsList) && count($mapsList) > 0 && count($mapsList['lst']) > 25){ ?>
					<li><a id="scrollToCurrentMap" href="#currentMap"><?php echo Utils::t('Go to the current map'); ?></a></li>
				<?php } ?>
				<li><a id="detailMode" href="." data-statusmode="<?php echo USER_MODE_MAPS; ?>" data-textdetail="<?php echo Utils::t('Detailed mode'); ?>" data-textsimple="<?php echo Utils::t('Simple mode'); ?>"><?php if(USER_MODE_MAPS == 'detail'){ echo Utils::t('Simple mode'); }else{ echo Utils::t('Detailed mode'); } ?></a></li>
				<li class="last"><input type="checkbox" name="checkAll" id="checkAll" value=""<?php if( !is_array($mapsList['lst']) ){ echo ' disabled="disabled"'; } ?> /></li>
			</ul>
		</div>
		
		<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<div id="maplist">
			<table>
				<thead>
					<tr>
						<th class="thleft"><?php echo Utils::t('Map'); ?></th>
						<th><?php echo Utils::t('Environment'); ?></th>
						<th><?php echo Utils::t('Author'); ?></th>
						<th class="detailModeTh"<?php if(USER_MODE_MAPS == 'simple'){ echo ' hidden="hidden"'; } ?>><?php echo Utils::t('Gold time'); ?></th>
						<th class="detailModeTh"<?php if(USER_MODE_MAPS == 'simple'){ echo ' hidden="hidden"'; } ?>><?php echo Utils::t('Cost'); ?></th>
						<th class="thright"></th>
					</tr>
				</thead>
				<tbody>
					<tr class="table-separation"><td colspan="6"></td></tr>
					<?php
						$showMapList = null;
						
						// Liste des joueurs
						if( is_array($mapsList['lst']) && count($mapsList['lst']) > 0 ){
							$pathRessources = AdminServConfig::$PATH_RESOURCES;
							$i = 0;
							foreach($mapsList['lst'] as $id => $map){
								// Ligne
								$showMapList .= '<tr'; if($id == $mapsList['cid']){ $showMapList .= ' id="currentMap"'; } $showMapList .= ' class="'; if($i%2){ $showMapList .= 'even'; }else{ $showMapList .= 'odd'; } if($id == $mapsList['cid']){ $showMapList .= ' current'; } $showMapList .= '">'
									.'<td class="imgleft"><img src="'.$pathRessources.'images/16/map.png" alt="" />'
										.'<span title="'.$map['FileName'].'">'.$map['Name'].'</span>';
										if(USER_MODE_MAPS == 'detail'){
											$showMapList .= '<span class="detailModeTd">'.$map['UId'].'</span>';
										}
									$showMapList .= '</td>'
									.'<td class="imgcenter"><img src="'.$pathRessources.'images/env/'.strtolower($map['Environnement']).'.png" alt="" />'.$map['Environnement'].'</td>'
									.'<td>'.$map['Author'].'</td>'
									.'<td'; if(USER_MODE_MAPS == 'simple'){ $showMapList .= ' hidden="hidden"'; } $showMapList .= '>'.$map['GoldTime'].'</td>'
									.'<td'; if(USER_MODE_MAPS == 'simple'){ $showMapList .= ' hidden="hidden"'; } $showMapList .= '>'.$map['CopperPrice'].'</td>'
									.'<td class="checkbox">'; if($id != $mapsList['cid']){ $showMapList .= '<input type="checkbox" name="map[]" value="'.$map['FileName'].'" />'; } $showMapList .= '</td>'
								.'</tr>';
								$i++;
							}
						}
						else{
							$showMapList .= '<tr class="no-line"><td class="center" colspan="6">'.$mapsList['lst'].'</td></tr>';
						}
						
						// Affichage
						echo $showMapList;
					?>
				</tbody>
			</table>
		</div>
		
		<div class="options">
			<div class="fleft">
				<span class="nb-line"><?php echo $mapsList['nbm']['count'].' '.$mapsList['nbm']['title']; ?></span>
			</div>
			<div class="fright">
				<div class="selected-files-label locked">
					<span class="selected-files-title"><?php echo Utils::t('For the selection'); ?></span>
					<span class="selected-files-count">(0)</span>
					<div class="selected-files-option">
						<input class="button dark" type="submit" name="removeMap" id="removeMap" value="<?php echo Utils::t('Delete'); ?>" />
						<input class="button dark" type="submit" name="chooseNextMap" id="chooseNextMap" value="<?php echo Utils::t('Move after the current map'); ?>" />
					</div>
				</div>
			</div>
		</div>
		
		<input type="hidden" id="currentSort" name="currentSort" value="" />
		</form>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>