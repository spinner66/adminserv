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
?>