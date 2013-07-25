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
	public static function hasAccess($access, $level = null){
		$out = false;
		
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
		if ($level === null && defined('USER_ADMINLEVEL')) {
			$level = USER_ADMINLEVEL;
		}
		$levelData = self::getLevelData('access', $level);
		
		if (!empty($levelData) && isset($levelData[$access]) && $levelData[$access] === true) {
			$out = true;
		}
		
		return $out;
	}
	
	
	/**
	* Vérifie s'il le niveau admin a bien la permission d'utiliser une fonctionnalité
	*
	* @param mixed  $permission     -> Nom de la permission ou tableau de plusieurs permissions
	* @param string $level          -> Nom du niveau admin
	* @param bool   $onlySuperAdmin -> Autoriser l'accès qu'au niveau SuperAdmin
	* @return bool
	*/
	public static function hasPermission($permission, $level = null, $onlySuperAdmin = false){
		$out = false;
		if ($level === null && defined('USER_ADMINLEVEL')) {
			$level = USER_ADMINLEVEL;
		}
		$levelData = self::getLevelData('permission', $level);
		
		if (!empty($levelData)) {
			if (is_array($permission)) {
				$result = array();
				foreach ($permission as $perm) {
					if (isset($levelData[$perm]) && $levelData[$perm] === true) {
						$result[] = true;
					}
					else {
						$result[] = false;
					}
				}
				if (in_array(true, $result)) {
					$out = true;
				}
			}
			else {
				if (isset($levelData[$permission]) && $levelData[$permission] === true) {
					$out = true;
				}
			}
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
	public static function getLevelData($data = 'all', $level = null){
		$out = array();
		if ($level === null && defined('USER_ADMINLEVEL')) {
			$level = USER_ADMINLEVEL;
		}
		
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