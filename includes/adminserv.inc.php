<?php

/**
* Classe pour l'interface d'AdminServ
*/
abstract class AdminServUI {
	
	/**
	* Récupère le titre de l'application
	*
	* @param string $type -> Retourner "str" ou "html"
	* @return string
	*/
	public static function getTitle($type = 'str'){
		$out = null;
		$title = AdminServConfig::TITLE;
		
		// Si aucun titre n'est spécifié, on met "AdminServ" par défaut
		if(!$title){
			$title = 'Admin,Serv';
		}
		
		// Si il y a une séparation
		if( strstr($title, ',') ){
			if($type == 'html'){
				$titleEx = explode(',', $title);
				$out = $titleEx[0].'<span class="title-color">'.$titleEx[1].'</span>';
			}
			else{
				$out = str_replace(',', '', $title);
			}
		}
		// Sinon, on renvoi le titre simple
		else{
			$out = $title;
		}
		
		return $out;
	}
	
	
	/**
	* Vérifie si il y a bien une config de theme
	*/
	public static function hasTheme(){
		$out = false;
		
		if( class_exists('ExtensionConfig') && isset(ExtensionConfig::$THEMES) && count(ExtensionConfig::$THEMES) > 0 ){
			$out = true;
		}
		
		return $out;
	}
	
	
	/**
	* Récupère le thème courant
	*
	* @param string $forceTheme -> Forcer l'utilisation du thème
	* @return $_SESSION['theme']
	*/
	public static function getTheme($forceTheme = null){
		$saveCookie = false;
		// Si on choisi un thème
		if($forceTheme){
			$_SESSION['theme'] = $forceTheme;
			$saveCookie = true;
		}
		else{
			if( !isset($_SESSION['theme']) ){
				// On récupère le thème dans le cookie
				if( isset($_COOKIE['adminserv_user']) ){
					$_SESSION['theme'] = Utils::readCookieData('adminserv_user', 0);
				}
				// Sinon thème par défaut
				else{
					if(AdminServConfig::DEFAULT_THEME){
						$_SESSION['theme'] = AdminServConfig::DEFAULT_THEME;
					}
					else{
						$_SESSION['theme'] = 'blue';
					}
					$saveCookie = true;
				}
			}
		}
		
		if($saveCookie){
			Utils::addCookieData('adminserv_user', array($_SESSION['theme'], self::getLang(), Utils::readCookieData('adminserv_user', 2), Utils::readCookieData('adminserv_user', 3)), AdminServConfig::COOKIE_EXPIRE);
		}
		
		return strtolower($_SESSION['theme']);
	}
	
	
	/**
	* Récupère la liste des thèmes
	*/
	public static function getThemeList($currentTheme = array() ){
		$out = null;
		$list = array();
		if( self::hasTheme() ){
			$list = ExtensionConfig::$THEMES;
		}
		
		// Thème courant
		if( count($currentTheme) > 0 && count($list) > 0 ){
			$currentThemeName = key($currentTheme);
			$currentThemeColor = current($currentTheme);
			unset($list[$currentThemeName]);
		}
		
		// Page courante
		if(USER_PAGE && USER_PAGE != 'index'){
			$param = '?p='. USER_PAGE .'&th=';
		}
		else{
			$param = '?th=';
		}
		
		if( count($list) > 0 ){
			$out .= '<ul>';
			// Si il y a un thème courant, on le place en 1er
			if( count($currentTheme) > 0 ){
				$out .= '<li><a class="theme-color" style="background-color: '.$currentThemeColor[0].';" href="'.$param.$currentThemeName.'" title="'.Utils::t( ucfirst($currentThemeName) ).'"></a></li>';
			}
			foreach($list as $name => $color){
				$out .= '<li><a class="theme-color" style="background-color: '.$color[0].';" href="'.$param.$name.'" title="'.Utils::t( ucfirst($name) ).'"></a></li>';
			}
			$out .= '</ul>';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la langue courante
	*
	* @param string $forceLang -> Forcer l'utilisation du langue
	* @return $_SESSION['lang']
	*/
	public static function getLang($forceLang = null){
		$saveCookie = false;
		// Si on choisi une langue
		if($forceLang){
			$_SESSION['lang'] = $forceLang;
			$saveCookie = true;
		}
		else{
			if( !isset($_SESSION['lang']) ){
				// On récupère la langue dans le cookie
				if( isset($_COOKIE['adminserv_user']) ){
					$_SESSION['lang'] = Utils::readCookieData('adminserv_user', 1);
				}
				else{
					// On récupère la langue du navigateur
					if( AdminServConfig::DEFAULT_LANGUAGE == 'auto' ){
						$_SESSION['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
					}
					// Sinon langue par défaut
					else{
						if(AdminServConfig::DEFAULT_LANGUAGE){
							$_SESSION['lang'] = AdminServConfig::DEFAULT_LANGUAGE;
						}
						else{
							$_SESSION['lang'] = 'en';
						}
					}
					$saveCookie = true;
				}
			}
		}
		
		if($saveCookie){
			Utils::addCookieData('adminserv_user', array(self::getTheme(), $_SESSION['lang'], Utils::readCookieData('adminserv_user', 2), Utils::readCookieData('adminserv_user', 3)), AdminServConfig::COOKIE_EXPIRE);
		}
		
		return $_SESSION['lang'];
	}
	
	
	/**
	* Récupère la liste des langues
	*/
	public static function getLangList($currentLang = array() ){
		$out = null;
		$list = ExtensionConfig::$LANG;
		
		// Langue courante
		if( count($currentLang) > 0 ){
			$currentLangCode = key($currentLang);
			$currentLangName = current($currentLang);
			unset($list[$currentLangCode]);
		}
		
		// Page courante
		if(USER_PAGE && USER_PAGE != 'index'){
			$param = '?p='. USER_PAGE .'&lg=';
		}
		else{
			$param = '?lg=';
		}
		
		// Liste de toutes les langues
		if( count($list) > 0 ){
			$out .= '<ul>';
			// Si il y a une langue courante, on la place en 1er
			if( count($currentLang) > 0 ){
				$out .= '<li><a class="lang-flag" style="background-image: url('. AdminServConfig::PATH_RESSOURCES .'images/lang/'.$currentLangCode.'.png);" href="'.$param.$currentLangCode.'" title="'.$currentLangName.'"></a></li>';
			}
			foreach($list as $code => $name){
				$out .= '<li><a class="lang-flag" style="background-image: url('. AdminServConfig::PATH_RESSOURCES .'images/lang/'.$code.'.png);" href="'.$param.$code.'" title="'.$name.'"></a></li>';
			}
			$out .= '</ul>';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère et inclue les classes PHP
	*/
	public static function getClass(){
		require_once __DIR__ .'/class/GbxRemote.inc.php';
		require_once __DIR__ .'/class/gbxdatafetcher.inc.php';
		require_once __DIR__ .'/class/utils.class.php';
		require_once __DIR__ .'/class/tmnick.class.php';
		require_once __DIR__ .'/class/upload.class.php';
		require_once __DIR__ .'/class/timedate.class.php';
		require_once __DIR__ .'/class/file.class.php';
		require_once __DIR__ .'/class/folder.class.php';
		require_once __DIR__ .'/class/str.class.php';
		require_once __DIR__ .'/class/zip.class.php';
	}
	
	
	/**
	* Récupère le header/footer du site
	*/
	public static function getHeader(){
		global $id;
		
		// Classes CSS body
		if( !isset($GLOBALS['body_class']) ){
			$GLOBALS['body_class'] = null;
		}
		if( defined('SERVER_NAME') ){
			$GLOBALS['page_title'] = SERVER_NAME;
			$GLOBALS['body_class'] .= ' not-front';
		}
		else{
			$GLOBALS['body_class'] .= ' front';
		}
		$GLOBALS['body_class'] .= ' section-'.USER_PAGE;
		if( $plugin = AdminServPlugin::getCurrent() ){
			$GLOBALS['body_class'] .= ' plugin-'.$plugin;
		}
		$GLOBALS['body_class'] = trim($GLOBALS['body_class']);
		
		require_once __DIR__ .'/header.inc.php';
	}
	public static function getFooter(){
		require_once __DIR__ .'/footer.inc.php';
	}
	
	
	/**
	* Récupère le CSS/JS du site
	*/
	public static function getCss($path = AdminServConfig::PATH_RESSOURCES){
		$out = '<link rel="stylesheet" href="'.$path.'styles/jquery-ui.css" />';
		if(USER_PAGE == 'maps-upload'){
			$out .= '<link rel="stylesheet" href="'.$path.'styles/fileuploader.css" />';
		}
		$out .= '<link rel="stylesheet" href="'.$path.'styles/global.css" />'
		.'<!--[if IE]><link rel="stylesheet" href="'.$path.'styles/ie.css" /><![endif]-->';
		if( defined('USER_THEME') ){
			$out .= '<link rel="stylesheet" href="'.$path.'styles/theme.php?th='. USER_THEME .'" />';
		}
		
		return $out;
	}
	public static function getJS($path = AdminServConfig::PATH_INCLUDES){
		$out = '<script src="'.$path.'js/jquery.js"></script>'
		.'<script src="'.$path.'js/jquery-ui.js"></script>';
		if(USER_PAGE == 'maps-upload'){
			$out .= '<script src="'.$path.'js/fileuploader.js"></script>';
		}
		$out .= '<script src="'.$path.'js/adminserv_funct.js"></script>'
		.'<script src="'.$path.'js/adminserv_event.js"></script>';
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des serveurs configurés
	*
	* @return string
	*/
	public static function getServerList(){
		$out = null;
		
		// On vérifie qu'une configuration existe
		if( class_exists('ServerConfig') ){
			
			// Si la configuration contient au moins 1 serveur et qu'il n'est pas l'exemple
			if( AdminServServerConfig::hasServer() ){
				
				if( isset($_GET['server']) && $_GET['server'] != null ){
					$currentServerId = intval($_GET['server']);
				}
				else{
					// Id du serveur utilisé dernièrement
					$currentServerId = Utils::readCookieData('adminserv', 0);
				}
				
				// Liste des serveurs
				foreach(ServerConfig::$SERVERS as $server => $values){
					if( AdminServServerConfig::getServerId($server) == $currentServerId ){
						$selected = ' selected="selected"';
					}else{
						$selected = null;
					}
					$out .= '<option value="'.$server.'"'.$selected.'>'.$server.'</option>';
				}
			}
			else{
				$out = -1;
			}
		}
		else{
			$out = -1;
		}
		
		
		// Retour
		if($out === -1){
			$out = '<option value="null">'.Utils::t('No server available').'</option>';
		}
		return $out;
	}
	
	
	/**
	* Récupère la liste des modes de jeu
	*
	* @param int $currentGameMode -> Le mode de jeu à sélectionner
	* @return string
	*/
	public static function getGameModeList($currentGameMode = null){
		$out = null;
		
		// On vérifie qu'une configuration existe
		if( class_exists('ExtensionConfig') ){
			
			// Si la configuration contient au moins 1 mode de jeu
			if( isset(ExtensionConfig::$GAMEMODES) && count(ExtensionConfig::$GAMEMODES) > 0 ){
				foreach(ExtensionConfig::$GAMEMODES as $gameModeId => $gameModeName){
					if( $gameModeId == $currentGameMode ){
						$selected = ' selected="selected"';
					}else{
						$selected = null;
					}
					$out .= '<option value="'.$gameModeId.'"'.$selected.'>'.$gameModeName.'</option>';
				}
			}
			else{
				$out = -1;
			}
		}
		else{
			$out = -1;
		}
		
		// Retour
		if($out === -1){
			$out = '<option value="null">'.Utils::t('No game mode available').'</option>';
		}
		return $out;
	}
	
	
	/**
	* Récupère le formulaire pour un champ
	*
	* @param string $name -> Le nom du champ affiché dans un label
	* @param string $id   -> L'id du champ du tableau GameInfos
	* @return string HTML
	*/
	public static function getGameInfosField($gameinfos, $name, $id){
		if( isset($gameinfos[0]) ){ $currGamInf = $gameinfos[0]; }else{ $currGamInf = null; }
		if( isset($gameinfos[1]) ){ $nextGamInf = $gameinfos[1]; }else{ $nextGamInf = null; }
		
		$out = '<tr>'
			.'<td class="key"><label for="Next'.$id.'">'.Utils::t($name).'</label></td>';
			if($currGamInf != null){
				$out .= '<td class="value">'
					.'<input class="text width2" type="text" name="Curr'.$id.'" id="Curr'.$id.'" readonly="readonly" value="'.$currGamInf[$id].'" />'
				.'</td>';
			}
			$out .= '<td class="value">'
				.'<input class="text width2" type="text" name="Next'.$id.'" id="Next'.$id.'" value="'.$nextGamInf[$id].'" />'
			.'</td>'
			.'<td class="preview"></td>'
		.'</tr>';
		
		return $out;
	}
	
	
	/**
	* Récupère le formulaire général aux informations de jeu
	*
	* @param array $currGamInf -> Les informations de jeu courantes
	* @param array $nextGamInf -> Les informations de jeu suivantes
	* @return string HTML
	*/
	public static function getGameInfosGeneralForm($gameinfos){
		if( isset($gameinfos[0]) ){ $currGamInf = $gameinfos[0]; }else{ $currGamInf = null; }
		if( isset($gameinfos[1]) ){ $nextGamInf = $gameinfos[1]; }else{ $nextGamInf = null; }
		
		$out = '<fieldset class="gameinfos_general">'
			.'<legend><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/restartrace.png" alt="" />Général</legend>'
			.'<table>'
				.'<tr>'
					.'<td class="key"><label for="NextGameMode">'.Utils::t('Game mode').'</label></td>';
					if($currGamInf != null){
						$out .= '<td class="value">'
							.'<input class="text width2" type="text" name="CurrGameMode" id="CurrGameMode" readonly="readonly" value="'.AdminServ::getGameModeName($currGamInf['GameMode']).'" />'
						.'</td>';
					}
					$out .= '<td class="value">'
						.'<select class="width2" name="NextGameMode" id="NextGameMode">'
							.AdminServUI::getGameModeList($nextGamInf['GameMode'])
						.'</select>'
					.'</td>'
					.'<td class="preview"></td>'
				.'</tr>'
				.'<tr>'
					.'<td class="key"><label for="NextChatTime">'.Utils::t('Map end time').' <span>('.Utils::t('sec').')</span></label></td>';
					if($currGamInf != null){
						$out .= '<td class="value">'
							.'<input class="text width2" type="text" name="CurrChatTime" id="CurrChatTime" readonly="readonly" value="'.TimeDate::millisecToSec($currGamInf['ChatTime'] + 8000).'" />'
						.'</td>';
					}
					$out .= '<td class="value">'
						.'<input class="text width2" type="text" name="NextChatTime" id="NextChatTime" value="'.TimeDate::millisecToSec($nextGamInf['ChatTime'] + 8000).'" />'
					.'</td>'
					.'<td class="preview"></td>'
				.'</tr>'
				.'<tr>'
					.'<td class="key"><label for="NextFinishTimeout">'.Utils::t('Round/lap end time').' <span>('.Utils::t('sec').')</span></label></td>';
					if($currGamInf != null){
						$out .= '<td class="value">'
							.'<input class="text width2" type="text" name="CurrFinishTimeout" id="CurrFinishTimeout" readonly="readonly" value="'; if($currGamInf['FinishTimeout'] == 0){ $out .= Utils::t('Default').' (15'.Utils::t('sec'); }else if($currGamInf['FinishTimeout'] == 1){ $out .= Utils::t('Auto (based on map)'); }else{ $out .= TimeDate::millisecToSec($currGamInf['FinishTimeout']); } $out .= '" />'
						.'</td>';
					}
					$out .= '<td class="value next">'
						.'<select class="width2" name="NextFinishTimeout" id="NextFinishTimeout"'; if($nextGamInf['FinishTimeout'] > 1){ $out .= ' hidden="hidden"'; } $out .= '>'
							.'<option value="0"'; if($nextGamInf['FinishTimeout'] == 0){ $out .= ' selected="selected"'; } $out .= '>'.Utils::t('Default').' (15'.Utils::t('sec').')</option>'
							.'<option value="1"'; if($nextGamInf['FinishTimeout'] == 1){ $out .= ' selected="selected"'; } $out .= '>'.Utils::t('Auto (based on map)').'</option>'
							.'<option value="more">'.Utils::t('Choose time').'</option>'
						.'</select>'
						.'<input class="text width2" type="text" name="NextFinishTimeoutValue" id="NextFinishTimeoutValue" value="'; if($nextGamInf['FinishTimeout'] > 1){ $out .= TimeDate::millisecToSec($nextGamInf['FinishTimeout']); } $out .= '"'; if($nextGamInf['FinishTimeout'] < 2){ $out .= ' hidden="hidden"'; } $out .= ' />'
					.'</td>'
					.'<td class="preview"'; if($nextGamInf['FinishTimeout'] < 2){ $out .= ' hidden="hidden"'; } $out .= '><a class="returnDefaultValue" href="?p='. USER_PAGE .'">'.Utils::t('Return to the default value').'</a></td>'
				.'</tr>'
				.self::getGameInfosField($gameinfos, 'All WarmUp duration', 'AllWarmUpDuration')
				.'<tr>'
					.'<td class="key"><label for="NextDisableRespawn">'.Utils::t('Respawn').'</label></td>';
					if($currGamInf != null){
						$out .= '<td class="value">'
							.'<input class="text width2" type="text" name="CurrDisableRespawn" id="CurrDisableRespawn" readonly="readonly" value="'; if($currGamInf['DisableRespawn'] === false){ $out .= Utils::t('Enable'); }else{ $out .= Utils::t('Disable'); } $out .= '" />'
						.'</td>';
					}
					$out .= '<td class="value">'
						.'<input class="text" type="checkbox" name="NextDisableRespawn" id="NextDisableRespawn"'; if($nextGamInf['DisableRespawn'] === false){ $out .= ' checked="checked"'; } $out .= ' value="" />'
					.'</td>'
					.'<td class="preview"></td>'
				.'</tr>'
				.'<tr>'
					.'<td class="key"><label for="NextForceShowAllOpponents">'.Utils::t('Force show of all opponents').'</label></td>';
					if($currGamInf != null){
						$out .= '<td class="value">'
							.'<input class="text width2" type="text" name="CurrForceShowAllOpponents" id="CurrForceShowAllOpponents" readonly="readonly" value="'; if($currGamInf['ForceShowAllOpponents'] == 0){ $out .= Utils::t('Let player choose'); }else if($currGamInf['ForceShowAllOpponents'] == 1){ $out .= Utils::t('All opponents'); }else{ $out .= $currGamInf['ForceShowAllOpponents'].' '.Utils::t('minimal opponents'); } $out .= '" />'
						.'</td>';
					}
					$out .= '<td class="value next">'
						.'<select class="width2" name="NextForceShowAllOpponents" id="NextForceShowAllOpponents"'; if($nextGamInf['ForceShowAllOpponents'] > 1){ $out .= ' hidden="hidden"'; } $out .= '>'
							.'<option value="0"'; if($nextGamInf['ForceShowAllOpponents'] == 0){ $out .= ' selected="selected"'; } $out .= '>'.Utils::t('Let player choose').'</option>'
							.'<option value="1"'; if($nextGamInf['ForceShowAllOpponents'] == 1){ $out .= ' selected="selected"'; } $out .= '>'.Utils::t('All opponents').'</option>'
							.'<option value="more">'.Utils::t('Choose opponents number').'</option>'
						.'</select>'
						.'<input class="text width2" type="text" name="NextForceShowAllOpponentsValue" id="NextForceShowAllOpponentsValue" value="'; if($nextGamInf['ForceShowAllOpponents'] > 1){ $out .= $nextGamInf['ForceShowAllOpponents']; } $out .= '"'; if($nextGamInf['ForceShowAllOpponents'] < 2){ $out .= ' hidden="hidden"'; } $out .= ' />'
					.'</td>'
					.'<td class="preview"'; if($nextGamInf['ForceShowAllOpponents'] < 2){ $out .= ' hidden="hidden"'; } $out .= '><a class="returnDefaultValue" href="?p='. USER_PAGE .'">'.Utils::t('Return to the default value').'</a></td>'
				.'</tr>'
			.'</table>'
		.'</fieldset>';
		
		return $out;
	}
	
	
	/**
	* Récupère les formulaires des modes de jeux
	*
	* @param array $currGamInf -> Les informations de jeu courantes
	* @param array $nextGamInf -> Les informations de jeu suivantes
	* @return string HTML
	*/
	public static function getGameInfosGameModeForm($gameinfos){
		if( isset($gameinfos[0]) ){ $currGamInf = $gameinfos[0]; }else{ $currGamInf = null; }
		if( isset($gameinfos[1]) ){ $nextGamInf = $gameinfos[1]; }else{ $nextGamInf = null; }
		$out = null;
		
		if(SERVER_VERSION_NAME == 'ManiaPlanet'){
			$out .= '<fieldset id="gameMode-script" class="gameinfos_script" hidden="hidden">'
				.'<legend><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/options.png" alt="" />'.AdminServ::getGameModeName(0).'</legend>'
				.'<table class="game_infos">'
					.self::getGameInfosField($gameinfos, 'Script name', 'ScriptName')
				.'</table>'
			.'</fieldset>';
		}
		
		$out .= '<fieldset id="gameMode-rounds" class="gameinfos_round" hidden="hidden">'
			.'<legend><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/rt_rounds.png" alt="" />'.AdminServ::getGameModeName(1, true).'</legend>'
			.'<table class="game_infos">'
				.'<tr>'
					.'<td class="key"><label for="NextRoundsUseNewRules">'.Utils::t('Use new rules').'</label></td>';
					if($currGamInf != null){
						$out .= '<td class="value">'
							.'<input class="text width2" type="text" name="CurrRoundsUseNewRules" id="CurrRoundsUseNewRules" readonly="readonly" value="'; if($currGamInf['RoundsUseNewRules'] != null){ $out .= Utils::t('Enable'); }else{ $out .= Utils::t('Disable'); } $out .= '" />'
						.'</td>';
					}
					$out .= '<td class="value">'
						.'<input class="text" type="checkbox" name="NextRoundsUseNewRules" id="NextRoundsUseNewRules"'; if($nextGamInf['RoundsUseNewRules'] != null){ $out .= ' checked="checked"'; } $out .= ' value="" />'
					.'</td>'
					.'<td class="preview"></td>'
				.'</tr>'
				.self::getGameInfosField($gameinfos, 'Points limit', 'RoundsPointsLimit')
				.self::getGameInfosField($gameinfos, 'Custom points limit', 'RoundCustomPoints')
				.self::getGameInfosField($gameinfos, 'Force laps', 'RoundsForcedLaps')
			.'</table>'
		.'</fieldset>'
		
		.'<fieldset id="gameMode-timeattack" class="gameinfos_timeattack" hidden="hidden">'
			.'<legend><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/rt_timeattack.png" alt="" />'.AdminServ::getGameModeName(2, true).'</legend>'
			.'<table class="game_infos">'
				.'<tr>'
					.'<td class="key"><label for="NextTimeAttackLimit">'.Utils::t('Time limit').' <span>('.Utils::t('sec').')</span></label></td>';
					if($currGamInf != null){
						$out .= '<td class="value">'
							.'<input class="text width2" type="text" name="CurrTimeAttackLimit" id="CurrTimeAttackLimit" readonly="readonly" value="'.TimeDate::millisecToSec($currGamInf['TimeAttackLimit']).'" />'
						.'</td>';
					}
					$out .= '<td class="value">'
						.'<input class="text width2" type="text" name="NextTimeAttackLimit" id="NextTimeAttackLimit" value="'.TimeDate::millisecToSec($nextGamInf['TimeAttackLimit']).'" />'
					.'</td>'
					.'<td class="preview"></td>'
				.'</tr>'
				.self::getGameInfosField($gameinfos, 'Start synchronization period', 'TimeAttackSynchStartPeriod')
			.'</table>'
		.'</fieldset>'
		
		.'<fieldset id="gameMode-team" class="gameinfos_team" hidden="hidden">'
			.'<legend><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/rt_team.png" alt="" />'.AdminServ::getGameModeName(3, true).'</legend>'
			.'<table class="game_infos">'
				.'<tr>'
					.'<td class="key"><label for="NextTeamUseNewRules">'.Utils::t('Use new rules').'</label></td>';
					if($currGamInf != null){
						$out .= '<td class="value">'
							.'<input class="text width2" type="text" name="CurrTeamUseNewRules" id="CurrTeamUseNewRules" readonly="readonly" value="'; if($currGamInf['TeamUseNewRules'] != null){ $out .= Utils::t('Enable'); }else{ $out .= Utils::t('Disable'); } $out .= '" />'
						.'</td>';
					}
					$out .= '<td class="value">'
						.'<input class="text" type="checkbox" name="NextTeamUseNewRules" id="NextTeamUseNewRules"'; if($nextGamInf['TeamUseNewRules'] != null){ $out .= ' checked="checked"'; } $out .= ' value="" />'
					.'</td>'
					.'<td class="preview"></td>'
				.'</tr>'
				.self::getGameInfosField($gameinfos, 'Points limit', 'TeamPointsLimit')
				.self::getGameInfosField($gameinfos, 'Maximal points', 'TeamMaxPoints')
			.'</table>'
		.'</fieldset>'
		
		.'<fieldset id="gameMode-laps" class="gameinfos_laps" hidden="hidden">'
			.'<legend><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/rt_laps.png" alt="" />'.AdminServ::getGameModeName(4, true).'</legend>'
			.'<table class="game_infos">'
				.self::getGameInfosField($gameinfos, 'Number of laps', 'LapsNbLaps')
				.self::getGameInfosField($gameinfos, Utils::t('Time limit').' <span>('.Utils::t('sec').')</span>', 'LapsTimeLimit')
			.'</table>'
		.'</fieldset>'
		
		.'<fieldset id="gameMode-cup" class="gameinfos_cup" hidden="hidden">'
			.'<legend><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/rt_cup.png" alt="" />'.AdminServ::getGameModeName(6, true).'</legend>'
			.'<table class="game_infos">'
				.self::getGameInfosField($gameinfos, 'Points limit', 'CupPointsLimit')
				.self::getGameInfosField($gameinfos, 'Rounds per map', 'CupRoundsPerMap')
				.self::getGameInfosField($gameinfos, 'Number of winner', 'CupNbWinners')
				.self::getGameInfosField($gameinfos, 'All WarmUp duration', 'CupWarmUpDuration')
			.'</table>'
		.'</fieldset>';
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des joueurs
	*
	* @param string $currentPlayerLogin -> Le login joueur à sélectionner
	* @return string
	*/
	public static function getPlayerList($currentPlayerLogin = null){
		global $client;
		$out = -1;
		
		if( $client->query('GetPlayerList', AdminServConfig::LIMIT_PLAYERS_LIST, 0) ){
			$playerList = $client->getResponse();
			if( count($playerList) > 0 ){
				$out = null;
				foreach($playerList as $player){
					if($currentPlayerLogin == $player['Login']){ $selected = ' selected="selected"'; }
					else{ $selected = null; }
					$out .= '<option value="'.$player['Login'].'"'.$selected.'>'.TmNick::toText($player['NickName']).'</option>';
				}
			}
		}
		
		// Retour
		if($out === -1){
			$out = '<option value="null">'.Utils::t('No player available').'</option>';
		}
		return $out;
	}
	
	
	/**
	* Retourne une liste html pour un menu
	*
	* @param array $list -> array('nom_de_la_page' => 'Nom du lien')
	* @return html
	*/
	public static function getMenuList($list){
		global $directory;
		$out = null;
		
		if( count($list) > 0 ){
			$out = '<nav class="vertical-nav">'
				.'<ul>';
					foreach($list as $page => $title){
						$out .= '<li><a '; if(USER_PAGE == $page){ $out .= 'class="active" '; } $out .= 'href="?p='.$page; if($directory){ $out .= '&amp;d='.$directory; } $out .= '">'.$title.'</a></li>';
					}
			$out .= '</ul>'
			.'</nav>';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la liset des dossiers du répertoire "Maps"
	*
	* @require class "Folder", "File", "Str"
	*
	* @param string $path -> Le chemin du dossier "Maps"
	* @param string $currentPath -> Le chemin à partir de "Maps"
	* @param bool   $showOptions -> Afficher les options (nouveau, renommer, déplacer, supprimer)
	* @return string
	*/
	public static function getMapsDirectoryList($path, $currentPath = null, $showOptions = true){
		$out = null;
		
		if( class_exists('Folder') ){
			// Titre + nouveau dossier
			$out .= '<form id="createFolderForm" method="post" action="?p='. USER_PAGE .'&amp;d='.$currentPath.'">'
				.'<h1>Dossiers';
					if($showOptions && AdminServConfig::$FOLDERS_OPTIONS['new'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['new'][1]) ){
						$out .='<span id="form-new-folder" hidden="hidden">'
							.'<input class="text" type="text" name="newFolderName" id="newFolderName" value="" />'
							.'<input class="button light" type="submit" name="newFolderValid" id="newFolderValid" value="ok" />'
						.'</span>';
					}
				$out .= '</h1>';
				if($showOptions && AdminServConfig::$FOLDERS_OPTIONS['new'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['new'][1]) ){
					$out .= '<div class="title-detail"><a href="." id="newfolder" data-cancel="'.Utils::t('Cancel').'" data-new="'.Utils::t('New').'">'.Utils::t('New').'</a></div>';
				}
			$out .= '</form>';
			
			// Liste des dossiers
			if( file_exists($path) ){
				if( in_array(USER_PAGE, array('maps-matchset', 'maps-creatematchset')) ){
					$directory = Folder::read($path.$currentPath, AdminServConfig::$MATCHSET_HIDDEN_FOLDERS, array(), intval(AdminServConfig::RECENT_STATUS_PERIOD * 3600) );
				}
				else{
					$directory = Folder::read($path.$currentPath, AdminServConfig::$MAPS_HIDDEN_FOLDERS, array(), intval(AdminServConfig::RECENT_STATUS_PERIOD * 3600) );
				}
				
				if( is_array($directory) ){
					$out .= '<div class="folder-list">'
					.'<ul>';
					
					// Dossier parent
					if($currentPath){
						$params = null;
						$parentPathEx = explode('/', $currentPath);
						array_pop($parentPathEx);
						array_pop($parentPathEx);
						if( count($parentPathEx) > 0 ){
							$parentPath = null;
							foreach($parentPathEx as $part){
								$parentPath .= $part.'/';
							}
							if($parentPath){
								$params = '&amp;d='.$parentPath;
							}
						}
						
						$out .= '<li>'
							.'<a href="./?p='. USER_PAGE . $params.'">'
								.'<img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/back.png" alt="" />'
								.'<span class="dir-name">'.Utils::t('Parent folder').'</span>'
							.'</a>'
						.'</li>';
					}
					
					// Dossiers
					if( count($directory['folders']) > 0 ){
						foreach($directory['folders'] as $dir => $values){
							$out .= '<li>'
								.'<a href="./?p='. USER_PAGE .'&amp;d='.$currentPath.$dir.'/">'
									.'<span class="dir-name">'.$dir.'</span>'
									.'<span class="dir-info">'.$values['nb_file'].'</span>'
								.'</a>'
							.'</li>';
						}
					}
					$out .= '</ul>'
					.'</div>';
				}
				else{
					AdminServ::error($directory);
				}
			}
			else{
				AdminServ::error('Path not exists');
			}
			
			// Options de dossier
			if($showOptions && $currentPath){
				if( (AdminServConfig::$FOLDERS_OPTIONS['rename'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['rename'][1])) || (AdminServConfig::$FOLDERS_OPTIONS['move'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['move'][1])) || (AdminServConfig::$FOLDERS_OPTIONS['delete'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['delete'][1])) ){
					$currentDir = basename($currentPath);
					$out .= '<form id="optionFolderForm" method="post" action="?p='. USER_PAGE .'&amp;d='.$currentPath.'">'
						.'<div class="option-folder-list">'
							.'<h3>'.Utils::t('Folder options').'<span class="arrow-down">&nbsp;</span></h3>'
							.'<ul hidden="hidden">';
								if(AdminServConfig::$FOLDERS_OPTIONS['rename'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['rename'][1]) ){
									$out .= '<li><a class="button light rename" id="renameFolder" href=".">'.Utils::t('Rename').'</a></li>';
								}
								if(AdminServConfig::$FOLDERS_OPTIONS['move'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['move'][1])){
									$out .= '<li><a class="button light move" id="moveFolder" href=".">'.Utils::t('Move').'</a></li>';
								}
								if(AdminServConfig::$FOLDERS_OPTIONS['delete'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['delete'][1])){
									$out .= '<li><a class="button light delete" id="deleteFolder" href="." data-confirm-text="'.Utils::t('Do yo really want to remove this folder !currentDir?', array('!currentDir' => $currentDir)).'">'.Utils::t('Delete').'</a></li>';
								}
							$out .= '</ul>'
						.'</div>'
						.'<input type="hidden" name="optionFolderHiddenFieldAction" id="optionFolderHiddenFieldAction" value="" />'
						.'<input type="hidden" name="optionFolderHiddenFieldValue" id="optionFolderHiddenFieldValue" value="" />'
						.'<div id="renameFolderForm" class="option-form" hidden="hidden" data-title="'.Utils::t('Rename folder').'" data-cancel="'.Utils::t('Cancel').'" data-rename="'.Utils::t('Rename').'">'
							.'<ul>'
								.'<li>'
									.'<span class="rename-map-name">'.$currentDir.'</span>'
									.'<span class="rename-map-arrow">&nbsp;</span>'
									.'<input class="text width2" type="text" name="renameFolderNewName" id="renameFolderNewName" value="'.$currentDir.'" />'
								.'</li>'
							.'</ul>'
						.'</div>'
						.'<div id="moveFolderForm" class="option-form" hidden="hidden" data-title="'.Utils::t('Move folder').'" data-cancel="'.Utils::t('Cancel').'" data-move="'.Utils::t('Move').'" data-root="'.Utils::t('Root').'" data-movethefolder="'.Utils::t('Move folder <b>!currentDir</b> in:', array('!currentDir' => $currentDir)).'"></div>'
					.'</form>';
				}
			}
		}
		else{
			AdminServ::error('Class "Folder" not exists');
		}
		return $out;
	}
	
	
	/**
	* Récupère le template de liste pour la page Maps-order
	*/
	public static function getTemplateMapsOrderList($list){
		$out = null;
		$pathRessources = AdminServConfig::PATH_RESSOURCES;
		
		if( is_array($list) && count($list) > 0 ){
			foreach($list['lst'] as $id => $map){
				if($list['cid'] != $id){
					$out .= '<li class="ui-state-default">'
						.'<div class="ui-icon ui-icon-arrowthick-2-n-s"></div>'
						.'<div class="order-map-name" title="'.$map['FileName'].'">'.$map['Name'].'</div>'
						.'<div class="order-map-env"><img src="'.$pathRessources.'images/env/'.strtolower($map['Environnement']).'.png" alt="" />'.$map['Environnement'].'</div>'
						.'<div class="order-map-author"><img src="'.$pathRessources.'images/16/mapauthor.png" alt="" />'.$map['Author'].'</div>'
					.'</li>';
				}
			}
		}
		
		return $out;
	}
}



/**
* Classe pour le fonctionnement d'AdminServ
*/
abstract class AdminServ {
	
	/**
	* Méthodes de debug
	*/
	public static function dsm($val){
		echo '<pre>';
		print_r($val);
		echo '</pre>';
	}
	public static function debug($globalValue = null){
		$const = get_defined_constants(true);
		if($globalValue){
			$globals = $GLOBALS[$globalValue];
		}else{
			$globals = $GLOBALS;
		}
		
		return self::dsm(
			array(
				'GLOBALS' => $globals,
				'ADMINSERV' => $const['user']
			)
		);
	}
	
	/**
	* Erreurs et infos
	*/
	public static function error($text = null){
		global $client;
		// Tente de récupérer le message d'erreur du dédié
		if($text === null){
			$text = '['.$client->getErrorCode().'] '.Utils::t($client->getErrorMessage() );
		}
		
		AdminServLogs::add('error', $text);
		unset($_SESSION['info']);
		$_SESSION['error'] = $text;
	}
	public static function info($text){
		$_SESSION['info'] = $text;
	}
	
	
	/**
	* Vérifie les droits pour l'écriture/lecture des fichiers
	*
	* @param array $list -> Liste des fichiers à tester : array('path' => 777)
	* @return array
	*/
	public static function checkRights($list){
		
		if( count($list) > 0 ){
			foreach($list as $path => $minChmod){
				$result = Folder::checkRights($path, $minChmod);
				foreach($result as $grpName => $grpValues){
					foreach($grpValues['result'] as $bool){
						if(!$bool){
							self::error('Le fichier ou dossier n\'a pas les droits requis : '.$path.' => '.$grpName.':'.$grpValues['result']);
							break;
						}
					}
				}
			}
		}
	}
	
	
	/**
	* Intialise le client du serveur courant
	*
	* @param bool $fullInit -> Intialisation complète ? oui par défaut.
	* Si non, ça ne recupère aucune info de base, seulement la connexion
	* au serveur dédié et son authentication.
	* @return true si réussi, sinon une erreur
	*/
	public static function initialize($fullInit = true){
		global $client;
		
		if( isset($_SESSION['adminserv']) ){
			// CONSTANTS
			define('USER_ADMINLEVEL', $_SESSION['adminserv']['adminlevel']);
			define('SERVER_ID', $_SESSION['adminserv']['sid']);
			define('SERVER_NAME', $_SESSION['adminserv']['name']);
			define('SERVER_ADDR', ServerConfig::$SERVERS[SERVER_NAME]['address']);
			define('SERVER_XMLRPC_PORT', ServerConfig::$SERVERS[SERVER_NAME]['port']);
			define('SERVER_MATCHSET', ServerConfig::$SERVERS[SERVER_NAME]['matchsettings']);
			define('SERVER_ADMINLEVEL', serialize( ServerConfig::$SERVERS[SERVER_NAME]['adminlevel']) );
			
			// CONNEXION
			$client = new IXR_ClientMulticall_Gbx;
			if( !$client->InitWithIp(SERVER_ADDR, SERVER_XMLRPC_PORT, AdminServConfig::SERVER_CONNECTION_TIMEOUT) ){
				Utils::redirection(false, '?error='.urlencode( Utils::t('The server is not accessible.') ) );
			}
			else{
				if( !self::userAllowedInAdminLevel(SERVER_NAME, USER_ADMINLEVEL) ){
					Utils::redirection(false, '?error='.urlencode( Utils::t('You are not allowed at this admin level') ) );
				}
				else{
					if( !$client->query('Authenticate', USER_ADMINLEVEL, $_SESSION['adminserv']['password']) ){
						Utils::redirection(false, '?error='.urlencode( Utils::t('The password doesn\'t match to the server.') ) );
					}
					else{
						if($fullInit){
							$client->addCall('GetVersion');
							$client->addCall('GetSystemInfo');
							$client->addCall('IsRelayServer');
							if( !$client->multiquery() ){
								self::error();
							}
							else{
								$queriesData = $client->getMultiqueryResponse();
								
								// Version
								$getVersion = $queriesData['GetVersion'];
								define('SERVER_VERSION_NAME', $getVersion['Name']);
								define('SERVER_VERSION', $getVersion['Version']);
								define('SERVER_BUILD', $getVersion['Build']);
								if(SERVER_VERSION_NAME == 'ManiaPlanet'){
									define('API_VERSION', $getVersion['ApiVersion']);
								}
								
								// SystemInfo
								$getSystemInfo = $queriesData['GetSystemInfo'];
								define('SERVER_LOGIN', $getSystemInfo['ServerLogin']);
								define('SERVER_PUBLISHED_IP', $getSystemInfo['PublishedIp']);
								define('SERVER_PORT', $getSystemInfo['Port']);
								define('SERVER_P2P_PORT', $getSystemInfo['P2PPort']);
								if(SERVER_VERSION_NAME == 'ManiaPlanet'){
									define('IS_SERVER', $getSystemInfo['IsServer']);
									define('IS_DEDICATED', $getSystemInfo['IsDedicated']);
								}
								
								// Relay
								define('IS_RELAY', $queriesData['IsRelayServer']);
								
								// Protocole : tmtp ou maniaplanet
								if(SERVER_VERSION_NAME == 'ManiaPlanet'){
									TmNick::$linkProtocol = 'maniaplanet';
								}
								define('LINK_PROTOCOL', TmNick::$linkProtocol);
								
								// Mode d'affichage : detail ou simple
								if( !isset($_SESSION['adminserv']['mode']) ){
									define('USER_MODE', 'simple');
								}
								else{
									define('USER_MODE', $_SESSION['adminserv']['mode']);
								}
								
								// TRACKMANIA FOREVER
								if(SERVER_VERSION_NAME == 'TmForever'){
									
									// Mode de jeu
									unset(ExtensionConfig::$GAMEMODES[0]);
									$newGameModes = array();
									foreach(ExtensionConfig::$GAMEMODES as $gameModeId => $gameModeName){
										$newGameModes[] = $gameModeName;
									}
									ExtensionConfig::$GAMEMODES = $newGameModes;
								}
								
								return true;
							}
						}
						else{
							return true;
						}
					}
				}
			}
		}
		else{
			return 'no session';
		}
	}
	
	
	/**
	* Vérifie si l'ip de l'utilisateur est autorisé dans le niveau admin
	*
	* @param string $serverName -> Le nom du serveur dans la config
	* @param string $level      -> Le niveau admin correspondant à tester
	* @return true si autorisé, sinon false
	*/
	public static function userAllowedInAdminLevel($serverName, $level){
		$out = false;
		$serverLevel = ServerConfig::$SERVERS[$serverName]['adminlevel'][$level];
		
		// Si la liste est un array
		if( is_array($serverLevel) ){
			// Si l'adresse ip est dans la liste des autorisées
			if( in_array($_SERVER['REMOTE_ADDR'], $serverLevel) ){
				$out = true;
			}
		}
		// Sinon, c'est local ou null
		else{
			// Si c'est all -> autorisé à tous
			if($serverLevel === 'all'){
				$out = true;
			}
			// Sinon -> autorisé au réseau local
			else{
				$out = Utils::isLocalhostIP();
			}
		}
		
		return $out;
	}
	
	
	/**
	* Vérifie les accès à différent niveau d'admin
	*
	* @param  string $level -> Level minimum à tester
	* @return true si autorisé, sinon false
	*/
	public static function isAdminLevel($level){
		$out = false;
		$adminLevel = $_SESSION['adminserv']['adminlevel'];
		
		if($level == 'User'){
			if($adminLevel == 'SuperAdmin' || $adminLevel == 'Admin' || $adminLevel == 'User'){
				$out = true;
			}
		}
		else if($level == 'Admin'){
			if($adminLevel == 'SuperAdmin' || $adminLevel == 'Admin'){
				$out = true;
			}
		}
		else if($level == 'SuperAdmin'){
			if($adminLevel == 'SuperAdmin'){
				$out = true;
			}
		}
		
		return $out;
	}
	
	
	/**
	* Retourne un lien protocol TM ou ManiaPlanet suivant l'environnement
	*
	* @param string $linkType  -> Type de lien : #join=server_login ou /:manialink_name
	* @param string $gameTitle -> Le nom du jeu : TMCanyon, SMStorm, etc
	* @return string
	*/
	public static function getProtocolLink($linkType, $gameTitle){
		$protocolName = 'maniaplanet';
		$protocolSeparator = '://';
		$protocolSeparatorTitle = '@';
		if( defined('LINK_PROTOCOL') && LINK_PROTOCOL ){
			$protocolName = LINK_PROTOCOL;
		}
		$game = self::getGameFromEnv($env);
		$title = $game['abbr'].ucfirst($env);
		
		return $protocolName.$protocolSeparator.$linkType.$protocolSeparatorTitle.$title;
	}
	
	
	/**
	* Récupère le nom et l'abrévation du jeu en fonction de son environnement
	*
	* @param string $env -> Le nom de l'environnement
	* @return array
	*/
	public static function getGameFromEnv($env){
		$out = array();
		$env = strtolower($env);
		
		switch($env){
			case 'alpine':
			case 'bay':
			case 'canyon':
			case 'coast':
			case 'desert':
			case 'island':
			case 'rally':
			case 'snow':
			case 'speed':
			case 'stadium':
				$out['name'] = 'TrackMania';
				$out['abbr'] = 'TM';
				break;
			case 'storm':
				$out['name'] = 'ShootMania';
				$out['abbr'] = 'SM';
				break;
		}
		
		return $out;
	}
	
	
	/**
	* Récupère le nom du game mode
	*
	* @param int  $gameMode  -> La réponse de GetGameMode()
	* @param bool $getManual -> Forcer la récupération manuelle du nom à partir du numéro dans la config
	* @return string
	*/
	public static function getGameModeName($gameMode, $getManual = false){
		$out = -1;
		
		// On vérifie qu'une configuration existe
		if( class_exists('ExtensionConfig') ){
			
			// Si la configuration contient au moins 1 mode de jeu
			if( isset(ExtensionConfig::$GAMEMODES) && count(ExtensionConfig::$GAMEMODES) > 0 ){
				if($getManual && SERVER_VERSION_NAME == 'TmForever'){
					$gameMode--;
					if( isset(ExtensionConfig::$GAMEMODES[$gameMode]) ){
						$out = ExtensionConfig::$GAMEMODES[$gameMode];
					}
				}
				else{
					$out = ExtensionConfig::$GAMEMODES[$gameMode];
				}
			}
		}
		
		// Retour
		if($out === -1){
			$out = Utils::t('No game mode available');
		}
		return $out;
	}
	
	
	/**
	* Détermine si le mode de jeu fourni en paramètre est le mode par équipe
	*
	* @param string $gameModeName    -> Nom du mode de jeu à tester
	* @param int    $currentGameMode -> ID du mode de jeu courant
	* @return bool
	*/
	public static function isGameMode($gameModeName, $currentGameMode){
		$out = false;
		
		if($gameModeName == self::getGameModeName($currentGameMode) ){
			$out = true;
		}
		
		return $out;
	}
	
	
	/**
	* Récupère les informations du serveur actuel (map, serveur, stats, joueurs)
	*
	* @global resource $client -> Le client doit être initialisé
	* @param  string   $sortBy -> Le tri à faire sur la liste
	* @return array
	*/
	public static function getCurrentServerInfo($sortBy = null){
		global $client;
		$out = array();
		
		// JEU
		if(SERVER_VERSION_NAME == 'TmForever'){
			$queryName = array(
				'getMapInfo' => 'GetCurrentChallengeInfo'
			);
		}
		else{
			$queryName = array(
				'getMapInfo' => 'GetCurrentMapInfo'
			);
		}
		
		// REQUÊTES
		$client->addCall($queryName['getMapInfo']);
		if( self::isAdminLevel('Admin') ){
			$client->addCall('GetMapsDirectory');
		}
		$client->addCall('GetGameMode');
		$client->addCall('GetServerName');
		$client->addCall('GetStatus');
		if( self::isAdminLevel('SuperAdmin') ){
			$client->addCall('GetNetworkStats');
		}
		$client->addCall('GetPlayerList', array(AdminServConfig::LIMIT_PLAYERS_LIST, 0) );
		
		if( !$client->multiquery() ){
			$out['error'] = Utils::t('Client not initialized');
		}
		else{
			// DONNÉES DES REQUÊTES
			$queriesData = $client->getMultiqueryResponse();
			
			// GameMode
			$out['srv']['gameModeId'] = $queriesData['GetGameMode'];
			$out['srv']['gameModeName'] = self::getGameModeName($out['srv']['gameModeId']);
			
			// CurrentMapInfo
			$currentMapInfo = $queriesData[$queryName['getMapInfo']];
			$out['map']['name'] = TmNick::toHtml($currentMapInfo['Name'], 10, true, false, '#999');
			$out['map']['uid'] = $currentMapInfo['UId'];
			$out['map']['author'] = $currentMapInfo['Author'];
			$out['map']['enviro'] = $currentMapInfo['Environnement'];
			
			// MapThumbnail
			$mapsDirectory = $queriesData['GetMapsDirectory'];
			if($mapsDirectory){
				$Gbx = new GBXChallengeFetcher($mapsDirectory.$currentMapInfo['FileName'], true, true);
				$out['map']['thumb'] = base64_encode($Gbx->thumbnail);
			}
			else{
				$out['map']['thumb'] = null;
			}
			
			// TeamScores (mode team)
			if( self::isGameMode('Team', $out['srv']['gameModeId']) ){
				$client->query('GetCurrentRanking', 2, 0);
				$currentRanking = $client->getResponse();
				$out['map']['scores']['blue'] = $currentRanking[0]['Score'];
				$out['map']['scores']['red'] = $currentRanking[1]['Score'];
			}
			
			// ServerName
			$out['srv']['name'] = TmNick::toHtml($queriesData['GetServerName'], 10, true, false, '#999');
			
			// Status
			$client->query('GetStatus');
			$out['srv']['status'] = $queriesData['GetStatus']['Name'];
			
			// NetworkStats
			$networkStats = $queriesData['GetNetworkStats'];
			if( count($networkStats) > 0 ){
				$out['net']['uptime'] = TimeDate::secToStringTime($networkStats['Uptime'], false);
				$out['net']['nbrconnection'] = $networkStats['NbrConnection'];
				$out['net']['meanconnectiontime'] = TimeDate::secToStringTime($networkStats['MeanConnectionTime'], false);
				$out['net']['meannbrplayer'] = $networkStats['MeanNbrPlayer'];
				$out['net']['recvnetrate'] = $networkStats['RecvNetRate'];
				$out['net']['sendnetrate'] = $networkStats['SendNetRate'];
				$out['net']['totalreceivingsize'] = $networkStats['TotalReceivingSize'];
				$out['net']['totalsendingsize'] = $networkStats['TotalSendingSize'];
			}
			else{
				$out['net'] = null;
			}
			
			// PlayerList
			$playerList = $queriesData['GetPlayerList'];
			$countPlayerList = count($playerList);
			
			if( $countPlayerList > 0 ){
				$client->query('GetCurrentRanking', AdminServConfig::LIMIT_PLAYERS_LIST, 0);
				$rankingList = $client->GetResponse();
				$i = 0;
				foreach($playerList as $player){
					// Nickname et Playerlogin
					$out['ply'][$i]['NickName'] = TmNick::toHtml(htmlspecialchars($player['NickName'], ENT_QUOTES, 'UTF-8'), 10, true);
					$out['ply'][$i]['Login'] = $player['Login'];
					
					// PlayerStatus
					if($player['IsSpectator'] != 0){ $playerStatus = Utils::t('Spectator'); }else{ $playerStatus = Utils::t('Player'); }
					$out['ply'][$i]['PlayerStatus'] = $playerStatus;
					
					// Autres
					$out['ply'][$i]['PlayerId'] = $player['PlayerId'];
					$out['ply'][$i]['TeamId'] = $player['TeamId'];
					if($player['TeamId'] == 0){ $teamName = Utils::t('Blue'); }else if($player['TeamId'] == 1){ $teamName = Utils::t('Red'); }else{ $teamName = Utils::t('Spectator'); }
					$out['ply'][$i]['TeamName'] = $teamName;
					$out['ply'][$i]['IsSpectator'] = $player['IsSpectator'];
					$out['ply'][$i]['IsInOfficialMode'] = $player['IsInOfficialMode'];
					$out['ply'][$i]['Rank'] = $rankingList[$i]['Rank'];
					$out['ply'][$i]['BestTime'] = $rankingList[$i]['BestTime'];
					$out['ply'][$i]['BestCheckpoints'] = $rankingList[$i]['BestCheckpoints'];
					$out['ply'][$i]['Score'] = $rankingList[$i]['Score'];
					$out['ply'][$i]['NbrLapsFinished'] = $rankingList[$i]['NbrLapsFinished'];
					$out['ply'][$i]['LadderScore'] = $rankingList[$i]['LadderScore'];
					$out['ply'][$i]['LadderRanking'] = $player['LadderRanking'];
					$i++;
				}
			}
			else{
				$out['ply'] = Utils::t('No player');
			}
			
			// Nombre de joueurs
			if($countPlayerList > 1){
				$out['nbp'] = $countPlayerList.' '.Utils::t('players');
			}
			else{
				$out['nbp'] = $countPlayerList.' '.Utils::t('player');
			}
			
			
			// TRI
			if( is_array($out['ply']) && count($out['ply']) > 0 ){
				// Si on est en mode équipe, on tri par équipe
				if( self::isGameMode('Team', $out['srv']['gameModeId']) ){
					uasort($out['ply'], 'AdminServSort::sortByTeam');
				}
				else{
					switch($sortBy){
						case 'nickname':
							uasort($out['ply'], 'AdminServSort::sortByNickName');
							break;
						case 'ladder':
							uasort($out['ply'], 'AdminServSort::sortByLadderRanking');
							break;
						case 'login':
							uasort($out['ply'], 'AdminServSort::sortByLogin');
							break;
						case 'status':
							uasort($out['ply'], 'AdminServSort::sortByStatus');
							break;
						default:
							uasort($out['ply'], 'AdminServSort::sortByRank');
							break;
					}
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Récupère le login du serveur principal à partir d'un serveur Relai
	*
	* @return string
	*/
	public static function getMainServerLoginFromRelay(){
		global $client;
		$out = null;
		
		if( self::isAdminLevel('Admin') ){
			if( !$client->query('GameDataDirectory') ){
				self::error('tout pété');
			}
			else{
				// Chemin parent
				$currentPath = Str::toSlash( $client->getResponse() );
				$parentPathEx = explode('/', $currentPath);
				array_pop($parentPathEx);
				array_pop($parentPathEx);
				if( count($parentPathEx) > 0 ){
					$parentPath = null;
					foreach($parentPathEx as $part){
						$parentPath .= $part.'/';
					}
				}
				
				// Fichier RunSrv
				if( Utils::isLinuxServer() ){ $ext = 'sh'; }else{ $ext = 'bat'; }
				if(SERVER_VERSION_NAME == 'TmForever'){ $filename = 'Start'; }else{ $filename = 'RunSrv'; }
				$file = $parentPath.$filename.'.'.$ext;
				if( file_exists($file) ){
					$fileContents = file_get_contents($file);
					$fileContentsEx = explode('/join=', $fileContents);
					$fileContentsEx = explode(' ', $fileContentsEx[1]);
					$out = trim($fileContentsEx[0]);
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Récupère le nombre de joueurs présent sur le serveur
	*
	* @return int
	*/
	public static function getNbPlayers(){
		global $client;
		$out = 0;
		
		if( !$client->query('GetPlayerList', AdminServConfig::LIMIT_PLAYERS_LIST, 0) ){
			self::error();
		}
		else{
			$out = count( $client->getResponse() );
		}
		
		return $out;
	}
	
	
	/**
	* Administration rapide (restart, next, endround)
	*
	* @global resource $client -> Le client doit être initialisé
	* @param  string   $cmd    -> Le nom de la méthode ManiaPlanet à utiliser
	* @return true si réussi, sinon un message d'erreur
	*/
	public static function speedAdmin($cmd){
		global $client;
		$out = true;
		
		// Méthode en fonction du jeu
		if($cmd != 'ForceEndRound'){
			if(SERVER_VERSION_NAME == 'TmForever'){
				$methodRestart = 'RestartChallenge';
				$methodNext = 'NextChallenge';
			}else{
				$methodRestart = 'RestartMap';
				$methodNext = 'NextMap';
			}
		}
		
		// Suivant la commande demandée
		if($cmd == 'RestartMap'){
			if( !$client->query($methodRestart) ){
				$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
			}
		}
		else if($cmd == 'NextMap'){
			if( !$client->query($methodNext) ){
				$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
			}
		}
		else if($cmd == 'ForceEndRound'){
			if( !$client->query('ForceEndRound') ){
				$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
			}
		}
		else{
			$out = Utils::t('Unknown command');
		}
		
		return $out;
	}
	
	
	/**
	* Récupère les informations de jeux
	*
	* @global resource $client -> Le client doit être initialisé
	* @return array
	*/
	public static function getGameInfos(){
		global $client;
		$out = array();
		
		// Jeu
		if(SERVER_VERSION_NAME == 'TmForever'){
			$queries = array(
				'CupRoundsPerMap' => 'GetCupRoundsPerChallenge',
			);
		}
		else{
			$queries = array(
				'CupRoundsPerMap' => 'GetCupRoundsPerMap',
			);
		}
		
		// Requêtes
		$client->addCall('GetGameInfos');
		$client->addCall('GetAllWarmUpDuration');
		$client->addCall('GetDisableRespawn');
		$client->addCall('GetForceShowAllOpponents');
		if(SERVER_VERSION_NAME == 'ManiaPlanet'){
			$client->addCall('GetScriptName');
		}
		$client->addCall('GetCupPointsLimit');
		$client->addCall($queries['CupRoundsPerMap']);
		$client->addCall('GetCupNbWinners');
		$client->addCall('GetCupWarmUpDuration');
		$client->addCall('GetRoundCustomPoints');
		
		if( !$client->multiquery() ){
			self::error();
		}
		else{
			$queriesData = $client->getMultiqueryResponse();
			
			// Game infos
			$currGamInf = $queriesData['GetGameInfos']['CurrentGameInfos'];
			$nextGamInf = $queriesData['GetGameInfos']['NextGameInfos'];
			
			// Nb de WarmUp
			$currGamInf['AllWarmUpDuration'] = $queriesData['GetAllWarmUpDuration']['CurrentValue'];
			$nextGamInf['AllWarmUpDuration'] = $queriesData['GetAllWarmUpDuration']['NextValue'];
			
			// Respawn
			$currGamInf['DisableRespawn'] = $queriesData['GetDisableRespawn']['CurrentValue'];
			$nextGamInf['DisableRespawn'] = $queriesData['GetDisableRespawn']['NextValue'];
			
			// ForceShowAllOpponents
			$currGamInf['ForceShowAllOpponents'] = $queriesData['GetForceShowAllOpponents']['CurrentValue'];
			$nextGamInf['ForceShowAllOpponents'] = $queriesData['GetForceShowAllOpponents']['NextValue'];
			
			// ScriptName
			if(SERVER_VERSION_NAME == 'ManiaPlanet'){
				$currGamInf['ScriptName'] = $queriesData['GetScriptName']['CurrentValue'];
				$nextGamInf['ScriptName'] = $queriesData['GetScriptName']['NextValue'];
			}
			
			// Mode Cup
			$currGamInf['CupPointsLimit'] = $queriesData['GetCupPointsLimit']['CurrentValue'];
			$nextGamInf['CupPointsLimit'] = $queriesData['GetCupPointsLimit']['NextValue'];
			$currGamInf['CupRoundsPerMap'] = $queriesData[$queries['CupRoundsPerMap']]['CurrentValue'];
			$nextGamInf['CupRoundsPerMap'] = $queriesData[$queries['CupRoundsPerMap']]['NextValue'];
			$currGamInf['CupNbWinners'] = $queriesData['GetCupNbWinners']['CurrentValue'];
			$nextGamInf['CupNbWinners'] = $queriesData['GetCupNbWinners']['NextValue'];
			$currGamInf['CupWarmUpDuration'] = $queriesData['GetCupWarmUpDuration']['CurrentValue'];
			$nextGamInf['CupWarmUpDuration'] = $queriesData['GetCupWarmUpDuration']['NextValue'];
			
			// RoundCustomPoints
			$RoundCustomPoints = implode(',', $queriesData['GetRoundCustomPoints']);
			$currGamInf['RoundCustomPoints'] = $RoundCustomPoints;
			$nextGamInf['RoundCustomPoints'] = $RoundCustomPoints;
			
			// Retour
			$out['curr'] = $currGamInf;
			$out['next'] = $nextGamInf;
		}
		
		return $out;
	}
	
	
	/**
	* Retourne la structure pour l'enregistrement des informations de jeu
	*
	* @return array
	*/
	public static function getGameInfosStructFromPOST(){
		// Mise en forme
		if($_POST['NextFinishTimeoutValue'] < 2){
			if($_POST['NextFinishTimeout'] == 0){ $FinishTimeout = 0; }
			else if($_POST['NextFinishTimeout'] == 1){ $FinishTimeout = 1; }
		}
		else{ $FinishTimeout = TimeDate::secToMillisec( intval($_POST['NextFinishTimeoutValue']) ); }
		if( array_key_exists('NextDisableRespawn', $_POST) === true ){ $DisableRespawn = false; }
		else{ $DisableRespawn = true; }
		if($_POST['NextForceShowAllOpponentsValue'] < 2){
			if($_POST['NextForceShowAllOpponents'] == 0){ $NextForceShowAllOpponents = 0; }
			else if($_POST['NextForceShowAllOpponents'] == 1){ $NextForceShowAllOpponents = 1; }
		}
		else{ $NextForceShowAllOpponents = intval($_POST['NextForceShowAllOpponentsValue']); }
		$out = array(
			'GameMode' => intval($_POST['NextGameMode']),
			'ChatTime' => TimeDate::secToMillisec( intval($_POST['NextChatTime'] - 8) ),
			'RoundsPointsLimit' => intval($_POST['NextRoundsPointsLimit']),
			'RoundsUseNewRules' => array_key_exists('NextRoundsUseNewRules', $_POST),
			'RoundsForcedLaps' => intval($_POST['NextRoundsForcedLaps']),
			'TimeAttackLimit' => TimeDate::secToMillisec( intval($_POST['NextTimeAttackLimit']) ),
			'TimeAttackSynchStartPeriod' => TimeDate::secToMillisec( intval($_POST['NextTimeAttackSynchStartPeriod']) ),
			'TeamPointsLimit' => intval($_POST['NextTeamPointsLimit']),
			'TeamMaxPoints' => intval($_POST['NextTeamMaxPoints']),
			'TeamUseNewRules' => array_key_exists('NextTeamUseNewRules', $_POST),
			'LapsNbLaps' => intval($_POST['NextLapsNbLaps']),
			'LapsTimeLimit' => TimeDate::secToMillisec( intval($_POST['NextLapsTimeLimit']) ),
			'FinishTimeout' => $FinishTimeout,
			'AllWarmUpDuration' => intval($_POST['NextAllWarmUpDuration']),
			'DisableRespawn' => $DisableRespawn,
			'ForceShowAllOpponents' => $NextForceShowAllOpponents,
			'RoundsPointsLimitNewRules' => intval($_POST['NextRoundsPointsLimit']),
			'TeamPointsLimitNewRules' => $_POST['NextTeamPointsLimit'],
			'CupPointsLimit' => intval($_POST['NextCupPointsLimit']),
			'CupRoundsPerMap' => intval($_POST['NextCupRoundsPerMap']),
			'CupNbWinners' => intval($_POST['NextCupNbWinners']),
			'CupWarmUpDuration' => intval($_POST['NextCupWarmUpDuration'])
		);
		if(SERVER_VERSION_NAME == 'ManiaPlanet'){
			$out += array('ScriptName' => Str::replaceChars($_POST['NextScriptName']));
		}
		
		return $out;
	}
	
	
	/**
	* Récupère les lignes du chat serveur
	*
	* @param bool $hideServerLines -> Masquer les lignes provenant d'un gestionnaire de serveur
	* @return string
	*/
	public static function getChatServerLines($hideServerLines = false){
		global $client;
		$out = null;
		
		// ChatLines
		if( !$client->query('GetChatLines') ){
			$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
		}
		else{
			$chatLines = $client->getResponse();
			foreach($chatLines as $line){
				// On masque les lignes du serveur si c'est demandé
				if($hideServerLines == true){
					$line = self::clearChatServerLine($line);
				}
				
				if($line == '$99FThis is a draw round.'){ $line = Utils::t('$99FThis is a draw round.'); }
				if($line == '$99FThe $<$00FBlue team$> wins this round.'){ $line = Utils::t('$99FThe $<$00FBlue team$> wins this round.'); }
				if($line == '$99FThe $<$F00Red team$> wins this round.'){ $line = Utils::t('$99FThe $<$F00Red team$> wins this round.'); }
				
				// On enlève les codes nadeo $s, $o, $w, etc
				$line = TmNick::stripNadeoCode($line);
				$line = str_replace('$>', '$z', $line);
				$line = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
				
				// Affichage des lignes
				if($line != null){
					// Convertie les codes nadeo restant en html
					$out .= TmNick::toHtml($line, 10, false, true, '#666');
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Masque les lignes générées par le gestionnaire de serveur
	*
	* @param string $line -> La ligne de la réponse GetChatLines
	* @return string
	*/
	public static function clearChatServerLine($line){
		$char = substr(utf8_decode($line), 0, 1);
		if($char == '<' || $char == '/' || substr($line, 0, 11) == '$99F[Admin]' || substr($line, 0, 12) == 'Invalid time' || $char == '?'){
			return $line;
		}
	}
	
	
	/**
	* Récupère le chemin du dossier "Maps"
	*
	* @global resource $client -> Le client doit être initialisé
	* @return string
	*/
	public static function getMapsDirectoryPath(){
		global $client;
		$out = null;
		
		// Version
		if(SERVER_VERSION_NAME == 'TmForever'){
			$queryName = 'GetTracksDirectory';
		}
		else{
			$queryName = 'GetMapsDirectory';
		}
		
		// Requête
		if( !$client->query($queryName) ){
			$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
		}
		else{
			$out = Str::toSlash( $client->getResponse() );
			if( substr($out, -1, 1) != '/'){ $out = $out.'/'; }
		}
		return $out;
	}
	
	
	/**
	* Retourne un tableau avec le nombre de maps et l'intitulé
	*
	* @param array $array -> La tableau contenant la liste des maps
	* @return array
	*/
	public static function getNbMaps($array){
		$out = array();
		
		// Test si c'est un tableau
		if( isset($array['lst']) && is_array($array['lst']) ){
			$countMapsList = count($array['lst']);
		}
		else{
			$countMapsList = 0;
		}
		
		// Compte et traduit l'intitulé
		$out['nbm']['count'] = $countMapsList;
		if($countMapsList > 1){
			$out['nbm']['title'] = Utils::t('maps');
		}
		else{
			$out['nbm']['title'] = Utils::t('map');
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des maps sur le serveur
	*
	* @global resource $client -> Le client doit être initialisé
	* @param  string   $sortBy -> Le tri à faire sur la liste
	* @return array
	*/
	public static function getMapList($sortBy = null){
		global $client;
		$out = array();
		
		// Méthodes
		if(SERVER_VERSION_NAME == 'TmForever'){
			$queryName = array(
				'mapList' => 'GetChallengeList',
				'mapIndex' => 'GetCurrentChallengeIndex'
			);
		}
		else{
			$queryName = array(
				'mapList' => 'GetMapList',
				'mapIndex' => 'GetCurrentMapIndex'
			);
		}
		
		// MAPSLIST
		if( !$client->query($queryName['mapList'], AdminServConfig::LIMIT_MAPS_LIST, 0) ){
			$out['error'] = Utils::t('Client not initialized');
		}
		else{
			$mapList = $client->getResponse();
			$countMapList = count($mapList);
			$client->query($queryName['mapIndex']);
			$out['cid'] = $client->getResponse();
			
			if( $countMapList > 0 ){
				$i = 0;
				foreach($mapList as $map){
					// Name
					$name = htmlspecialchars($map['Name'], ENT_QUOTES, 'UTF-8');
					$out['lst'][$i]['Name'] = TmNick::toHtml($name, 10, true);
					
					// Environnement
					$env = $map['Environnement'];
					if($env == 'Speed'){ $env = 'Desert'; }else if($env == 'Alpine'){ $env = 'Snow'; }
					$out['lst'][$i]['Environnement'] = $env;
					
					// Autres
					$out['lst'][$i]['UId'] = $map['UId'];
					$out['lst'][$i]['FileName'] = $map['FileName'];
					$out['lst'][$i]['Author'] = $map['Author'];
					$out['lst'][$i]['GoldTime'] = TimeDate::format($map['GoldTime']);
					$out['lst'][$i]['CopperPrice'] = $map['CopperPrice'];
					$i++;
				}
			}
			
			// Nombre de maps
			$out += self::getNbMaps($out);
			if($out['nbm']['count'] == 0){
				$out['lst'] = Utils::t('No map');
			}
			
			
			// TRI
			if($sortBy != null){
				if( is_array($out['lst']) && count($out['lst']) > 0 ){
					switch($sortBy){
						case 'name':
							uasort($out['lst'], 'AdminServSort::sortByName');
							break;
						case 'env':
							uasort($out['lst'], 'AdminServSort::sortByEnviro');
							break;
						case 'author':
							uasort($out['lst'], 'AdminServSort::sortByAuthor');
							break;
						case 'goldtime':
							uasort($out['lst'], 'AdminServSort::sortByGoldTime');
							break;
						case 'cost':
							uasort($out['lst'], 'AdminServSort::sortByPrice');
							break;
					}
				}
				$out['lst'] = array_values($out['lst']);
			}
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des maps sur le serveur et retourne un champ en particulier
	*
	* @global resource $client -> Le client doit être initialisé
	* @return array
	*/
	public static function getMapListField($field){
		global $client;
		$out = array();
		
		// Méthodes
		if(SERVER_VERSION_NAME == 'TmForever'){
			$queryName = array(
				'mapList' => 'GetChallengeList',
			);
		}
		else{
			$queryName = array(
				'mapList' => 'GetMapList',
			);
		}
		
		// Mapslist
		if( !$client->query($queryName['mapList'], AdminServConfig::LIMIT_MAPS_LIST, 0) ){
			$out['error'] = Utils::t('Client not initialized');
		}
		else{
			$mapList = $client->getResponse();
			$countMapList = count($mapList);
			if( $countMapList > 0 ){
				$i = 0;
				foreach($mapList as $map){
					switch($field){
						case 'Name':
							$name = htmlspecialchars($map['Name'], ENT_QUOTES, 'UTF-8');
							$out[] = TmNick::toHtml($name, 10, true);
							break;
						case 'Environnement':
							$env = $map['Environnement'];
							if($env == 'Speed'){ $env = 'Desert'; }else if($env == 'Alpine'){ $env = 'Snow'; }
							$out[] = $env;
							break;
						case 'UId':
							$out[] = $map['UId'];
							break;
						case 'FileName':
							$out[] = $map['FileName'];
							break;
						case 'Author':
							$out[] = $map['Author'];
							break;
						case 'GoldTime':
							$out[] = TimeDate::format($map['GoldTime']);
							break;
						case 'CopperPrice':
							$out[] = $map['CopperPrice'];
							break;
					}
					$i++;
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des maps en local à partir d'un chemin
	*
	* @param string $path   -> Le chemin du dossier à lister
	* @param string $sortBy -> Le tri à faire sur la liste
	* @return array
	*/
	public static function getLocalMapList($path, $sortBy = null){
		global $client;
		$out = array();
		$currentMapsListUId = null;
		if(AdminServConfig::LOCAL_GET_MAPS_ON_SERVER){
			$currentMapsListUId = self::getMapListField('UId');
		}
		$pathFromMapsFolder = self::getMapsDirectoryPath();
		$pathFromMapsFolder = str_replace($pathFromMapsFolder, '', $path);
		
		if( class_exists('Folder') && class_exists('GBXChallengeFetcher') ){
			$directory = Folder::read($path, AdminServConfig::$MAPS_HIDDEN_FOLDERS, array(), intval(AdminServConfig::RECENT_STATUS_PERIOD * 3600) );
			if( is_array($directory) ){
				$countMapList = count($directory['files']);
				if($countMapList > 0){
					$i = 0;
					foreach($directory['files'] as $file => $values){
						if( in_array(File::getDoubleExtension($file), AdminServConfig::$MAP_EXTENSION) ){
							// Données
							$Gbx = new GBXChallengeFetcher($path.$file, true);
							
							// Name
							$name = htmlspecialchars($Gbx->name, ENT_QUOTES, 'UTF-8');
							$out['lst'][$i]['Name'] = TmNick::toHtml($name, 10, true);
							
							// Environnement
							$env = $Gbx->envir;
							if($env == 'Speed'){ $env = 'Desert'; }else if($env == 'Alpine'){ $env = 'Snow'; }
							$out['lst'][$i]['Environnement'] = $env;
							
							// Autres
							$out['lst'][$i]['FileName'] = $pathFromMapsFolder.$file;
							$out['lst'][$i]['UId'] = $Gbx->uid;
							$out['lst'][$i]['Author'] = $Gbx->author;
							$out['lst'][$i]['Recent'] = $values['recent'];
							
							// On server
							$out['lst'][$i]['OnServer'] = false;
							if($currentMapsListUId){
								if( in_array($out['lst'][$i]['UId'], $currentMapsListUId) ){
									$out['lst'][$i]['OnServer'] = true;
								}
							}
							$i++;
						}
					}
				}
				
				// Nombre de maps
				$out += self::getNbMaps($out);
				if($out['nbm']['count'] == 0){
					$out['lst'] = Utils::t('No map');
				}
				
				
				// TRIS
				if($sortBy != null){
					if( is_array($out['lst']) && count($out['lst']) > 0 ){
						switch($sortBy){
							case 'name':
								uasort($out['lst'], 'AdminServSort::sortByName');
								break;
							case 'env':
								uasort($out['lst'], 'AdminServSort::sortByEnviro');
								break;
							case 'author':
								uasort($out['lst'], 'AdminServSort::sortByAuthor');
								break;
						}
					}
				}
			}
			else{
				// Retour des erreurs de la méthode read
				$out = $directory;
			}
		}
		else{
			$out['error'] = 'Class "Folder" or "GBXChallengeFetcher" not found';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des matchsettings en local à partir d'un chemin
	*
	* @param string $path -> Le chemin du dossier à lister
	* @return array
	*/
	public static function getLocalMatchSettingList($path){
		$out = array();
		
		if( class_exists('Folder') && class_exists('File') ){
			$directory = Folder::read($path, AdminServConfig::$MATCHSET_HIDDEN_FOLDERS, array(), intval(AdminServConfig::RECENT_STATUS_PERIOD * 3600) );
			if( is_array($directory) ){
				$countMatchsetList = count($directory['files']);
				if($countMatchsetList > 0){
					$i = 0;
					foreach($directory['files'] as $file => $values){
						if( in_array(File::getExtension($file), AdminServConfig::$MATCHSET_EXTENSION) ){
							// Données
							$matchsetData = self::getMatchSettingsData($path.$file, array('maps'));
							$matchsetNbmCount = 0;
							if( isset($matchsetData['maps']) ){
								$matchsetNbmCount = count($matchsetData['maps']);
							}
							if($matchsetNbmCount > 1){
								$matchsetNbm = $matchsetNbmCount . ' '.Utils::t('maps');
							}
							else{
								$matchsetNbm = $matchsetNbmCount . ' '.Utils::t('map');
							}
							
							$out['lst'][$i]['Name'] = substr($file, 0, -4);
							$out['lst'][$i]['FileName'] = $file;
							$out['lst'][$i]['Nbm'] = $matchsetNbm;
							$out['lst'][$i]['Mtime'] = $values['mtime'];
							$out['lst'][$i]['Recent'] = $values['recent'];
							$i++;
						}
					}
				}
				
				// Nombre de maps
				if( isset($out['lst']) && is_array($out['lst']) ){
					$out['nbm']['count'] = $countMatchsetList;
					if( count($out['lst']) > 1){
						$out['nbm']['title'] = Utils::t('matchsettings');
					}
					else{
						$out['nbm']['title'] = Utils::t('matchsetting');
					}
				}
				else{
					$out['nbm']['count'] = 0;
					$out['nbm']['title'] = Utils::t('matchsetting');
				}
				if($out['nbm']['count'] == 0){
					$out['lst'] = Utils::t('No matchsetting');
				}
			}
			else{
				// Retour des erreurs de la méthode read
				$out = $directory;
			}
		}
		else{
			$out['error'] = 'Class "Folder" or "File" not found';
		}
		
		return $out;
	}
	
	
	/**
	* Enregistre la sélection du MatchSettings en session
	*
	* @param array $maps -> Le tableau de maps à ajouter à la sélection
	*/
	public static function saveMatchSettingSelection($maps = array() ){
		// Liste des maps
		$out = array();
		if( isset($_SESSION['adminserv']['matchset_maps_selected']) ){
			$mapsSelected = $_SESSION['adminserv']['matchset_maps_selected'];
			if( isset($mapsSelected['lst']) && is_array($mapsSelected['lst']) && count($mapsSelected['lst']) > 0 ){
				foreach($mapsSelected['lst'] as $id => $values){
					$out['lst'][] = $values;
				}
			}
		}
		if( isset($maps['lst']) && is_array($maps['lst']) && count($maps['lst']) > 0 ){
			foreach($maps['lst'] as $id => $values){
				$out['lst'][] = $values;
			}
		}
		
		// Nombre de maps
		$out += self::getNbMaps($out);
		if($out['nbm']['count'] == 0){
			$out['lst'] = Utils::t('No map');
		}
		
		// Mise à jour de la session
		$_SESSION['adminserv']['matchset_maps_selected'] = $out;
	}
	
	
	/**
	* Enregistre un MatchSettings
	*
	* @param string $filename -> L'url du dossier dans lequel le MatchSettings sera crée
	* @param array  $struct   -> La structure du MatchSettings avec ses données
	* $struct = Array
	* (
	*  [gameinfos] => Array
	*   (
	*    [game_mode] => 0
	*    etc...
	*   )
	*  [hotseat] => Array()
	*  [filter] => Array()
	*  [startindex] => 1
	*  [map] => Array
	*   (
	*    [8bDoQMwzUllV0D9eu7hSth3rQs6] => name.Map.Gbx
	*    etc...
	*   )
	* )
	* @return true si réussi, sinon une erreur
	*/
	public static function saveMatchSettings($filename, $struct){
		$out = false;
		
		// Jeu
		if(SERVER_VERSION_NAME == 'TmForever'){
			$mapField = 'challenge';
		}
		else{
			$mapField = 'map';
		}
		
		// Génération du XML
		$out = '<?xml version="1.0" encoding="utf-8" ?>'."\n"
		."<playlist>\n";
			// GameInfos, Hotseat, Filter
			$structFields = array(
				'gameinfos',
				'hotseat',
				'filter'
			);
			foreach($structFields as $strucField){
				if( isset($struct[$strucField]) && count($struct[$strucField]) > 0 ){
					$out .= "\t<$strucField>\n";
						foreach($struct[$strucField] as $field => $value){
							$out .= "\t\t<$field>$value</$field>\n";
						}
					$out .= "\t</$strucField>\n\n";
				}
			}
			
			// Maps
			$out .= "\t<startindex>".$struct['startindex']."</startindex>\n";
			if( isset($struct[$mapField]) && count($struct[$mapField]) > 0 ){
				foreach($struct[$mapField] as $ident => $file){
					$out .= "\t<$mapField>\n"
						."\t\t<file>$file</file>\n"
						."\t\t<ident>$ident</ident>\n"
					."\t</$mapField>\n";
				}
			}
		$out .= "</playlist>\n";
		
		// Création XML
		if( ! @$newXMLObject = simplexml_load_string($out) ){
			$out = Utils::t('text->XML conversion error');
		}
		else{
			if( !$newXMLObject->asXML($filename) ){
				$out = Utils::t('Saving XML file error');
			}
			else{
				$out = true;
			}
		}
		
		return $out;
	}
	
	
	/**
	* Extrait les données d'un MatchSettings et renvoi un tableau
	*
	* @param string $filename -> L'url du MatchSettings
	* @param array  $list     -> Liste des champs à retourner
	* @return array si le fichier existe, sinon false
	*/
	public static function getMatchSettingsData($filename, $list = array('gameinfos', 'hotseat', 'filter', 'maps') ){
		$out = array();
		$xml = null;
		
		// Chargement du fichier XML
		if( @file_exists($filename) ){
			if( !($xml = @simplexml_load_file($filename)) ){
				$out['error'] = 'simplexml_load_file error';
			}
		}
		
		// Lecture du fichier XML
		if($xml){
			// Jeu
			if(SERVER_VERSION_NAME == 'TmForever'){
				$mapsField = 'challenge';
			}
			else{
				$mapsField = 'map';
			}
			
			// Gameinfos
			if( in_array('gameinfos', $list) ){
				if( isset($xml->gameinfos) && count($xml->gameinfos) > 0 ){
					$gameinfos = $xml->gameinfos;
					$out['gameinfos']['GameMode'] = (string)$gameinfos->game_mode;
					$out['gameinfos']['ChatTime'] = (string)$gameinfos->chat_time;
					$out['gameinfos']['FinishTimeout'] = (string)$gameinfos->finishtimeout;
					$out['gameinfos']['AllWarmUpDuration'] = (string)$gameinfos->allwarmupduration;
					$out['gameinfos']['DisableRespawn'] = (string)$gameinfos->disablerespawn;
					$out['gameinfos']['ForceShowAllOpponents'] = (string)$gameinfos->forceshowallopponents;
					$out['gameinfos']['RoundsPointsLimit'] = (string)$gameinfos->rounds_pointslimit;
					$out['gameinfos']['RoundsUseNewRules'] = (string)$gameinfos->rounds_usenewrules;
					$out['gameinfos']['RoundsForcedLaps'] = (string)$gameinfos->rounds_forcedlaps;
					if(SERVER_VERSION_NAME == 'TmForever'){
						$out['gameinfos']['rounds_pointslimitnewrules'] = (string)$gameinfos->rounds_pointslimitnewrules;
					}
					$out['gameinfos']['TeamPointsLimit'] = (string)$gameinfos->team_pointslimit;
					$out['gameinfos']['TeamMaxPoints'] = (string)$gameinfos->team_maxpoints;
					$out['gameinfos']['TeamUseNewRules'] = (string)$gameinfos->team_usenewrules;
					if(SERVER_VERSION_NAME == 'TmForever'){
						$out['gameinfos']['team_pointslimitnewrules'] = (string)$gameinfos->team_pointslimitnewrules;
					}
					$out['gameinfos']['TimeAttackLimit'] = (string)$gameinfos->timeattack_limit;
					$out['gameinfos']['TimeAttackSynchStartPeriod'] = (string)$gameinfos->timeattack_synchstartperiod;
					$out['gameinfos']['LapsNbLaps'] = (string)$gameinfos->laps_nblaps;
					$out['gameinfos']['LapsTimeLimit'] = (string)$gameinfos->laps_timelimit;
					$out['gameinfos']['CupPointsLimit'] = (string)$gameinfos->cup_pointslimit;
					$out['gameinfos']['CupRoundsPerMap'] = (string)$gameinfos->cup_roundsperchallenge;
					$out['gameinfos']['CupNbWinners'] = (string)$gameinfos->cup_nbwinners;
					$out['gameinfos']['CupWarmUpDuration'] = (string)$gameinfos->cup_warmupduration;
				}
			}
			
			// Hotseat
			if( in_array('hotseat', $list) ){
				if( isset($xml->hotseat) && count($xml->hotseat) > 0 ){
					$hotseat = $xml->hotseat;
					$out['hotseat']['GameMode'] = (string)$hotseat->game_mode;
					$out['hotseat']['TimeLimit'] = (string)$hotseat->time_limit;
					$out['hotseat']['RoundsCount'] = (string)$hotseat->rounds_count;
				}
			}
			
			// Filter
			if( in_array('filter', $list) ){
				if( isset($xml->filter) && count($xml->filter) > 0 ){
					$filter = $xml->filter;
					$out['filter']['IsLan'] = (string)$filter->is_lan;
					$out['filter']['IsInternet'] = (string)$filter->is_internet;
					$out['filter']['IsSolo'] = (string)$filter->is_solo;
					$out['filter']['IsHotseat'] = (string)$filter->is_hotseat;
					$out['filter']['SortIndex'] = (string)$filter->sort_index;
					$out['filter']['RandomMapOrder'] = (string)$filter->random_map_order;
					$out['filter']['ForceDefaultGameMode'] = (string)$filter->force_default_gamemode;
				}
			}
			
			// Maps
			if( in_array('maps', $list) ){
				$out['StartIndex'] = (string)$xml->startindex;
				if( isset($xml->$mapsField) && count($xml->$mapsField) > 0 ){
					foreach($xml->$mapsField as $map){
						$out['maps'][(string)$map->ident] = (string)$map->file;
					}
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Met en forme les données des maps du MatchSettings
	*
	* @global resource $client -> Le client doit être initialisé
	* @param  array    $maps   -> Le tableau extrait du matchsettings : assoc array(ident => filename)
	* @return array
	*/
	public static function getMapListFromMatchSetting($maps){
		global $client;
		$out = array();
		$path = self::getMapsDirectoryPath();
		$countMapList = count($maps);
		
		if($countMapList > 0){
			$i = 0;
			foreach($maps as $mapUId => $mapFileName){
				if( in_array(File::getDoubleExtension($mapFileName), AdminServConfig::$MAP_EXTENSION) ){
					// Données
					$Gbx = new GBXChallengeFetcher($path.Str::toSlash($mapFileName), true);
					
					// Name
					$name = htmlspecialchars($Gbx->name, ENT_QUOTES, 'UTF-8');
					$out['lst'][$i]['Name'] = TmNick::toHtml($name, 10, true);
					
					// Environnement
					$env = $Gbx->envir;
					if($env == 'Speed'){ $env = 'Desert'; }else if($env == 'Alpine'){ $env = 'Snow'; }
					$out['lst'][$i]['Environnement'] = $env;
					
					// Autres
					$out['lst'][$i]['FileName'] = $mapFileName;
					$out['lst'][$i]['UId'] = $Gbx->uid;
					$out['lst'][$i]['Author'] = $Gbx->author;
					$i++;
				}
			}
		}
		
		// Nombre de maps
		$out += self::getNbMaps($out);
		if($out['nbm']['count'] == 0){
			$out['lst'] = Utils::t('No map');
		}
		
		return $out;
	}
	
	
	/**
	* Extrait les données d'une playlist (blacklist ou guestlist) et renvoi un tableau
	*
	* @param string $filename -> L'url de la playlist
	* @return array si le fichier existe, sinon false
	*/
	public static function getPlaylistData($filename){
		$out = array();
		
		// Chargement du fichier XML
		$xml = null;
		if( @file_exists($filename) ){
			if( !($xml = @simplexml_load_file($filename)) ){
				$out['error'] = 'simplexml_load_file error';
			}
		}
		
		// Lecture du fichier XML
		if($xml){
			$out['type'] = @$xml->getName();
			foreach($xml->player as $player){
				$out['logins'][] = (string)$player->login;
			}
		}
		
		return $out;
	}
}



/**
* Classe pour le traitement des tris AdminServ
*/
abstract class AdminServSort {
	
	
	public static function sortByNickName($a, $b){
		// Modification
		$a['NickName'] = TmNick::toText($a['NickName']);
		$b['NickName'] = TmNick::toText($b['NickName']);
		
		// Comparaison
		if($a['NickName'] == $b['NickName']){
			return 0;
		}
		if($a['NickName'] < $b['NickName']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByLadderRanking($a, $b){
		if($a['LadderRanking'] == $b['LadderRanking']){
			return 0;
		}
		if($a['LadderRanking'] < $b['LadderRanking']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByLogin($a, $b){
		if($a['Login'] == $b['Login']){
			return 0;
		}
		if($a['Login'] < $b['Login']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByStatus($a, $b){
		if($a['IsSpectator'] == $b['IsSpectator']){
			return 0;
		}
		if($a['IsSpectator'] < $b['IsSpectator']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByTeam($a, $b){
		// Modification
		if($a['TeamId'] == 0){
			$a['TeamId'] = 'blue';
		}else if($a['TeamId'] == 1){
			$a['TeamId'] = 'red';
		}else{
			$a['TeamId'] = 'spectator';
		}
		if($b['TeamId'] == 0){
			$b['TeamId'] = 'blue';
		}else if($b['TeamId'] == 1){
			$b['TeamId'] = 'red';
		}else{
			$b['TeamId'] = 'spectator';
		}
		
		// Comparaison
		if($a['TeamId'] == $b['TeamId']){
			return 0;
		}
		if($a['TeamId'] < $b['TeamId']){
			return -1;
		}else{
			return 1;
		}
	}
	
	/* Maps-list */
	public static function sortByName($a, $b){
		// Modification
		$a['Name'] = TmNick::toText($a['Name']);
		$b['Name'] = TmNick::toText($b['Name']);
		
		// Comparaison
		if($a['Name'] == $b['Name']){
			return 0;
		}
		if($a['Name'] < $b['Name']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByEnviro($a, $b){
		// Modification
		if($a['Environnement'] == 'Speed'){
			$a['Environnement'] = 'Desert';
		}
		if($b['Environnement'] == 'Speed'){
			$b['Environnement'] = 'Desert';
		}
		if($a['Environnement'] == 'Alpine'){
			$a['Environnement'] = 'Snow';
		}
		if($b['Environnement'] == 'Alpine'){
			$b['Environnement'] = 'Snow';
		}
		
		// Comparaison
		if($a['Environnement'] == $b['Environnement']){
			return 0;
		}
		if($a['Environnement'] < $b['Environnement']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByAuthor($a, $b){
		if($a['Author'] == $b['Author']){
			return 0;
		}
		if($a['Author'] < $b['Author']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByGoldTime($a, $b){
		if($a['GoldTime'] == $b['GoldTime']){
			return 0;
		}
		if($a['GoldTime'] < $b['GoldTime']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByPrice($a, $b){
		if($a['CopperPrice'] == $b['CopperPrice']){
			return 0;
		}
		if($a['CopperPrice'] < $b['CopperPrice']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByRank($a, $b){
		if($a['Rank'] == $b['Rank']){
			return 0;
		}
		if($a['Rank'] < $b['Rank']){
			return -1;
		}else{
			return 1;
		}
	}
}


/**
* Classe pour le traitement des logs AdminServ
*/
abstract class AdminServLogs {
	/**
	* Globales
	*/
	public static $LOGS_PATH = './logs/';
	
	/**
	* Initialise les logs (vérification des droits, création des fichiers)
	* @return bool
	*/
	public static function initialize(){
		$out = false;
		
		if( in_array(true, AdminServConfig::$LOGS) ){
			if( file_exists(self::$LOGS_PATH) ){
				if( is_writable(self::$LOGS_PATH) ){
					$out = true;
				}
				else{
					AdminServ::error( Utils::t('The folder "logs" is not writable.') );
				}
			}
			else{
				AdminServ::error( Utils::t('The folder "logs" does not exist.') );
			}
		}
		
		if($out){
			if( count(AdminServConfig::$LOGS) > 0 ){
				foreach(AdminServConfig::$LOGS as $file => $activate){
					$path = self::$LOGS_PATH.$file.'.log';
					if($activate && !file_exists($path) ){
						if( File::save($path) !== true ){
							AdminServ::error( Utils::t('Unable to create log file:').' '.$file.'.');
							$out = false;
							break;
						}
					}
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Ajoute un log au fichier correspondant
	*
	* @param string $type -> Type de log : access, action, etc
	* @param string $str  -> Ligne de log à écrire
	* @return bool
	*/
	public static function add($type, $str){
		$out = false;
		$type = strtolower($type);
		if( defined('USER_PAGE') ){ $userPage = USER_PAGE; }else{ $userPage = 'index'; }
		$str = '['.date('d/m/Y H:i:s').'] ['.$userPage.'] ['.$_SERVER['REMOTE_ADDR'].'] '.utf8_decode($str)."\n";
		$path = self::$LOGS_PATH.$type.'.log';
		
		if( file_exists($path) ){
			if( File::save($path, utf8_encode($str) ) !== true ){
				AdminServ::error( Utils::t('Unable to add log in file:').' '.$type.'.');
			}
			else{
				$out = true;
			}
		}
		
		return $out;
	}
}


/**
* Classe pour la gestion de la configuration serveur
*/
abstract class AdminServServerConfig {
	
	/**
	* Globales
	*/
	private static $CONFIG_PATH = './config/';
	private static $CONFIG_FILENAME = 'servers.cfg.php';
	private static $CONFIG_START_TEMPLATE = "<?php\nclass ServerConfig {\n\tpublic static \$SERVERS = array(\n\t\t/********************* SERVER CONFIGURATION *********************/\n\t\t\n";
	private static $CONFIG_END_TEMPLATE =  "\t);\n}\n?>";
	
	
	/**
	* Détermine si il y a au moins un serveur disponible
	*
	* @return bool
	*/
	public static function hasServer(){
		$out = false;
		
		if( isset(ServerConfig::$SERVERS) && count(ServerConfig::$SERVERS) > 0 && !isset(ServerConfig::$SERVERS['new server name']) && !isset(ServerConfig::$SERVERS['']) ){
			$out = true;
		}
		
		return $out;
	}
	
	
	/**
	* Récupère les données d'un serveur
	*
	* @param string $serverName -> Le nom du serveur dans la config
	* @return array
	*/
	public static function getServer($serverName){
		$out = null;
		
		if( self::hasServer() ){
			if( isset(ServerConfig::$SERVERS[$serverName]) ){
				$out = ServerConfig::$SERVERS[$serverName];
			}
			else{
				$out = Utils::t('This server does not exist');
			}
		}
		else{
			$out = Utils::t('No server available');
		}
		
		return $out;
	}
	
	
	/**
	* Retourne l'identifiant du serveur dans la config
	*
	* @param  string $serverName -> Le nom du serveur dans la config
	* @return int
	*/
	public static function getServerId($serverName){
		$id = 0;
		$servers = ServerConfig::$SERVERS;
		$countServers = count($servers);
		
		// On cherche la position du serveur à partir de son nom
		if( $countServers > 0 ){
			foreach($servers as $server_name => $server_values){
				if($server_name == $serverName){
					break;
				}
				else{
					$id++;
				}
			}
		}
		
		// Si l'id = le nb total de serveur -> pas trouvé
		if($id == $countServers ){
			return -1;
		}else{
			return $id;
		}
	}
	
	
	/**
	* Retourne le nom du serveur dans la config
	*
	* @param  int $serverId -> L'id du serveur dans la config
	* @return string
	*/
	public static function getServerName($serverId){
		$out = null;
		$servers = ServerConfig::$SERVERS;
		$countServers = count($servers);
		
		if( $countServers > 0 ){
			$i = 0;
			foreach($servers as $serverName => $serverValues){
				if($i == $serverId){
					$out = $serverName;
					break;
				}
				else{
					$i++;
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Créer le template d'un serveur
	*
	* @param array $serverData -> assoc array(name, address, port, matchsettings, adminlevel => array(SuperAdmin, Admin, User));
	*/
	public static function getServerTemplate($serverData){
		$out = "\t\t'".$serverData['name']."' => array(\n"
			."\t\t\t'address'\t\t=> '".$serverData['address']."',\n"
			."\t\t\t'port'\t\t\t=> ".$serverData['port'].",\n"
			."\t\t\t'matchsettings'\t=> '".$serverData['matchsettings']."',\n"
			."\t\t\t'adminlevel'\t=> array('SuperAdmin' => ";
			if( is_array($serverData['adminlevel']['SuperAdmin']) ){
				$out .= "array('".implode("', '", str_replace(' ', '', $serverData['adminlevel']['SuperAdmin']))."')";
			}else{
				$out .= "'".$serverData['adminlevel']['SuperAdmin']."'";
			}
			$out .= ", 'Admin' => ";
			if( is_array($serverData['adminlevel']['Admin']) ){
				$out .= "array('".implode("', '",  str_replace(' ', '', $serverData['adminlevel']['Admin']))."')";
			}else{
				$out .= "'".$serverData['adminlevel']['Admin']."'";
			}
			$out .= ", 'User' => ";
			if( is_array($serverData['adminlevel']['User']) ){
				$out .= "array('".implode("', '",  str_replace(' ', '', $serverData['adminlevel']['User']))."')";
			}else{
				$out .= "'".$serverData['adminlevel']['User']."'";
			}
		$out .= ")\n\t\t),\n";
		
		return $out;
	}
	
	
	/**
	* Sauvegarde le fichier de configuration des serveurs
	*
	* @param array $serverData -> assoc array(name, address, port, matchsettings, adminlevel => array(SuperAdmin, Admin, User));
	* @param int   $editServer -> Id du serveur à éditer
	* @param array $serverList -> Liste des serveurs de la config
	* @return bool or string error
	*/
	public static function saveServerConfig($serverData = array(), $editServer = -1, $serverList = array() ){
		// Liste des serveurs
		if( isset($serverList) && count($serverList) > 0 ){
			$servers = $serverList;
		}else{
			$servers = ServerConfig::$SERVERS;
		}
		
		// Template
		$fileTemplate = self::$CONFIG_START_TEMPLATE;
			$i = 0;
			foreach($servers as $serverName => $serverValues){
				// Édition
				if($i == $editServer && isset($serverData) && count($serverData) > 0 ){
					$fileTemplate .= self::getServerTemplate($serverData);
				}
				else{
					// Liste des serveurs existant
					$fileTemplate .= self::getServerTemplate(
						array(
							'name' => $serverName,
							'address' => $serverValues['address'],
							'port' => $serverValues['port'],
							'matchsettings' => $serverValues['matchsettings'],
							'adminlevel' => array(
								'SuperAdmin' => $serverValues['adminlevel']['SuperAdmin'],
								'Admin' => $serverValues['adminlevel']['Admin'],
								'User' => $serverValues['adminlevel']['User']
							)
						)
					);
				}
				$i++;
			}
			
			// Ajout d'un nouveau
			if($editServer === -1 && isset($serverData) && count($serverData) > 0 ){
				$fileTemplate .= self::getServerTemplate($serverData);
			}
		$fileTemplate .= self::$CONFIG_END_TEMPLATE;
		
		// Enregistrement
		return File::save(self::$CONFIG_PATH.self::$CONFIG_FILENAME, $fileTemplate, false);
	}
}



/**
* Classe pour la gestion des plugins
*/
abstract class AdminServPlugin {
	
	/**
	* Tente de récupérer une config des plugins à partir d'un autre fichier
	*/
	public static function setPluginsList(){
		$otherPluginsList = AdminServConfig::PLUGINS_LIST;
		
		if($otherPluginsList){
			// Récupération du fichier
			if( file_exists($otherPluginsList) ){
				include_once $otherPluginsList;
				
				if( isset($PLUGINS) ){
					if(AdminServConfig::PLUGINS_LIST_TYPE == 'add'){
						ExtensionConfig::$PLUGINS = array_merge(ExtensionConfig::$PLUGINS, $PLUGINS);
					}
					else{
						ExtensionConfig::$PLUGINS = $PLUGINS;
					}
				}
				else{
					AdminServ::error( Utils::t('Variable "$PLUGINS" not found.') );
				}
			}
			else{
				AdminServ::error( Utils::t('Cannot include another plugins config file.') );
			}
		}
	}
	
	
	/**
	* Détermine si il y a au moins un plugin disponible
	*
	* @param string $pluginName -> Test un plugin en particulier
	* @return bool
	*/
	public static function hasPlugin($pluginName = null){
		$out = false;
		
		if( class_exists('ExtensionConfig') ){
			if( isset(ExtensionConfig::$PLUGINS) && count(ExtensionConfig::$PLUGINS) > 0 ){
				if($pluginName){
					if( in_array($pluginName, ExtensionConfig::$PLUGINS) ){
						$out = true;
					}
				}
				else{
					$out = true;
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Récupère le plugin courant
	*
	* @return pluginName
	*/
	public static function getCurrent(){
		$out = null;
		
		if( defined('USER_PAGE') ){
			$pageEx = explode('-', USER_PAGE);
			if( count($pageEx) > 0 && isset($pageEx[0]) && $pageEx[0] == 'plugins' ){
				if( isset($pageEx[1]) && $pageEx[1] != 'list' ){
					$out = $pageEx[1];
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la config du plugin grâce au fichier config.ini
	*
	* @param string $pluginName  -> Le nom du dossier plugin
	* @param string $returnField -> Retourner un champ en particulier
	* @return array ou string si le 2ème paramètre est spécifié
	*/
	public static function getConfig($pluginName = null, $returnField = null){
		$out = null;
		if($pluginName == null){
			$pluginName = CURRENT_PLUGIN;
		}
		$path = AdminServConfig::PATH_PLUGINS .$pluginName.'/config.ini';
		
		if( file_exists($path) ){
			$ini = parse_ini_file($path);
			if($returnField && isset($ini[$returnField]) ){
				$out = $ini[$returnField];
			}
			else{
				$out = $ini;
			}
		}
		
		return $out;
	}
	
	
	/**
	* Retourne une liste html pour le menu des plugins
	*
	* @return html
	*/
	public static function getMenuList(){
		$out = null;
		$pluginsList = array();
		if( count(ExtensionConfig::$PLUGINS) > 0 ){
			foreach(ExtensionConfig::$PLUGINS as $plugin){
				$pluginInfos = self::getConfig($plugin);
				if($pluginInfos['game'] == 'all' || $pluginInfos['game'] == SERVER_VERSION_NAME){
					$pluginsList[$plugin] = $pluginInfos;
				}
			}
		}
		
		if( count($pluginsList) > 0 ){
			$out = '<nav class="vertical-nav">'
				.'<ul>';
					foreach($pluginsList as $plugin => $infos){
						$out .= '<li><a '; if(self::getCurrent() == $plugin){ $out .= 'class="active" '; } $out .= 'href="?p=plugins-'.$plugin.'" title="Version : '.$infos['version'].'">'.$infos['name'].'</a></li>';
					}
			$out .= '</ul>'
			.'</nav>';
		}
		
		return $out;
	}
	
	
	/**
	* Compte le nombre de plugins installés
	*
	* @return array
	*/
	public static function countPlugins(){
		$out = array();
		$pluginsList = array();
		if( self::hasPlugin() ){
			foreach(ExtensionConfig::$PLUGINS as $plugin){
				$pluginInfos = self::getConfig($plugin);
				if($pluginInfos['game'] == 'all' || $pluginInfos['game'] == SERVER_VERSION_NAME){
					$pluginsList[] = $plugin;
				}
			}
		}
		
		$out['count'] = count($pluginsList);
		if($out['count'] > 1){
			$out['title'] = Utils::t('plugins installed');
		}
		else{
			$out['title'] = Utils::t('plugin installed');
		}
		
		return $out;
	}
	
	
	/**
	* Inclue tous les fichiers necessaire au fonctionnement du plugin
	*
	* @param string $pluginName -> Le nom du dossier plugin
	* @return html
	*/
	public static function getPlugin($pluginName = null){
		global $client, $translate;
		if($pluginName == null){
			$pluginName = CURRENT_PLUGIN;
		}
		
		$file = AdminServConfig::PATH_PLUGINS .$pluginName.'/index.php';
		if( file_exists($file) ){
			AdminServUI::getHeader();
			echo '<section class="plugins hasMenu">'
				.'<section class="cadre left menu">'
					.self::getMenuList()
				.'</section>'
				
				.'<section class="cadre right">'
					.'<h1>'.self::getConfig($pluginName, 'name').'</h1>';
					require_once $file;
				echo '</section>'
			.'</section>';
			AdminServUI::getFooter();
		}
	}
	
	
	/**
	* Récupère le chemin du dossier plugin
	*
	* @param string $pluginName -> Le nom du dossier plugin
	* @return string
	*/
	public static function getPluginPath($pluginName = null){
		$out = null;
		if($pluginName == null){
			$pluginName = CURRENT_PLUGIN;
		}
		$path = AdminServConfig::PATH_PLUGINS;
		
		if($path && $pluginName){
			$out = $path.$pluginName.'/';
		}
		
		return $out;
	}
}
?>