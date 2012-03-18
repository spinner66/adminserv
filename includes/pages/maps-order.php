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
		<?php echo AdminServUI::getMenuList(ExtensionConfig::$MAPSMENU); ?>
	</section>
	
	<section class="cadre right order">
		<h1>Ordonner</h1>
		<form method="post" action="?p=maps-order">
			<h2>Tri automatique</h2>
			<div class="autoSortMode options-radio-inline">
				<ul>
					<li class="ui-state-default">
						<input class="text" type="radio" name="sortMode" id="sortModeName" value="name" />
						<div class="name">Nom</div>
						<div class="icon">
							<span class="ui-icon ui-icon-arrowthick-1-n"></span>
							<span class="ui-icon ui-icon-arrowthick-1-s"></span>
						</div>
					</li>
					<li class="ui-state-default">
						<input class="text" type="radio" name="sortMode" id="sortModeEnv" value="env" />
						<div class="name">Environnement</div>
						<div class="icon">
							<span class="ui-icon ui-icon-arrowthick-1-n"></span>
							<span class="ui-icon ui-icon-arrowthick-1-s"></span>
						</div>
					</li>
					<li class="ui-state-default">
						<input class="text" type="radio" name="sortMode" id="sortModeAuthor" value="author" />
						<div class="name">Auteur</div>
						<div class="icon">
							<span class="ui-icon ui-icon-arrowthick-1-n"></span>
							<span class="ui-icon ui-icon-arrowthick-1-s"></span>
						</div>
					</li>
					<li class="ui-state-default">
						<input class="text" type="radio" name="sortMode" id="sortModeRand" value="rand" />
						<div class="name">Aléatoire</div>
					</li>
				</ul>
			</div>
			
			<h2>Tri manuel</h2>
			<div class="content">
				<ul id="sortableMapList">
					<?php echo AdminServUI::getTemplateMapsOrderList($mapsList); ?>
				</ul>
			</div>
			<?php if(SERVER_MATCHSET){ ?>
				<div class="fleft options-checkbox">
					<input class="text inline" type="checkbox" name="SaveCurrentMatchSettings" id="SaveCurrentMatchSettings"<?php if(AdminServConfig::AUTOSAVE_MATCHSETTINGS === true){ echo ' checked="checked"'; } ?> value="" /><label for="SaveCurrentMatchSettings" title="<?php echo SERVER_MATCHSET; ?>">Sauvegarder le MatchSettings courant</label>
				</div>
			<?php } ?>
			<div class="fright save">
				<input class="button light" type="button" id="reset" name="reset" value="Réinitialiser" />
				<input class="button light" type="submit" id="save" name="save" value="Enregistrer" />
				<input type="hidden" id="list" name="list" value="" />
				<input type="hidden" id="jsonlist" name="jsonlist" value="<?php echo htmlspecialchars( json_encode($mapsList) ); ?>" />
			</div>
		</form>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>