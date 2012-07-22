<?php
	// ACTIONS
	if( isset($_GET['stop']) ){
		if( !$client->query('StopServer') ){
			AdminServ::error();
		}
		else{
			$client->Terminate();
			Utils::redirection(false, '?logout');
		}
	}
	else if( isset($_POST['BanLoginList']) && count($_POST['player']) > 0 ){
		foreach($_POST['player'] as $player){
			if( !$client->query('Ban', $player) ){
				AdminServ::error();
				break;
			}
			else{
				AdminServLogs::add('action', 'Ban player: '.$player);
			}
		}
	}
	else if( isset($_POST['KickLoginList']) && count($_POST['player']) > 0 ){
		foreach($_POST['player'] as $player){
			if( !$client->query('Kick', $player) ){
				AdminServ::error();
				break;
			}
			else{
				AdminServLogs::add('action', 'Kick player: '.$player);
			}
		}
	}
	else if( isset($_POST['IgnoreLoginList']) && count($_POST['player']) > 0 ){
		foreach($_POST['player'] as $player){
			if( !$client->query('Ignore', $player) ){
				AdminServ::error();
				break;
			}
			else{
				AdminServLogs::add('action', 'Ignore player: '.$player);
			}
		}
	}
	else if( isset($_POST['GuestLoginList']) && count($_POST['player']) > 0 ){
		foreach($_POST['player'] as $player){
			if( !$client->query('AddGuest', $player) ){
				AdminServ::error();
				break;
			}
			else{
				AdminServLogs::add('action', 'Add Guest player: '.$player);
			}
		}
	}
	else if( isset($_POST['ForcePlayerList']) && count($_POST['player']) > 0 ){
		foreach($_POST['player'] as $player){
			if( !$client->query('ForceSpectator', $player, 2) ){
				AdminServ::error();
				break;
			}
			else{
				if( !$client->query('ForceSpectator', $player, 0) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Force player mode: '.$player);
				}
			}
		}
	}
	else if( isset($_POST['ForceSpectatorList']) && count($_POST['player']) > 0 ){
		foreach($_POST['player'] as $player){
			if( !$client->query('ForceSpectator', $player, 1) ){
				AdminServ::error();
				break;
			}
			else{
				if(!$client->query('ForceSpectator', $player, 0) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Force spectator mode: '.$player);
				}
			}
		}
	}
	else if( (isset($_POST['ForceBlueTeam']) || isset($_POST['ForceRedTeam'])) && count($_POST['player']) > 0 ){
		if( isset($_POST['ForceBlueTeam']) ){
			$teamId = 0;
		}else{
			$teamId = 1;
		}
		
		foreach($_POST['player'] as $player){
			if( !$client->query('ForcePlayerTeam', $player, $teamId) ){
				AdminServ::error();
				break;
			}
			else{
				if($teamId == 0){ $color = 'blue'; }else{ $color = 'red'; }
				AdminServLogs::add('action', 'Force player in '.$color.' team: '.$player);
			}
		}
	}
	else if( isset($_POST['ForceScores']) && isset($_POST['ScoreTeamBlue']) && isset($_POST['ScoreTeamRed']) ){
		$scoreTeamBlue = intval($_POST['ScoreTeamBlue']);
		$scoreTeamRed = intval($_POST['ScoreTeamRed']);
		$scores = array(
			array(
				'PlayerId' => 0,
				'Score' => $scoreTeamBlue
			),
			array(
				'PlayerId' => 1,
				'Score' => $scoreTeamRed
			)
		);
		if( !$client->query('ForceScores', $scores, true) ){
			AdminServ::error();
		}else{
			$action = '[Admin] '.Utils::t('The scores have been modified : $00fblue team $fffhas !scoreTeamBlue and $f00red team $fffhas !scoreTeamRed', array('!scoreTeamBlue' => $scoreTeamBlue, '!scoreTeamRed' => $scoreTeamRed));
			if( !$client->query('ChatSendServerMessage', $action) ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', $action);
			}
		}
	}
	else if( isset($_POST['CancelVote']) ){
		if( !$client->query('CancelVote') ){
			AdminServ::error();
		}
		else{
			Utils::redirection();
		}
	}
	
	
	// Info serveur
	$serverInfo = AdminServ::getCurrentServerInfo();
	$isTeamGameMode = AdminServ::isGameMode('Team', $serverInfo['srv']['gameModeId']);
	// Si on est en mode équipe, on force l'affichage en mode détail
	if($isTeamGameMode){
		$_SESSION['adminserv']['mode'] = 'detail';
	}
	if( defined('IS_RELAY') && IS_RELAY ){
		// @deprecated $mainServerLogin = AdminServ::getMainServerLoginFromRelay();
		$mainServerLogin = null;
	}
	
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="cadre left<?php if($isTeamGameMode){ echo ' isTeamGameMode'; } ?>">
	<h1><?php echo Utils::t('Current map'); ?></h1>
	<form method="post" action=".">
	<div class="content">
		<table class="current_map">
			<tr>
				<td class="key"><?php echo Utils::t('Name'); ?></td>
				<td class="value" id="map_name"><?php echo $serverInfo['map']['name']; ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo Utils::t('Author'); ?></td>
				<td class="value" id="map_author"><?php echo $serverInfo['map']['author']; ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo Utils::t('Environment'); ?></td>
				<td class="value" id="map_enviro"><?php echo $serverInfo['map']['enviro']; ?><img src="<?php echo AdminServConfig::PATH_RESSOURCES .'images/env/'.strtolower($serverInfo['map']['enviro']); ?>.png" alt="" /></td>
			</tr>
			<tr>
				<td class="key"><?php echo Utils::t('Map UId'); ?></td>
				<td class="value" id="map_uid"><?php echo $serverInfo['map']['uid']; ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo Utils::t('Game mode'); ?></td>
				<td class="value <?php echo strtolower($serverInfo['srv']['gameModeName']); ?>" id="map_gamemode"><?php echo $serverInfo['srv']['gameModeName']; ?></td>
			</tr>
			<?php if($serverInfo['map']['callvote']['login']){ ?>
				<tr>
					<td class="key"><?php echo Utils::t('Current vote'); ?></td>
					<td class="value" id="map_currentcallvote">
						<?php echo $serverInfo['map']['callvote']['login'].' : '.$serverInfo['map']['callvote']['cmdname'].' ('.$serverInfo['map']['callvote']['cmdparam'].')'; ?>
						<input class="button light" type="submit" name="CancelVote" id="CancelVote" value="<?php echo Utils::t('Cancel vote'); ?>" />
					</td>
				</tr>
			<?php } ?>
			<?php if($isTeamGameMode){ ?>
				<tr>
					<td class="key"><?php echo Utils::t('Scores'); ?></td>
					<td class="value" id="map_teamscore">
						<span class="team_0" title="<?php echo Utils::t('Blue team'); ?>"></span>
						<input class="text" type="text" name="ScoreTeamBlue" id="ScoreTeamBlue" value="<?php echo $serverInfo['map']['scores']['blue']; ?>" />
						<span class="team_1" title="<?php echo Utils::t('Red team'); ?>"></span>
						<input class="text" type="text" name="ScoreTeamRed" id="ScoreTeamRed" value="<?php echo $serverInfo['map']['scores']['red']; ?>" />
						<input class="button light" type="submit" name="ForceScores" id="ForceScores" value="Forcer les scores" />
					</td>
				</tr>
			<?php } ?>
		</table>
		<?php
			if($serverInfo['map']['thumb'] != null){
				echo '<div id="map_thumbnail" data-text-thumbnail="'.Utils::t('No thumbnail').'">'
					.'<img src="data:image/jpeg;base64,'.$serverInfo['map']['thumb'].'" alt="'.Utils::t('No thumbnail').'" />'
				.'</div>';
			}
		?>
	</div>
	</form>
	
	<h1><?php echo Utils::t('Server'); ?></h1>
	<div class="content">
		<table>
			<tr>
				<td class="key"><?php echo Utils::t('Server name'); ?></td>
				<td class="value" id="server_name"><?php echo $serverInfo['srv']['name']; ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo Utils::t('Status'); ?></td>
				<td class="value" id="server_status"><?php echo $serverInfo['srv']['status']; ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo Utils::t('Server login'); ?></td>
				<td class="value"><?php echo SERVER_LOGIN; ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo Utils::t('Connected on'); ?></td>
				<td class="value" id="srv_version_name">
					<?php
						if(defined('IS_RELAY') && IS_RELAY && isset($mainServerLogin) && $mainServerLogin !== null){
							echo $mainServerLogin.' (<span class="'.strtolower(SERVER_VERSION_NAME).'">'. SERVER_VERSION_NAME .'</span>)';
						}
						else{
							echo '<span class="'.strtolower(SERVER_VERSION_NAME).'">'. SERVER_VERSION_NAME .'</span>';
						}
					?>
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo Utils::t('Dedicated version'); ?></td>
				<td class="value"><?php echo SERVER_BUILD.' ('. SERVER_VERSION .')'; ?></td>
			</tr>
		</table>
	</div>
	
	<?php if( AdminServ::isAdminLevel('SuperAdmin') ){ ?>
		<h1><?php echo Utils::t('Statistics'); ?></h1>
		<div class="content last">
			<table>
				<tr>
					<td class="key"><?php echo Utils::t('Uptime'); ?></td>
					<td class="value" id="network_uptime"><?php echo $serverInfo['net']['uptime']; ?></td>
				</tr>
				<tr>
					<td class="key"><?php echo Utils::t('Number of connections'); ?></td>
					<td class="value" id="network_nbrconnection"><?php echo $serverInfo['net']['nbrconnection']; ?></td>
				</tr>
				<tr>
					<td class="key"><?php echo Utils::t('Average connection time'); ?></td>
					<td class="value" id="network_meanconnectiontime"><?php echo $serverInfo['net']['meanconnectiontime']; ?></td>
				</tr>
				<tr>
					<td class="key"><?php echo Utils::t('Average number of players'); ?></td>
					<td class="value" id="network_meannbrplayer"><?php echo $serverInfo['net']['meannbrplayer']; ?></td>
				</tr>
				<tr>
					<td class="key"><?php echo Utils::t('Recv net rate'); ?></td>
					<td class="value" id="network_recvnetrate"><?php echo $serverInfo['net']['recvnetrate']; ?></td>
				</tr>
				<tr>
					<td class="key"><?php echo Utils::t('Send net rate'); ?></td>
					<td class="value" id="network_sendnetrate"><?php echo $serverInfo['net']['sendnetrate']; ?></td>
				</tr>
				<tr>
					<td class="key"><?php echo Utils::t('Total receiving size'); ?></td>
					<td class="value" id="network_totalreceivingsize"><?php echo $serverInfo['net']['totalreceivingsize']; ?></td>
				</tr>
				<tr>
					<td class="key"><?php echo Utils::t('Total sending size'); ?></td>
					<td class="value" id="network_totalsendingsize"><?php echo $serverInfo['net']['totalsendingsize']; ?></td>
				</tr>
			</table>
		</div>
	<?php } ?>
</section>

<section class="cadre right<?php if($isTeamGameMode){ echo ' isTeamGameMode'; } ?>">
	<h1><?php echo Utils::t('Players'); ?></h1>
	<div class="title-detail">
		<ul>
			<li><a id="detailMode" href="." data-statusmode="<?php echo USER_MODE; ?>" data-textdetail="<?php echo Utils::t('Detailed mode'); ?>" data-textsimple="<?php echo Utils::t('Simple mode'); ?>"><?php if(USER_MODE == 'detail'){ echo Utils::t('Simple mode'); }else{ echo Utils::t('Detailed mode'); } ?></a></li>
			<li class="last"><input type="checkbox" name="checkAll" id="checkAll" value=""<?php if( !is_array($serverInfo['ply']) ){ echo ' disabled="disabled"'; } ?> /></li>
		</ul>
	</div>
	
	<!-- Liste des joueurs -->
	<form method="post" action=".">
	<div id="playerlist">
		<table>
			<thead>
				<tr>
					<?php if($isTeamGameMode){ ?>
						<th class="detailModeTh thleft"<?php if(USER_MODE == 'simple'){ echo ' hidden="hidden"'; } ?>><a href="?sort=team"><?php echo Utils::t('Team'); ?></a></th>
					<?php } ?>
					<th class="firstTh <?php if(USER_MODE == 'simple' || !$isTeamGameMode){ echo 'thleft'; } ?>"><a href="?sort=nickname"><?php echo Utils::t('Nickname'); ?></a></th>
					<?php if(!$isTeamGameMode){ ?>
						<th class="detailModeTh"<?php if(USER_MODE == 'simple'){ echo ' hidden="hidden"'; } ?>><a href="?sort=ladder"><?php echo Utils::t('Ladder'); ?></a></th>
					<?php } ?>
					<th><a href="?sort=login"><?php echo Utils::t('Login'); ?></a></th>
					<th><a href="?sort=status"><?php echo Utils::t('Status'); ?></a></th>
					<th class="thright"></th>
				</tr>
			</thead>
			<tbody>
				<tr class="table-separation"><td colspan="<?php if($isTeamGameMode){ echo '6'; }else{ echo '5'; } ?>"></td></tr>
				<?php
					$showPlayerList = null;
					
					// Liste des joueurs
					if( is_array($serverInfo['ply']) && count($serverInfo['ply']) > 0 ){
						$i = 0;
						foreach($serverInfo['ply'] as $player){
							// Ligne
							$showPlayerList .= '<tr class="'; if($i%2){ $showPlayerList .= 'even'; }else{ $showPlayerList .= 'odd'; } $showPlayerList .= '">';
								if($isTeamGameMode && USER_MODE == 'detail'){
									$showPlayerList .= '<td class="detailModeTd imgleft"><span class="team_'.$player['TeamId'].'" title="'.$player['TeamName'].'"></span>'.$player['TeamName'].'</td>';
								}
								
								$showPlayerList .= '<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/solo.png" alt="" />'.$player['NickName'].'</td>';
								if(!$isTeamGameMode){
									$showPlayerList .= '<td class="detailModeTd imgleft"'; if(USER_MODE == 'simple'){ $showPlayerList .= ' hidden="hidden"'; } $showPlayerList .= '><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/leagueladder.png" alt="" />'.$player['LadderRanking'].'</td>';
								}
								$showPlayerList .= '<td>'.$player['Login'].'</td>'
								.'<td>'.$player['PlayerStatus'].'</td>'
								.'<td class="checkbox"><input type="checkbox" name="player[]" value="'.$player['Login'].'" /></td>'
							.'</tr>';
							$i++;
						}
					}
					else{
						$showPlayerList .= '<tr class="no-line"><td class="center" colspan="'; if($isTeamGameMode){ $showPlayerList .= '6'; }else{ $showPlayerList .= '5'; } $showPlayerList .= '">'.$serverInfo['ply'].'</td></tr>';
					}
					
					// Affichage
					echo $showPlayerList;
				?>
			</tbody>
		</table>
	</div>
	
	<div class="options">
		<div class="fleft">
			<span class="nb-line"><?php echo $serverInfo['nbp']; ?></span>
		</div>
		<div class="fright">
			<div class="selected-files-label locked">
				<span class="selected-files-title"><?php echo Utils::t('For the selection'); ?></span>
				<span class="selected-files-count">(0)</span>
				<div class="selected-files-option">
					<input class="button dark" type="submit" name="IgnoreLoginList" id="IgnoreLoginList" value="<?php echo Utils::t('Ignore'); ?>" />
					<input class="button dark" type="submit" name="GuestLoginList" id="GuestLoginList" value="<?php echo Utils::t('Guest'); ?>" />
					<input class="button dark" type="submit" name="BanLoginList" id="BanLoginList" value="<?php echo Utils::t('Ban'); ?>" />
					<input class="button dark" type="submit" name="KickLoginList" id="KickLoginList" value="<?php echo Utils::t('Kick'); ?>" />
					<?php if($isTeamGameMode){ ?>
						<input class="button dark" type="submit" name="ForceBlueTeam" id="ForceBlueTeam" value="<?php echo Utils::t('Blue team'); ?>" />
						<input class="button dark" type="submit" name="ForceRedTeam" id="ForceRedTeam" value="<?php echo Utils::t('Red team'); ?>" />
					<?php } ?>
					<input class="button dark" type="submit" name="ForceSpectatorList" id="ForceSpectatorList" value="<?php echo Utils::t('Spectator'); ?>" />
					<input class="button dark" type="submit" name="ForcePlayerList" id="ForcePlayerList" value="<?php echo Utils::t('Player'); ?>" />
				</div>
			</div>
		</div>
	</div>
	
	<input type="hidden" id="currentSort" name="currentSort" value="" />
	<input type="hidden" id="isTeamGameMode" name="isTeamGameMode" value="<?php echo $isTeamGameMode; ?>" />
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>