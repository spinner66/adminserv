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
		else{
			AdminServLogs::add('action', 'Order map list');
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
		<?php echo AdminServUI::getMenuList(ExtensionConfig::$MAPSMENU); ?>
	</section>
	
	<section class="cadre right order">
		<h1><?php echo Utils::t('Order'); ?></h1>
		<form method="post" action="?p=<?php echo USER_PAGE; ?>">
			<h2><?php echo Utils::t('Automatic sort'); ?></h2>
			<div class="autoSortMode options-radio-inline">
				<ul>
					<li class="ui-state-default">
						<input class="text" type="radio" name="sortMode" id="sortModeName" value="name" />
						<div class="name"><?php echo Utils::t('Name'); ?></div>
						<div class="icon">
							<span class="ui-icon ui-icon-arrowthick-1-n"></span>
							<span class="ui-icon ui-icon-arrowthick-1-s"></span>
						</div>
					</li>
					<li class="ui-state-default">
						<input class="text" type="radio" name="sortMode" id="sortModeEnv" value="env" />
						<div class="name"><?php echo Utils::t('Environment'); ?></div>
						<div class="icon">
							<span class="ui-icon ui-icon-arrowthick-1-n"></span>
							<span class="ui-icon ui-icon-arrowthick-1-s"></span>
						</div>
					</li>
					<li class="ui-state-default">
						<input class="text" type="radio" name="sortMode" id="sortModeAuthor" value="author" />
						<div class="name"><?php echo Utils::t('Author'); ?></div>
						<div class="icon">
							<span class="ui-icon ui-icon-arrowthick-1-n"></span>
							<span class="ui-icon ui-icon-arrowthick-1-s"></span>
						</div>
					</li>
					<li class="ui-state-default">
						<input class="text" type="radio" name="sortMode" id="sortModeRand" value="rand" />
						<div class="name"><?php echo Utils::t('Random'); ?></div>
					</li>
				</ul>
			</div>
			
			<h2><?php echo Utils::t('Manual sort'); ?></h2>
			<div class="content">
				<ul id="sortableMapList">
					<?php echo AdminServUI::getTemplateMapsOrderList($mapsList); ?>
				</ul>
			</div>
			<?php if(SERVER_MATCHSET){ ?>
				<div class="fleft options-checkbox">
					<input class="text inline" type="checkbox" name="SaveCurrentMatchSettings" id="SaveCurrentMatchSettings"<?php if(AdminServConfig::AUTOSAVE_MATCHSETTINGS === true){ echo ' checked="checked"'; } ?> value="" /><label for="SaveCurrentMatchSettings" title="<?php echo SERVER_MATCHSET; ?>"><?php echo Utils::t('Save the current MatchSettings'); ?></label>
				</div>
			<?php } ?>
			<div class="fright save">
				<input class="button light" type="button" id="reset" name="reset" value="<?php echo Utils::t('Reset'); ?>" />
				<input class="button light" type="submit" id="save" name="save" value="<?php echo Utils::t('Save'); ?>" />
				<input type="hidden" id="list" name="list" value="" />
				<input type="hidden" id="jsonlist" name="jsonlist" value="<?php echo htmlspecialchars( json_encode($mapsList) ); ?>" />
			</div>
		</form>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>