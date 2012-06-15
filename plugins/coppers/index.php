<?php
	/* INCLUDES */
	$path = AdminServPlugin::getPluginPath();
	
	$langFile = $path.'lang/'. USER_LANG .'.php';
	if( file_exists($langFile) ){
		include_once $langFile;
	}
	
	
	/* ACTIONS */
	if( isset($_POST['transfercoppers']) ){
		// Server > Server
		if( isset($_POST['serverToServerAmout']) && isset($_POST['serverToServerLogin']) ){
			$serverToServerAmout = intval($_POST['serverToServerAmout']);
			$serverToServerLogin = trim($_POST['serverToServerLogin']);
			
			if($serverToServerAmout > 0 && $serverToServerLogin != Utils::t('Server login') ){
				if( !$client->query('Pay', $serverToServerLogin, $serverToServerAmout, Utils::t('Transfered by AdminServ')) ){
					AdminServ::error();
				}
				else{
					$_SESSION['adminserv']['transfer_billid'] = $client->getResponse();
				}
			}
		}
		
		// Server > Player
		if( isset($_POST['serverToPlayerAmount']) && isset($_POST['serverToPlayerLogin']) ){
			$serverToPlayerAmount = intval($_POST['serverToPlayerAmount']);
			$serverToPlayerMessage = trim($_POST['serverToPlayerMessage']);
			$serverToPlayerLogin = trim($_POST['serverToPlayerLogin']);
			$serverToPlayerLogin2 = trim($_POST['serverToPlayerLogin2']);
			
			if( $serverToPlayerAmount > 0 ){
				// Message
				if($serverToPlayerMessage == Utils::t('Optionnal') ){
					$serverToPlayerMessage = Utils::t('Transfered by AdminServ');
				}
				// Login joueur tapé
				if($serverToPlayerLogin2 != Utils::t('Player login') ){
					if( !$client->query('Pay', $serverToPlayerLogin2, $serverToPlayerAmount, $serverToPlayerMessage) ){
						AdminServ::error();
					}
					else{
						$_SESSION['adminserv']['transfer_billid'] = $client->getResponse();
					}
				}
				// Login joueur sélectionné
				else{
					if( !$client->query('Pay', $serverToPlayerLogin, $serverToPlayerAmount, $serverToPlayerMessage) ){
						AdminServ::error();
					}
					else{
						$_SESSION['adminserv']['transfer_billid'] = $client->getResponse();
					}
				}
			}
		}
		
		// Server < Player
		if( isset($_POST['playerToServerAmount']) && isset($_POST['playerToServerLogin']) ){
			$playerToServerAmount = intval($_POST['playerToServerAmount']);
			$playerToServerLogin = trim($_POST['playerToServerLogin']);
			
			if( $playerToServerAmount > 0 ){
				if( !$client->query('SendBill', $playerToServerLogin, $playerToServerAmount, Utils::t('Confirmation of the transfer by AdminServ'), SERVER_LOGIN) ){
					AdminServ::error();
				}else{
					$_SESSION['adminserv']['transfer_billid'] = $client->getResponse();
				}
			}
		}
		
		// Redirection
		Utils::redirection(false, '?p='.USER_PAGE);
	}
	
	
	/* LECTURE */
	$client->addCall('GetServerCoppers');
	if( isset($_SESSION['adminserv']['transfer_billid']) && $_SESSION['adminserv']['transfer_billid'] != null){
		$client->addCall('GetBillState', array($_SESSION['adminserv']['transfer_billid']) );
	}
	
	if( !$client->multiquery() ){
		AdminServ::error();
	}
	else{
		$queriesData = $client->getMultiqueryResponse();
		
		// Nombre de coppers
		$nbCoppers = $queriesData['GetServerCoppers'];
		
		// Statut du transfert
		if( isset($queriesData['GetBillState']) ){
			$billState = $queriesData['GetBillState'];
			$transferState = Utils::t('Transaction').' #'.$billState['TransactionId'].' : '.$billState['StateName'];
		}
		else{
			$transferState = '<i>'.Utils::t('No transfer made.').'</i>';
		}
	}
	
	// Nombre de joueurs
	$playerCount = AdminServ::getNbPlayers();
	$getPlayerListUI = AdminServUI::getPlayerList();
	
	$client->Terminate();
?>
<script src="<?php echo $path; ?>js/event.js"></script>

<h2><?php echo Utils::t('Infos'); ?></h2>
<div class="content">
	<p><?php echo Utils::t('Number of coppers:'); ?> <b><?php echo $nbCoppers; ?></b></p>
	<p><?php echo Utils::t('Transfer state:').' '.$transferState; ?></p>
</div>

<h2><?php echo Utils::t('Transfers'); ?></h2>
<form method="post" action="">
<div class="content">
	<fieldset>
		<legend><?php echo Utils::t('Server').' &gt; '.Utils::t('Server'); ?></legend>
		<table>
			<tr>
				<td class="key">
					<label for="serverToServerAmout"><?php echo Utils::t('Amount'); ?></label>
				</td>
				<td class="value">
					<input class="text width2" type="text" name="serverToServerAmout" id="serverToServerAmout" value="" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="serverToServerLogin"><i><?php echo SERVER_LOGIN; ?></i> →</label>
				</td>
				<td class="value">
					<input class="text width2" type="text" name="serverToServerLogin" id="serverToServerLogin" data-default-value="Login serveur" value="Login serveur" />
				</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend><?php echo Utils::t('Server').' &gt; '.Utils::t('Player'); ?></legend>
		<table>
			<tr>
				<td class="key">
					<label for="serverToPlayerAmount"><?php echo Utils::t('Amount'); ?></label>
				</td>
				<td class="value">
					<input class="text width2" type="text" name="serverToPlayerAmount" id="serverToPlayerAmount" value="" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="serverToPlayerMessage"><?php echo Utils::t('Message'); ?></label>
				</td>
				<td class="value">
					<input class="text width4" type="text" name="serverToPlayerMessage" id="serverToPlayerMessage" data-default-value="<?php echo Utils::t('Optionnal'); ?>" value="<?php echo Utils::t('Optionnal'); ?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="serverToPlayerLogin"><i><?php echo SERVER_LOGIN; ?></i> →</label>
				</td>
				<td class="value">
					<select class="width2" name="serverToPlayerLogin" id="serverToPlayerLogin"<?php if($playerCount == 0){ echo ' hidden="hidden"'; } ?>>
						<?php echo $getPlayerListUI; ?>
						<option value="more"><?php echo Utils::t('Enter another login'); ?></option>
					</select>
					<input class="text width2" type="text" name="serverToPlayerLogin2" id="serverToPlayerLogin2" data-default-value="<?php echo Utils::t('Player login'); ?>" value="<?php echo Utils::t('Player login'); ?>"<?php if($playerCount != 0){ echo ' hidden="hidden"'; } ?> />
				</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend><?php echo Utils::t('Server').' &lt; '.Utils::t('Player'); ?></legend>
		<table>
			<tr>
				<td class="key">
					<label for="playerToServerAmount"><?php echo Utils::t('Amount'); ?></label>
				</td>
				<td class="value">
					<input class="text width2" type="text" name="playerToServerAmount" id="playerToServerAmount" value="" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="playerToServerLogin"><i><?php echo SERVER_LOGIN; ?></i> ←</label>
				</td>
				<td class="value">
					<select class="width2" name="playerToServerLogin" id="playerToServerLogin">
						<?php echo $getPlayerListUI; ?>
					</select>
				</td>
				<td class="info">
					<?php echo Utils::t('Confirmation from a player on the server is necessary.'); ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="fright save">
	<input class="button light" type="submit" name="transfercoppers" id="transfercoppers" value="<?php echo Utils::t('Transfer'); ?>" />
</div>
</form>