<?php

/**
* Classe pour l'interface d'AdminServ
*/
class AdminServUI {
	
	/**
	* Récupère le titre de l'application
	*
	* @param string $type -> Retourner "str" ou "html"
	* @return string
	*/
	public static function getTitle($type = 'str'){
		$out = null;
		$title = AdminServConfig::TITLE;
		if(!$title){
			$title = 'Admin,Serv';
		}
		
		if($type == 'html'){
			if( strstr($title, ',') ){
				$out = str_replace(',', '<span class="title-color">', $title).'</span>';
			}
			else{
				$out = $title;
			}
		}
		else{
			$out = str_replace(',', '', $title);
		}
		
		return $out;
	}
	
	
	/**
	* Vérifie s'il y a bien une config de theme
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
		
		if($forceTheme){
			$_SESSION['theme'] = $forceTheme;
			$saveCookie = true;
		}
		else{
			if( !isset($_SESSION['theme']) ){
				if( isset($_COOKIE['adminserv_user']) ){
					$_SESSION['theme'] = Utils::readCookieData('adminserv_user', 0);
				}
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
			$cookieData = array(
				$_SESSION['theme'],
				self::getLang(),
				Utils::readCookieData('adminserv_user', 2),
				Utils::readCookieData('adminserv_user', 3)
			);
			
			Utils::addCookieData('adminserv_user', $cookieData, AdminServConfig::COOKIE_EXPIRE);
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
		$countList = count($list);
		
		// Page courante
		if(USER_PAGE && USER_PAGE != 'index'){
			$param = '?p='. USER_PAGE .'&amp;th=';
		}
		else{
			$param = '?th=';
		}
		
		if( $countList > 0 ){
			$out .= '<ul>';
			// S'il y a un thème courant, on le place en 1er
			if( count($currentTheme) > 0 ){
				$currentThemeName = key($currentTheme);
				$currentThemeColor = current($currentTheme);
				unset($list[$currentThemeName]);
				$out .= '<li><a tabindex="-1" class="theme-color" style="background-color: '.$currentThemeColor[0].';" href="'.$param.$currentThemeName.'" title="'.Utils::t( ucfirst($currentThemeName) ).'"></a></li>';
			}
			foreach($list as $name => $color){
				$out .= '<li><a tabindex="-1" class="theme-color" style="background-color: '.$color[0].';" href="'.$param.$name.'" title="'.Utils::t( ucfirst($name) ).'"></a></li>';
			}
			$out .= '</ul>';
		}
		
		return $out;
	}
	
	
	/**
	* Vérifie s'il y a bien une config de langue
	*/
	public static function hasLang(){
		$out = false;
		
		if( class_exists('ExtensionConfig') && isset(ExtensionConfig::$LANG) && count(ExtensionConfig::$LANG) > 0 ){
			$out = true;
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la langue courante
	*
	* @param string $forceLang -> Forcer l'utilisation de la langue
	* @return $_SESSION['lang']
	*/
	public static function getLang($forceLang = null){
		$saveCookie = false;
		
		if($forceLang){
			$_SESSION['lang'] = $forceLang;
			$saveCookie = true;
		}
		else{
			if( !isset($_SESSION['lang']) ){
				if( isset($_COOKIE['adminserv_user']) ){
					$_SESSION['lang'] = Utils::readCookieData('adminserv_user', 1);
				}
				else{
					if( AdminServConfig::DEFAULT_LANGUAGE == 'auto' ){
						$_SESSION['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
					}
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
			$cookieData = array(
				self::getTheme(),
				$_SESSION['lang'],
				Utils::readCookieData('adminserv_user', 2),
				Utils::readCookieData('adminserv_user', 3)
			);
			
			Utils::addCookieData('adminserv_user', $cookieData, AdminServConfig::COOKIE_EXPIRE);
		}
		
		return strtolower($_SESSION['lang']);
	}
	
	
	/**
	* Récupère la liste des langues
	*/
	public static function getLangList($currentLang = array() ){
		$out = null;
		$list = array();
		if( self::hasLang() ){
			$list = ExtensionConfig::$LANG;
		}
		$countList = count($list);
		
		// Page courante
		if(USER_PAGE && USER_PAGE != 'index'){
			$param = '?p='. USER_PAGE .'&amp;lg=';
		}
		else{
			$param = '?lg=';
		}
		
		// Liste de toutes les langues
		if( $countList > 0 ){
			$out .= '<ul>';
			// S'il y a une langue courante, on la place en 1er
			if( count($currentLang) > 0 ){
				$currentLangCode = key($currentLang);
				$currentLangName = current($currentLang);
				unset($list[$currentLangCode]);
				$out .= '<li><a tabindex="-1" class="lang-flag" style="background-image: url('. AdminServConfig::PATH_RESOURCES .'images/lang/'.$currentLangCode.'.png);" href="'.$param.$currentLangCode.'" title="'.$currentLangName.'"></a></li>';
			}
			foreach($list as $code => $name){
				$out .= '<li><a tabindex="-1" class="lang-flag" style="background-image: url('. AdminServConfig::PATH_RESOURCES .'images/lang/'.$code.'.png);" href="'.$param.$code.'" title="'.$name.'"></a></li>';
			}
			$out .= '</ul>';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère le header/footer du site
	*/
	public static function getHeader(){
		global $id;
		
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
		if( defined('USER_PAGE') && USER_PAGE ){
			$GLOBALS['body_class'] .= ' section-'.USER_PAGE;
		}
		if( defined('CURRENT_PLUGIN') && CURRENT_PLUGIN ){
			$GLOBALS['body_class'] .= ' plugin-'.CURRENT_PLUGIN;
		}
		$GLOBALS['body_class'] = trim($GLOBALS['body_class']);
		
		require_once AdminServConfig::PATH_RESOURCES . 'templates/header.tpl.php';
	}
	public static function getFooter(){
		require_once AdminServConfig::PATH_RESOURCES . 'templates/footer.tpl.php';
	}
	
	
	/**
	* Récupère le CSS/JS du site
	*/
	public static function getCss(){
		$path = AdminServConfig::PATH_RESOURCES .'css/';
		$out = '<link rel="stylesheet" href="'.$path.'fileuploader.css" />'."\n\t\t"
		.'<link rel="stylesheet" href="'.$path.'global.css" />'."\n\t\t"
		.'<!--[if IE]><link rel="stylesheet" href="'.$path.'ie.css" /><![endif]-->'."\n\t\t";
		if( defined('USER_THEME') && USER_THEME ){
			$out .= '<link rel="stylesheet" href="'.$path.'jqueryui/'. USER_THEME .'.css" />'."\n\t\t"
			.'<link rel="stylesheet" href="'.$path.'theme.php?th='. USER_THEME .'" />'."\n\t\t";
		}
		$out .= '<link rel="stylesheet" media="screen and (max-width: 1000px) and (min-width: 335px)" href="'.$path.'mobile.css" />'."\n";
		
		return $out;
	}
	public static function getJS(){
		$path = AdminServConfig::PATH_RESOURCES .'js/';
		$out = '<script src="'.$path.'jquery.js"></script>'."\n\t\t"
		.'<script src="'.$path.'jquery-ui.js"></script>'."\n\t\t"
		//.'<script src="'.$path.'jquery.lint.js"></script>'."\n\t\t"
		.'<script src="'.$path.'colorpicker.js"></script>'."\n\t\t"
		.'<script src="'.$path.'fileuploader.js"></script>'."\n\t\t"
		.'<script src="'.$path.'adminserv_funct.js"></script>'."\n\t\t"
		.'<script src="'.$path.'adminserv_event.js"></script>'."\n";
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des serveurs configurés
	*
	* @return string
	*/
	public static function getServerList(){
		$out = null;
		
		if( class_exists('ServerConfig') && AdminServServerConfig::hasServer() ){
			if( isset($_GET['server']) && $_GET['server'] != null ){
				$currentServerId = intval($_GET['server']);
			}
			else{
				$currentServerId = Utils::readCookieData('adminserv', 0);
			}
			
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
		
		if( class_exists('ExtensionConfig') && isset(ExtensionConfig::$GAMEMODES) && count(ExtensionConfig::$GAMEMODES) > 0 ){
			foreach(ExtensionConfig::$GAMEMODES as $gameModeId => $gameModeName){
				if($gameModeId == $currentGameMode){
					$selected = ' selected="selected"';
				}else{
					$selected = null;
				}
				$out .= '<option value="'.$gameModeId.'"'.$selected.'>'.$gameModeName.'</option>';
			}
		}
		else{
			$out = '<option value="null">'.Utils::t('No game mode available').'</option>';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère le formulaire pour un champ
	*
	* @param array  $gameinfos -> Informations de jeu courantes et suivantes
	* @param string $name      -> Le nom du champ affiché dans un label
	* @param string $id        -> L'id du champ du tableau GameInfos
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
				.'<input class="text width2" type="'; if( is_numeric($nextGamInf[$id]) ){ $out .= 'number" min="0"'; }else{ $out .= 'text'; } $out .= '" name="Next'.$id.'" id="Next'.$id.'" value="'.$nextGamInf[$id].'" />'
			.'</td>'
			.'<td class="preview"></td>'
		.'</tr>';
		
		return $out;
	}
	
	
	/**
	* Récupère le formulaire général aux informations de jeu
	*
	* @param array $gameinfos -> Informations de jeu courantes et suivantes
	* @return string HTML
	*/
	public static function getGameInfosGeneralForm($gameinfos){
		if( isset($gameinfos[0]) ){ $currGamInf = $gameinfos[0]; }else{ $currGamInf = null; }
		if( isset($gameinfos[1]) ){ $nextGamInf = $gameinfos[1]; }else{ $nextGamInf = null; }
		
		$out = '<fieldset class="gameinfos_general">'
			.'<legend><img src="'. AdminServConfig::PATH_RESOURCES .'images/16/restartrace.png" alt="" />'.Utils::t('General').'</legend>'
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
							.self::getGameModeList($nextGamInf['GameMode'])
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
						.'<input class="text width2" type="number" min="0" name="NextChatTime" id="NextChatTime" value="'.TimeDate::millisecToSec($nextGamInf['ChatTime'] + 8000).'" />'
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
						.'<input class="text width2" type="number" min="0" name="NextFinishTimeoutValue" id="NextFinishTimeoutValue" value="'; if($nextGamInf['FinishTimeout'] > 1){ $out .= TimeDate::millisecToSec($nextGamInf['FinishTimeout']); } $out .= '"'; if($nextGamInf['FinishTimeout'] < 2){ $out .= ' hidden="hidden"'; } $out .= ' />'
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
	* @param array $gameinfos -> Informations de jeu courantes et suivantes
	* @return string HTML
	*/
	public static function getGameInfosGameModeForm($gameinfos){
		if( isset($gameinfos[0]) ){ $currGamInf = $gameinfos[0]; }else{ $currGamInf = null; }
		if( isset($gameinfos[1]) ){ $nextGamInf = $gameinfos[1]; }else{ $nextGamInf = null; }
		$out = null;
		
		if(SERVER_VERSION_NAME == 'ManiaPlanet'){
			$out .= '<fieldset id="gameMode-script" class="gameinfos_script" hidden="hidden">'
				.'<legend><img src="'. AdminServConfig::PATH_RESOURCES .'images/16/options.png" alt="" />'.AdminServ::getGameModeName(0).'</legend>'
				.'<table class="game_infos">'
					.'<tr>'
						.'<td class="key"><label for="NextScriptName">'.Utils::t('Script name').'</label></td>';
						if($currGamInf != null){
							$out .= '<td class="value">'
								.'<input class="text width2" type="text" name="CurrScriptName" id="CurrScriptName" readonly="readonly" value="'.$currGamInf['ScriptName'].'" />'
							.'</td>';
						}
						$out .= '<td class="value">'
							.'<input class="text width2" type="text" name="NextScriptName" id="NextScriptName" value="'.$nextGamInf['ScriptName'].'" />'
						.'</td>'
						.'<td class="preview">';
							if($nextGamInf['GameMode'] == 0){
								$out .= '<a id="getScriptSettings" href="" data-infotext="'.Utils::t('Script settings updated.').'">'.Utils::t('Script settings').'</a>';
							}
						$out .= '</td>'
					.'</tr>'
				.'</table>'
			.'</fieldset>';
			if($nextGamInf['GameMode'] == 0){
				$out .= '<div id="getScriptSettingsDialog" data-title="'.Utils::t('Script settings').'" data-cancel="'.Utils::t('Cancel').'" data-save="'.Utils::t('Save').'" hidden="hidden">
					<div id="dialogScriptInfo">
						<h2>'.Utils::t('Script info').'</h2>
						<div class="content">
							<table>
								<tbody>
									<tr>
										<td class="key">'.Utils::t('Name').'</td>
										<td class="value" id="dialogScriptInfoName"></td>
									</tr>
									<tr>
										<td class="key">'.Utils::t('Compatible map types').'</td>
										<td class="value" id="dialogScriptInfoCompatibleMapTypes"></td>
									</tr>
									<tr class="dialogScriptInfoDesc" hidden="hidden">
										<td class="key">'.Utils::t('Description').'</td>
										<td class="value" id="dialogScriptInfoDesc"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div id="dialogScriptSettings">
						<h2>'.Utils::t('Script parameters').'</h2>
						<table>
							<thead>
								<tr>
									<th class="thleft">'.Utils::t('Name').'</th>
									<th>'.Utils::t('Value').'</th>
									<th class="thright">'.Utils::t('Description').'</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>';
			}
		}
		
		$out .= '<fieldset id="gameMode-rounds" class="gameinfos_round" hidden="hidden">'
			.'<legend><img src="'. AdminServConfig::PATH_RESOURCES .'images/16/rt_rounds.png" alt="" />'.AdminServ::getGameModeName(1, true).'</legend>'
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
			.'<legend><img src="'. AdminServConfig::PATH_RESOURCES .'images/16/rt_timeattack.png" alt="" />'.AdminServ::getGameModeName(2, true).'</legend>'
			.'<table class="game_infos">'
				.'<tr>'
					.'<td class="key"><label for="NextTimeAttackLimit">'.Utils::t('Time limit').' <span>('.Utils::t('sec').')</span></label></td>';
					if($currGamInf != null){
						$out .= '<td class="value">'
							.'<input class="text width2" type="text" name="CurrTimeAttackLimit" id="CurrTimeAttackLimit" readonly="readonly" value="'.TimeDate::millisecToSec($currGamInf['TimeAttackLimit']).'" />'
						.'</td>';
					}
					$out .= '<td class="value">'
						.'<input class="text width2" type="number" min="0" name="NextTimeAttackLimit" id="NextTimeAttackLimit" value="'.TimeDate::millisecToSec($nextGamInf['TimeAttackLimit']).'" />'
					.'</td>'
					.'<td class="preview"></td>'
				.'</tr>'
				.self::getGameInfosField($gameinfos, 'Start synchronization period', 'TimeAttackSynchStartPeriod')
			.'</table>'
		.'</fieldset>'
		
		.'<fieldset id="gameMode-team" class="gameinfos_team" hidden="hidden">'
			.'<legend><img src="'. AdminServConfig::PATH_RESOURCES .'images/16/rt_team.png" alt="" />'.AdminServ::getGameModeName(3, true).'</legend>'
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
			.'<legend><img src="'. AdminServConfig::PATH_RESOURCES .'images/16/rt_laps.png" alt="" />'.AdminServ::getGameModeName(4, true).'</legend>'
			.'<table class="game_infos">'
				.self::getGameInfosField($gameinfos, 'Number of laps', 'LapsNbLaps')
				.self::getGameInfosField($gameinfos, Utils::t('Time limit').' <span>('.Utils::t('sec').')</span>', 'LapsTimeLimit')
			.'</table>'
		.'</fieldset>'
		
		.'<fieldset id="gameMode-cup" class="gameinfos_cup" hidden="hidden">'
			.'<legend><img src="'. AdminServConfig::PATH_RESOURCES .'images/16/rt_cup.png" alt="" />'.AdminServ::getGameModeName(5, true).'</legend>'
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
		$out = '<option value="null">'.Utils::t('No player available').'</option>';
		
		if( !$client->query('GetPlayerList', AdminServConfig::LIMIT_PLAYERS_LIST, 0, 1) ){
			AdminServ::error();
		}
		else{
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
		
		return $out;
	}
	
	
	/**
	* Retourne le menu pour les pages maps
	*
	* @return html
	*/
	public static function getMapsMenuList(){
		global $directory;
		$out = null;
		$list = ExtensionConfig::$MAPSMENU;
		$excludeLocalPage = array('maps-local', 'maps-matchset', 'maps-creatematchset');
		if( !IS_LOCAL ){
			foreach($excludeLocalPage as $page){
				unset($list[$page]);
			}
		}
		
		if( count($list) > 0 ){
			$out = '<nav class="vertical-nav">'
				.'<ul>';
					foreach($list as $page => $title){
						$out .= '<li><a '; if(USER_PAGE == $page){ $out .= 'class="active" '; } $out .= 'href="?p='.$page; if($directory){ $out .= '&amp;d='.$directory; } $out .= '">'.Utils::t($title).'</a></li>';
					}
			$out .= '</ul>'
			.'</nav>';
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des dossiers du répertoire "Maps"
	*
	* @require class "Folder"
	*
	* @param string $path        -> Le chemin du dossier "Maps"
	* @param string $currentPath -> Le chemin à partir de "Maps"
	* @param bool   $showOptions -> Afficher les options (nouveau, renommer, déplacer, supprimer)
	* @return string
	*/
	public static function getMapsDirectoryList($directory, $currentPath = null, $showOptions = true){
		$out = null;
		
		if( class_exists('Folder') ){
			// Titre + nouveau dossier
			$out .= '<form id="createFolderForm" method="post" action="?p='. USER_PAGE .'&amp;d='.$currentPath.'">'
				.'<h1>Dossiers';
					if($showOptions && isset(AdminServConfig::$FOLDERS_OPTIONS) && isset(AdminServConfig::$FOLDERS_OPTIONS['new']) && AdminServConfig::$FOLDERS_OPTIONS['new'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['new'][1]) ){
						$out .='<span id="form-new-folder" hidden="hidden">'
							.'<input class="text" type="text" name="newFolderName" id="newFolderName" value="" />'
							.'<input class="button light" type="submit" name="newFolderValid" id="newFolderValid" value="ok" />'
						.'</span>';
					}
				$out .= '</h1>';
				if($showOptions && isset(AdminServConfig::$FOLDERS_OPTIONS) && isset(AdminServConfig::$FOLDERS_OPTIONS['new']) && AdminServConfig::$FOLDERS_OPTIONS['new'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['new'][1]) ){
					$out .= '<div class="title-detail"><a href="." id="newfolder" data-cancel="'.Utils::t('Cancel').'" data-new="'.Utils::t('New').'">'.Utils::t('New').'</a></div>';
				}
			$out .= '</form>';
			
			// Liste des dossiers
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
							.'<img src="'. AdminServConfig::PATH_RESOURCES .'images/16/back.png" alt="" />'
							.'<span class="dir-name">'.Utils::t('Parent folder').'</span>'
						.'</a>'
					.'</li>';
				}
				
				// Dossiers
				if( count($directory['folders']) > 0 ){
					foreach($directory['folders'] as $dir => $values){
						$out .= '<li>'
							.'<a href="./?p='. USER_PAGE .'&amp;d='.urlencode($currentPath.$dir).'/">'
								.'<span class="dir-name">'.$dir.'</span>'
								.'<span class="dir-info">'.$values['nb_file'].'</span>'
							.'</a>'
						.'</li>';
					}
				}
				else{
					$out .= '<li class="no-result">'.Utils::t('No folder').'</li>';
				}
				$out .= '</ul>'
				.'</div>';
			}
			else{
				AdminServ::error($directory);
			}
			
			// Options de dossier
			if($showOptions && $currentPath && isset(AdminServConfig::$FOLDERS_OPTIONS) ){
				if( (isset(AdminServConfig::$FOLDERS_OPTIONS['rename']) && AdminServConfig::$FOLDERS_OPTIONS['rename'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['rename'][1])) || (isset(AdminServConfig::$FOLDERS_OPTIONS['move']) && AdminServConfig::$FOLDERS_OPTIONS['move'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['move'][1])) || (isset(AdminServConfig::$FOLDERS_OPTIONS['delete']) && AdminServConfig::$FOLDERS_OPTIONS['delete'][0] && AdminServ::isAdminLevel(AdminServConfig::$FOLDERS_OPTIONS['delete'][1])) ){
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
									$out .= '<li><a class="button light delete" id="deleteFolder" href="." data-confirm-text="'.Utils::t('Do you really want to remove this folder !currentDir?', array('!currentDir' => $currentDir)).'">'.Utils::t('Delete').'</a></li>';
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
		$pathRessources = AdminServConfig::PATH_RESOURCES;
		
		if( is_array($list) && count($list) > 0 ){
			foreach($list['lst'] as $id => $map){
				$out .= '<li class="ui-state-default">'
					.'<div class="ui-icon ui-icon-arrowthick-2-n-s"></div>'
					.'<div class="order-map-name" title="'.$map['FileName'].'">'.$map['Name'].'</div>'
					.'<div class="order-map-env"><img src="'.$pathRessources.'images/env/'.strtolower($map['Environnement']).'.png" alt="" />'.$map['Environnement'].'</div>'
					.'<div class="order-map-author"><img src="'.$pathRessources.'images/16/mapauthor.png" alt="" />'.$map['Author'].'</div>'
				.'</li>';
			}
		}
		
		return $out;
	}
}
?>