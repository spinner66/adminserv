<?php

/**
* Classe pour la gestion des niveaux admins
*/
class AdminServAdminLevel {
	
	/**
	* Vérifie s'il y a bien une config de niveaux admins
	*
	* @param string $levelName -> Tester si le niveau admin est présent
	* @return bool
	*/
	public static function hasLevel($level = null) {
		$out = false;
		
		if (class_exists('AdminLevelConfig')) {
			$adminLevelList = AdminLevelConfig::$ADMINLEVELS;
			
			if (isset($adminLevelList) && !empty($adminLevelList)) {
				if ($level) {
					foreach ($adminLevelList as $adminLevelName => $adminLevelData) {
						if ($adminLevelName === $level) {
							$out = true;
							break;
						}
					}
				}
				else {
					$out = true;
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Vérifie s'il le niveau admin a bien l'accès à la page
	*
	* @param string $access -> Nom de l'accès
	* @param string $level  -> Nom du niveau admin
	* @return bool
	*/
	public static function hasAccess($access, $level = USER_ADMINLEVEL){
		$out = false;
		
		if ($access == 'general') {
			$out = true;
		}
		else {
			$pageToAccess = array(
				'srvopts' => 'server_options',
				'gameinfos' => 'game_infos',
				'chat' => 'chat',
				'maps-list' => 'maps_list',
				'maps-local' => 'maps_local',
				'maps-upload' => 'maps_upload',
				'maps-order' => 'maps_order',
				'maps-matchset' => 'maps_matchsettings',
				'maps-creatematchset' => 'maps_create_matchsettings',
				'plugins-list' => 'plugins_list',
				'guestban' => 'guest_ban',
			);
			if (array_key_exists($access, $pageToAccess)) {
				$access = $pageToAccess[$access];
			}
			$levelData = self::getLevelData('access', $level);
			
			if (!empty($levelData) && isset($levelData[$access]) && $levelData[$access] === true) {
				$out = true;
			}
		}
		
		return $out;
	}
	
	
	/**
	* Vérifie s'il le niveau admin a bien la permission d'utiliser une fonctionnalité
	*
	* @param string $permission -> Nom de la permission
	* @param string $level      -> Nom du niveau admin
	* @return bool
	*/
	public static function hasPermission($permission, $level = USER_ADMINLEVEL){
		$out = false;
		$levelData = self::getLevelData('permission');
		
		if (!empty($levelData) && isset($levelData[$permission]) && $levelData[$permission] === true) {
			$out = true;
		}
		
		return $out;
	}
	
	
	/**
	* Récupère les données de configuration pour un niveau admin
	*
	* @param string $data  -> Champ de données à retourner
	* @param string $level -> Nom du niveau admin
	* @return array
	*/
	public static function getLevelData($data = 'all', $level = USER_ADMINLEVEL){
		$out = array();
		
		if (self::hasLevel($level)) {
			$levelData = AdminLevelConfig::$ADMINLEVELS[$level];
			
			if ($data != 'all') {
				if (isset($levelData[$data])) {
					$out = $levelData[$data];
				}
			}
			else {
				$out = $levelData;
			}
		}
		
		return $out;
	}
	
	
	/**
	* Vérifie si l'ip de l'utilisateur est autorisé dans le niveau admin
	*
	* @param string $level -> Nom du niveau admin
	* @return bool
	*/
	public static function userAllowedInLevel($level, $server = null){
		$out = false;
		
		$serverLevels = ($server === null && defined('SERVER_ADMINLEVEL')) ? unserialize(SERVER_ADMINLEVEL) : ServerConfig::$SERVERS[$server]['adminlevel'];
		$serverLevel = $serverLevels[$level];
		
		if (is_array($serverLevel)) {
			if (in_array($_SERVER['REMOTE_ADDR'], $serverLevel)) {
				$out = true;
			}
		}
		else {
			if ($serverLevel === 'all') {
				$out = true;
			}
			elseif ($serverLevel === 'none') {
				$out = false;
			}
			else {
				$out = Utils::isLocalhostIP();
			}
		}
		
		return $out;
	}
}
?>