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
	}
	else if( isset($_POST['chooseNextMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		if( !$client->query($queries['chooseNextMap'], $_POST['map']) ){
			AdminServ::error();
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
		<?php echo AdminServUI::getMenuList(ExtensionConfig::$MAPSMENU); ?>
	</section>
	
	<section class="cadre right list">
		<h1>Liste</h1>
		<div class="title-detail">
			<ul>
				<li><a href="">Mode détail</a></li>
				<li><input type="checkbox" name="checkAll" id="checkAll" value="" /></li>
			</ul>
		</div>
		
		<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<div id="maplist">
			<table>
				<thead>
					<tr>
						<th class="thleft"><a href="?sort=name">Map</a></th>
						<th><a href="?sort=env">Environnement</a></th>
						<th><a href="?sort=author">Auteur</a></th>
						<th class="thright"></th>
					</tr>
					<tr class="table-separation"></tr>
				</thead>
				<tbody>
				<?php
					$showMapList = null;
					
					// Liste des joueurs
					if( is_array($mapsList) && count($mapsList) > 0 ){
						$i = 0;
						foreach($mapsList['lst'] as $id => $map){
							// Ligne
							$showMapList .= '<tr class="'; if($i%2){ $showMapList .= 'even'; }else{ $showMapList .= 'odd'; } if($id == $mapsList['cid']){ $showMapList .= ' current'; } $showMapList .= '">'
								.'<td class="imgleft"><img src="'.$mapsList['cfg']['path_rsc'].'images/16/map.png" alt="" /><span title="'.$map['FileName'].'">'.$map['Name'].'</span></td>'
								.'<td class="imgcenter"><img src="'.$mapsList['cfg']['path_rsc'].'images/env/'.strtolower($map['Environnement']).'.png" alt="" />'.$map['Environnement'].'</td>'
								.'<td>'.$map['Author'].'</td>'
								.'<td class="checkbox">'; if($id != $mapsList['cid']){ $showMapList .= '<input type="checkbox" name="map[]" value="'.$map['FileName'].'" />'; } $showMapList .= '</td>'
							.'</tr>';
							$i++;
						}
					}
					else{
						$showMapList .= '<tr class="no-line"><td class="center" colspan="4">'.$mapsList['lst'].'</td></tr>';
					}
					
					// Affichage
					echo $showMapList;
				?>
				</tbody>
			</table>
		</div>
		
		<div class="options">
			<div class="fleft">
				<span class="nb-line"><?php echo $mapsList['nbm']; ?></span>
			</div>
			<div class="fright">
				<div class="selected-files-label locked">
					<span class="selected-files-title">Pour la sélection</span>
					<span class="selected-files-count">(0)</span>
					<div class="selected-files-option">
						<input class="button dark" type="submit" name="removeMap" id="removeMap" value="Supprimer" />
						<input class="button dark" type="submit" name="chooseNextMap" id="chooseNextMap" value="Déplacer après la map en cours" />
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