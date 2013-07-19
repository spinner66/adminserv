<?php
	// LECTURE
	$chatServerLines = AdminServ::getChatServerLines();
	$lastNicknameUsed = Utils::readCookieData('adminserv_user', 2);
	if($lastNicknameUsed != null){
		$chatNickname = $lastNicknameUsed;
	}
	else{
		$chatNickname = Utils::t('Nickname');
	}
	$colorList = array(
		'$ff0' => Utils::t('Color'),
		'$000' => Utils::t('Black'),
		'$f00' => Utils::t('Red'),
		'$0f0' => Utils::t('Green'),
		'$00f' => Utils::t('Blue'),
		'$f80' => Utils::t('Orange'),
		'$f0f' => Utils::t('Pink'),
		'$888' => Utils::t('Grey'),
		'$fff' => Utils::t('White')
	);
	$chatColorOptions = null;
	$lastColorUsed = Utils::readCookieData('adminserv_user', 3);
	foreach($colorList as $colorCode => $colorName){
		if($colorCode == $lastColorUsed){ $selected = ' selected="selected"'; }
		else{ $selected = null; }
		$chatColorOptions .= '<option value="'.$colorCode.'"'.$selected.'>'.$colorName.'</option>';
	}
	if( isset($_SESSION['adminserv']['chat_dst']) ){
		$playerList = AdminServUI::getPlayerList($_SESSION['adminserv']['chat_dst']);
		$destTitle = Utils::t('Message destination').' : '.$_SESSION['adminserv']['chat_dst'];
	}
	else{
		$playerList = AdminServUI::getPlayerList();
		$destTitle = Utils::t('Message destination').' : '.Utils::t('server');
	}
	
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1><?php echo Utils::t('Chat'); ?></h1>
	<div class="title-detail">
		<ul>
			<li class="last"><a id="checkServerLines" href="?p=chat" data-val="0" data-txt="<?php echo Utils::t('Show server lines'); ?>"><?php echo Utils::t('Hide server lines'); ?></a></li>
		</ul>
	</div>
	
	<div id="chat"><?php echo $chatServerLines; ?></div>
	
	<div class="options">
		<input class="text" type="text" name="chatNickname" id="chatNickname" value="<?php echo $chatNickname; ?>" data-default-value="<?php echo Utils::t('Nickname'); ?>" />
		<select name="chatColor" id="chatColor" title="<?php echo Utils::t('Default color: yellow'); ?>">
			<?php echo $chatColorOptions; ?>
		</select>
		<input class="text" type="text" name="chatMessage" id="chatMessage" value="<?php echo Utils::t('Message'); ?>" data-default-value="<?php echo Utils::t('Message'); ?>" />
		<select name="chatDestination" id="chatDestination" title="<?php echo $destTitle; ?>">
			<option value="server"><?php echo Utils::t('Destination'); ?></option>
			<?php echo $playerList; ?>
		</select>
		<input class="button dark" type="submit" name="chatSend" id="chatSend" value="<?php echo Utils::t('Send'); ?>" />
	</div>
</section>
<?php
	AdminServUI::getFooter();
?>