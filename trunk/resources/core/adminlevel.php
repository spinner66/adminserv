<?php

/**
* Classe pour la gestion des niveaux admins
*/
class AdminServAdminLevel {
	
	/**
	* Constantes
	*/
	public static $DEFAULT_LEVELS = array(
		'SuperAdmin' => array('SuperAdmin', 'Admin', 'User'),
		'Admin' => array('Admin', 'User'),
		'User' => array('User'),
	);
	private static $CONFIG_PATH = './config/';
	private static $CONFIG_FILENAME = 'adminlevel.cfg.php';
	private static $CONFIG_START_TEMPLATE = "<?php\nclass AdminLevelConfig {\n\tpublic static \$ADMINLEVELS = array(\n\t\t/********************* ADMINLEVELS CONFIGURATION *********************/\n\t\t\n";
	private static $CONFIG_END_TEMPLATE =  "\t);\n}\n?>";
	
	
	/**
	* Vérifie s'il y a bien une config de niveaux admins
	*
	* @param string $levelName -> Tester si le niveau admin est présent
	* @return bool
	*/
	public static function hasLevel($levelName = null) {
		$out = false;
		
		if (class_exists('AdminLevelConfig')) {
			$adminLevelList = AdminLevelConfig::$ADMINLEVELS;
			
			if (isset($adminLevelList) && !empty($adminLevelList)) {
				if ($levelName) {
					foreach ($adminLevelList as $adminLevelName => $adminLevelData) {
						if ($adminLevelName === $levelName) {
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
	* @param string $access    -> Nom de l'accès
	* @param string $levelName -> Nom du niveau admin
	* @return bool
	*/
	public static function hasAccess($access, $levelName = null) {
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
		if ($levelName === null && defined('USER_ADMINLEVEL')) {
			$levelName = USER_ADMINLEVEL;
		}
		$levelData = self::getData($levelName, 'access');
		
		if (!empty($levelData) && isset($levelData[$access]) && $levelData[$access] === true) {
			$out = true;
		}
		
		return $out;
	}
	
	
	/**
	* Vérifie s'il le niveau admin a bien la permission d'utiliser une fonctionnalité
	*
	* @param mixed  $permission -> Nom de la permission ou tableau de plusieurs permissions
	* @param string $levelName  -> Nom du niveau admin
	* @param string $levelType  -> Type de niveau minimum à autoriser
	* @return bool
	*/
	public static function hasPermission($permission, $levelName = null, $levelType = null) {
		$out = false;
		if ($levelName === null && defined('USER_ADMINLEVEL')) {
			$levelName = USER_ADMINLEVEL;
		}
		
		if ($levelName) {
			if ($levelType !== null) {
				$levelData = self::getData($levelName, 'adminlevel');
				$levelTypeAuthorized = self::getDefaultType($levelData['type']);
				if (!in_array($levelType, $levelTypeAuthorized)) {
					return false;
				}
			}
			$levelData = self::getData($levelName, 'permission');
			
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
		}
		
		return $out;
	}
	
	
	/**
	* Récupère les niveaux par défaut ou renvoie les niveaux autorisés suivant le type
	*
	* @param string $type -> Type de niveau
	* @return array
	*/
	public static function getDefaultType($levelType = null){
		$out = array();
		
		if ($levelType === null) {
			$out = array_keys(self::$DEFAULT_LEVELS);
		}
		else {
			foreach (self::$DEFAULT_LEVELS as $defaultTypeLevel => $defaultAuthorizedLevel) {
				if ($levelType === $defaultTypeLevel) {
					$out = $defaultAuthorizedLevel;
					break;
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Récupère les données de configuration pour un niveau admin
	*
	* @param string $levelName -> Nom du niveau admin
	* @param string $fieldName -> Nom du champ de données à retourner
	* @return array
	*/
	public static function getData($levelName = null, $fieldName = 'all') {
		$out = array();
		if ($levelName === null && defined('USER_ADMINLEVEL')) {
			$levelName = USER_ADMINLEVEL;
		}
		
		if (self::hasLevel($levelName)) {
			$levelData = AdminLevelConfig::$ADMINLEVELS[$levelName];
			
			if ($fieldName != 'all') {
				if (isset($levelData[$fieldName])) {
					$out = $levelData[$fieldName];
				}
			}
			else {
				$out = $levelData;
			}
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la position du niveau admin dans le fichier de config
	*
	* @param string $levelName -> Nom du niveau admin
	* @return string
	*/
	public static function getId($levelName = null) {
		$id = 0;
		$levelList = self::getStaticList();
		if ($levelName === null && defined('USER_ADMINLEVEL')) {
			$levelName = USER_ADMINLEVEL;
		}
		
		// On cherche la position du niveau à partir de son nom
		if (!empty($levelList)) {
			foreach ($levelList as $levelListName) {
				if ($levelListName == $levelName) {
					break;
				}
				else {
					$id++;
				}
			}
		}
		
		// Si l'id = le nb total de levels -> pas trouvé
		return ($id == count($levelList)) ? -1 : $id;
	}
	
	
	/**
	* Récupère le type à partir du nom
	*
	* @param string $levelName -> Nom du niveau admin
	* @return string
	*/
	public static function getType($levelName = null) {
		$out = null;
		if ($levelName === null && defined('USER_ADMINLEVEL')) {
			$levelName = USER_ADMINLEVEL;
		}
		
		$levelData = self::getData($levelName, 'adminlevel');
		if (isset($levelData['type']) && $levelData['type'] != null) {
			$out = $levelData['type'];
		}
		
		return $out;
	}
	
	
	/**
	* Récupère le nom à partir du type
	*
	* @param string $levelType -> Type du niveau admin
	* @return string
	*/
	public static function getName($levelType) {
		$out = null;
		
		if (self::hasLevel()) {
			foreach (AdminLevelConfig::$ADMINLEVELS as $levelName => $levelData) {
				if (self::getType($levelName) === $levelType) {
					$out = $levelName;
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Détermine si le niveau utilisateur est au minimum de type $levelType
	*
	* @param string $levelType -> Type de niveau admin minimum
	* @return bool
	*/
	public static function isType($levelType, $levelName = null) {
		$out = false;
		if ($levelName === null && defined('USER_ADMINLEVEL')) {
			$levelName = USER_ADMINLEVEL;
		}
		
		$levelData = self::getData($levelName, 'adminlevel');
		$levelTypeAuthorized = self::getDefaultType($levelData['type']);
		if (in_array($levelType, $levelTypeAuthorized)) {
			$out = true;
		}
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des niveaux admins configurés pour le serveur courant
	*
	* @param string $server -> Nom du serveur
	* @return array
	*/
	public static function getServerList($serverName = null) {
		$out = array();
		if ($serverName === null && defined('SERVER_NAME')) {
			$serverName = SERVER_NAME;
		}
		
		if (AdminServServerConfig::hasServer($serverName)) {
			$levels = self::getStaticList();
			if (!empty($levels)) {
				foreach ($levels as $levelName) {
					$levelType = self::getType($levelName);
					foreach (ServerConfig::$SERVERS[$serverName]['adminlevel'] as $serverLevelType => $serverLevelTypeAccess) {
						if ($levelType === $serverLevelType) {
							if ($serverLevelType != null && $serverLevelTypeAccess != 'none') {
								if (self::userAllowed($levelName, $serverName)) {
									$out['levels'][] = $levelName;
								}
							}
						}
					}
				}
			}
		}
		
		$out['last'] = Utils::readCookieData('adminserv', 1);
		
		return $out;
	}
	
	
	/**
	* Récupère la liste des niveaux admins configurés
	*/
	public static function getStaticList(){
		$out = array();
		
		if (self::hasLevel()) {
			foreach (AdminLevelConfig::$ADMINLEVELS as $levelName => $levelData) {
				$out[] = $levelName;
			}
		}
		
		return $out;
	}
	
	
	/**
	* Vérifie si l'ip de l'utilisateur est autorisé dans le niveau admin
	*
	* @param string $level  -> Nom du niveau admin
	* @param string $server -> Nom du serveur
	* @return bool
	*/
	public static function userAllowed($levelName, $serverName = null) {
		$out = false;
		if ($serverName === null && defined('SERVER_NAME')) {
			$serverName = SERVER_NAME;
		}
		
		if (AdminServServerConfig::hasServer($serverName)){
			$levelType = self::getType($levelName);
			if ($levelType) {
				$serverLevelTypeAccess = ServerConfig::$SERVERS[$serverName]['adminlevel'][$levelType];
				
				if (is_array($serverLevelTypeAccess)) {
					if (in_array($_SERVER['REMOTE_ADDR'], $serverLevelTypeAccess)) {
						$out = true;
					}
				}
				else {
					if ($serverLevelTypeAccess === 'all') {
						$out = true;
					}
					elseif ($serverLevelTypeAccess === 'none') {
						$out = false;
					}
					else {
						$out = Utils::isLocalhostIP();
					}
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	* Créer le template d'un niveau admin
	*
	* @param array $levelData -> assoc array(adminlevel => array(type), access, permission)
	*/
	public static function getTemplate($levelData){
		$out = "\t\t'".$levelData['name']."' => array(\n"
			."\t\t\t'adminlevel' => array(\n";
				foreach ($levelData['adminlevel'] as $adminlevelName => $adminlevelData) {
					$out .= "\t\t\t\t'$adminlevelName' => '$adminlevelData',\n";
				}
			$out .= "\t\t\t),\n"
			."\t\t\t'access' => array(\n";
				foreach ($levelData['access'] as $accessName => $accessData) {
					$out .= "\t\t\t\t'$accessName' => ".var_export($accessData, true).",\n";
				}
			$out .= "\t\t\t),\n"
			."\t\t\t'permission' => array(\n";
				foreach ($levelData['permission'] as $permissionName => $permissionData) {
					$out .= "\t\t\t\t'$permissionName' => ".var_export($permissionData, true).",\n";
				}
			$out .= "\t\t\t),\n"
		."\t\t),\n";
		
		return $out;
	}
	
	
	/**
	* Sauvegarde le fichier de configuration des niveaux admins
	*
	* @param array $levelData -> assoc array(adminlevel => array(type), access, permission)
	* @param int   $editLevel -> Id du niveau à éditer
	* @param array $levelList -> Liste des niveaux de la config
	* @return bool or string error
	*/
	public static function saveConfig($levelData = array(), $editLevel = -1, $levelList = array()) {
		// Liste des niveaux
		$levels = (isset($levelList) && !empty($levelList)) ? $levelList : AdminLevelConfig::$ADMINLEVELS;
		
		// Template
		$fileTemplate = self::$CONFIG_START_TEMPLATE;
		$i = 0;
		foreach ($levels as $levelName => $levelValues) {
			// Édition
			if ($i == $editLevel && isset($levelData) && !empty($levelData)) {
				$fileTemplate .= self::getTemplate($levelData);
			}
			else {
				// Récupération des données des niveaux existant
				$getLevelsData = array(
					'name' => $levelName,
					'adminlevel' => $levelValues['adminlevel'],
					'access' => $levelValues['access'],
					'permission' => $levelValues['permission'],
				);
				
				// Ajout des données au template
				$fileTemplate .= self::getTemplate($getLevelsData);
			}
			$i++;
		}
		
		// Ajout d'un nouveau
		if ($editLevel === -1 && isset($levelData) && !empty($levelData)) {
			if (self::getId($levelData['name']) === -1) {
				$fileTemplate .= self::getTemplate($levelData);
			}
			else {
				return Utils::t('The admin level already exist! Change the name.');
			}
		}
		$fileTemplate .= self::$CONFIG_END_TEMPLATE;
		
		// Enregistrement
		return File::save(self::$CONFIG_PATH.self::$CONFIG_FILENAME, $fileTemplate, false);
	}
}
?>