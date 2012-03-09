<?php
	// LECTURE
	$chatServerLines = AdminServ::getChatServerLines();
	$lastNicknameUsed = Utils::readCookieData('adminserv', 2);
	if($lastNicknameUsed != null){
		$chatNickname = $lastNicknameUsed;
	}
	else{
		$chatNickname = 'Pseudo';
	}
	$colorList = array(
		'$ff0' => 'Couleur',
		'$000' => 'Noir',
		'$f00' => 'Rouge',
		'$0f0' => 'Vert',
		'$00f' => 'Bleu',
		'$f80' => 'Orange',
		'$f0f' => 'Rose',
		'$888' => 'Gris',
		'$fff' => 'Blanc'
	);
	$chatColor = null;
	$lastColorUsed = Utils::readCookieData('adminserv', 3);
	foreach($colorList as $colorCode => $colorName){
		if($colorCode == $lastColorUsed){ $selected = ' selected="selected"'; }
		else{ $selected = null; }
		$chatColor .= '<option value="'.$colorCode.'"'.$selected.'>'.$colorName.'</option>';
	}
	if( isset($_SESSION['adminserv']['chat_dst']) ){
		$playerList = AdminServUI::getPlayerList($_SESSION['adminserv']['chat_dst']);
	}
	else{
		$playerList = AdminServUI::getPlayerList();
	}
	
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1>Chat</h1>
	<div class="title-detail">
		<ul>
			<li class="last"><a id="checkServerLines" href="?p=chat" data-val="0" data-txt="Afficher les lignes du serveur">Masquer les lignes du serveur</a></li>
		</ul>
	</div>
	
	<div id="chat"><?php echo $chatServerLines; ?></div>
	
	<div class="options">
		<input class="text" type="text" name="chatNickname" id="chatNickname" value="<?php echo $chatNickname; ?>" data-default-value="Pseudo" />
		<select name="chatColor" id="chatColor">
			<?php echo $chatColor; ?>
		</select>
		<input class="text width4" type="text" name="chatMessage" id="chatMessage" value="Message" data-default-value="Message" />
		<select name="chatDestination" id="chatDestination">
			<option value="server">Destination</option>
			<?php echo $playerList; ?>
		</select>
		<input class="button dark" type="submit" name="chatSend" id="chatSend" value="Envoyer" />
	</div>
</section>
<?php
	AdminServUI::getFooter();
?>