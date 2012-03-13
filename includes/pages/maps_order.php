<?php
	// GAME
	if(SERVER_VERSION_NAME == 'TmForever'){
		$queries = array(
			'chooseNextMap' => 'ChooseNextChallengeList'
		);
	}
	else{
		$queries = array(
			'chooseNextMap' => 'ChooseNextMapList'
		);
	}
	
	// ACTIONS
	if( isset($_POST['save']) && isset($_POST['list']) && $_POST['list'] != null ){
		
		$list = explode(',', $_POST['list']);
		
		if( !$client->query($queries['chooseNextMap'], $list) ){
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
		<?php echo AdminServUI::getMenuList($menuList); ?>
	</section>
	
	<section class="cadre right order">
		<h1>Ordonner</h1>
		<form method="post" action="?p=maps-order">
			<h2>Tri automatique</h2>
			<div class="autoSortMode options-radio-inline">
				<ul>
					<li class="ui-state-default">
						<input class="text" type="radio" name="transferMode" id="transferModeAdd" value="add" />
						<div class="name">Nom</div>
						<div class="icon">
							<span class="ui-icon ui-icon-arrowthick-1-n"></span>
							<span class="ui-icon ui-icon-arrowthick-1-s"></span>
						</div>
					</li>
					<li class="ui-state-default">
						<input class="text" type="radio" name="transferMode" id="transferModeInsert" value="insert" />
						<div class="name">Environnement</div>
						<div class="icon">
							<span class="ui-icon ui-icon-arrowthick-1-n"></span>
							<span class="ui-icon ui-icon-arrowthick-1-s"></span>
						</div>
					</li>
					<li class="ui-state-default">
						<input class="text" type="radio" name="transferMode" id="transferModeLocal" value="local" />
						<div class="name">Auteur</div>
						<div class="icon">
							<span class="ui-icon ui-icon-arrowthick-1-n"></span>
							<span class="ui-icon ui-icon-arrowthick-1-s"></span>
						</div>
					</li>
					<li class="ui-state-default">
						<input class="text" type="radio" name="transferMode" id="transferModeLocal" value="local" />
						<div class="name">Aléatoire</div>
						<div class="icon">
							<span class="ui-icon ui-icon-arrowthick-1-n"></span>
							<span class="ui-icon ui-icon-arrowthick-1-s"></span>
						</div>
					</li>
				</ul>
			</div>
			
			<h2>Tri manuel</h2>
			<div class="content">
				<ul id="sortableMapList">
					<?php
						if( is_array($mapsList) && count($mapsList) > 0 ){
							$i = 0;
							foreach($mapsList['lst'] as $id => $map){
								echo '<li class="ui-state-default">'
									.'<div class="ui-icon ui-icon-arrowthick-2-n-s"></div>'
									.'<div class="order-map-name" title="'.$map['FileName'].'">'.$map['Name'].'</div>'
									.'<div class="order-map-env"><img src="'.$mapsList['cfg']['path_rsc'].'images/env/'.strtolower($map['Environnement']).'.png" alt="" />'.$map['Environnement'].'</div>'
									.'<div class="order-map-author"><img src="'.$mapsList['cfg']['path_rsc'].'images/16/challengeauthor.png" alt="" />'.$map['Author'].'</div>'
								.'</li>';
							}
						}
					?>
				</ul>
			</div>
			<div class="fright save">
				<input class="button light" type="button" id="reset" name="reset" value="Réinitialiser" />
				<input class="button light" type="submit" id="save" name="save" value="Enregistrer" />
				<input type="hidden" id="list" name="list" value="" />
			</div>
		</form>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>