<?php
	// SESSION
	if( !isset($_SESSION['adminserv']['allow_config_servers']) ){
		AdminServ::error( Utils::t('You are not allowed to configure the servers') );
		Utils::redirection();
	}
	
	// VERIFICATION
	if( class_exists('ServerConfig') ){
		// Si on n'autorise pas la configuration en ligne
		if( OnlineConfig::ACTIVATE !== true ){
			AdminServ::info( Utils::t('No server available. To add one, configure "config/servers.cfg.php" file.') );
			Utils::redirection();
		}
		else{
			if( OnlineConfig::ADD_ONLY === true ){
				Utils::redirection(false, './?p=addserver');
			}
		}
	}
	else{
		AdminServ::error( Utils::t('The servers configuration file isn\'t recognized by AdminServ.') );
		Utils::redirection();
	}
	
	
	// ACTIONS
	if( isset($_POST['save']) && isset($_POST['list']) && $_POST['list'] != null ){
		$serverList = ServerConfig::$SERVERS;
		$list = explode(',', $_POST['list']);
		
		$i = 0;
		$newServerList = array();
		foreach($list as $listServerName){
			$newServerList[$listServerName] = array(
				'address' => $serverList[$listServerName]['address'],
				'port' => $serverList[$listServerName]['port'],
				'mapsbasepath' => (isset($serverList[$listServerName]['mapsbasepath'])) ? $serverList[$listServerName]['mapsbasepath'] : '',
				'matchsettings' => $serverList[$listServerName]['matchsettings'],
				'adminlevel' => $serverList[$listServerName]['adminlevel']
			);
			$i++;
		}
		
		AdminServServerConfig::saveServerConfig(array(), -1, $newServerList);
		AdminServLogs::add('action', 'Order server list');
		Utils::redirection(false, '?p='.USER_PAGE);
	}
	
	
	// SERVERLIST
	$serverList = ServerConfig::$SERVERS;
	
	// HTML
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1><?php echo Utils::t('Servers order'); ?></h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<div class="content">
			<ul id="sortableServersList">
			<?php
				$showServerList = null;
				
				// Liste des serveurs
				if( is_array($serverList) && count($serverList) > 0 ){
					foreach($serverList as $serverName => $serverData){
						$showServerList .= '<li class="ui-state-default">'
							.'<div class="ui-icon ui-icon-arrowthick-2-n-s"></div>'
							.'<div class="order-server-name">'.$serverName.'</div>'
							.'<div class="order-server-addr-port">'.$serverData['address'].' / '.$serverData['port'].'</div>'
						.'</li>';
					}
				}
				
				echo $showServerList;
			?>
			</ul>
		</div>
		<div class="fright save">
			<input class="button light" type="button" id="reset" name="reset" value="<?php echo Utils::t('Reset'); ?>" />
			<input class="button light" type="submit" id="save" name="save" value="<?php echo Utils::t('Save'); ?>" />
			<input type="hidden" id="list" name="list" value="" />
		</div>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>