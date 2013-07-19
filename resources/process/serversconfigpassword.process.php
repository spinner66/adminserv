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
			AdminServ::info( Utils::t('No server available. For add this, configure "config/servers.cfg.php" file.') );
			Utils::redirection();
		}
		else{
			if( OnlineConfig::ADD_ONLY === true || OnlineConfig::PASSWORD == null ){
				Utils::redirection(false, './?p=addserver');
			}
		}
	}
	else{
		AdminServ::error( Utils::t('The servers configuration file doesn\'t reconized by AdminServ.') );
		Utils::redirection();
	}
	
	
	// ENREGISTREMENT
	if( isset($_POST['savepassword']) ){
		$current = md5($_POST['changePasswordCurrent']);
		$new = md5($_POST['changePasswordNew']);
		if( isset($_SESSION['adminserv']['path']) ){ $adminservPath = $_SESSION['adminserv']['path']; }else{ $adminservPath = null; }
		$pathConfig = $adminservPath.'config/';
		
		if(OnlineConfig::PASSWORD !== $current){
			AdminServ::error( Utils::t('The current password doesn\'t match.') );
		}
		else{
			if( ($result = AdminServServerConfig::savePasswordConfig($pathConfig.'adminserv.cfg.php', $new)) !== true ){
				AdminServ::error( Utils::t('Unable to save password.').' ('.$result.')');
			}
			else{
				$info = Utils::t('The password has been changed.');
				AdminServ::info($info);
				AdminServLogs::add('action', $info);
			}
		}
		
		Utils::redirection(false, '?p='.USER_PAGE);
	}
	
	
	// HTML
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1><?php echo Utils::t('Change password'); ?></h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<div class="content">
			<fieldset>
				<legend><?php echo Utils::t('Password'); ?></legend>
				<table>
					<tr>
						<td class="key"><label for="changePasswordCurrent"><?php echo Utils::t('Current'); ?></label></td>
						<td class="value"><input class="text width3" type="password" name="changePasswordCurrent" id="changePasswordCurrent" value="" /></td>
					</tr>
					<tr>
						<td class="key"><label for="changePasswordNew"><?php echo Utils::t('New'); ?></label></td>
						<td class="value"><input class="text width3" type="password" name="changePasswordNew" id="changePasswordNew" value="" /></td>
					</tr>
				</table>
			</fieldset>
		</div>
		
		<div class="fright save">
			<input class="button light" type="submit" name="savepassword" id="savepassword" value="<?php echo Utils::t('Save'); ?>" />
		</div>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>