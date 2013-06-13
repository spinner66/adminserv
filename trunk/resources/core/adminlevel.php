<?php

/**
* Classe pour la gestion des niveaux admins
*/
class AdminServAdminLevel {
	
	
	public static function hasLevel(){
		$out = false;
		
		if( class_exists('AdminLevelConfig') ){
			$adminLevelList = AdminLevelConfig::$ADMINLEVELS;
			
			if( isset($adminLevelList) && count($adminLevelList) > 0 ){
				$out = true;
			}
		}
		
		return $out;
	}
	
	
	public static function hasPermission($permissionName){
		$out = false;
		$level = self::getUserLevelData('permission');
		
		if( !empty($level) && isset($level[$permissionName]) && $level[$permissionName] === true ){
			$out = true;
		}
		
		return $out;
	}
	
	
	public static function getUserLevelData($data = 'all'){
		$out = array();
		
		if( self::hasLevel() ){
			$userLevelData = AdminLevelConfig::$ADMINLEVELS[USER_ADMINLEVEL];
			
			if($data != 'all'){
				if( isset($userLevelData[$data]) ){
					$out = $userLevelData[$data];
				}
			}
			else{
				$out = $userLevelData;
			}
		}
		
		return $out;
	}
}


/**
* Classe pour la gestion des niveaux admin
*/
class AdminServAdminLevelConfig {
	
	/**
	* Globales
	*/
	private static $DEFAULT_ADMINLEVEL = array(
		'SuperAdmin',
		'Admin',
		'User'
	);
	private static $CONFIG_PATH = './config/';
	private static $CONFIG_FILENAME = 'adminlevel.cfg.php';
	private static $CONFIG_START_TEMPLATE = "<?php\nclass AdminLevelConfig {\n\tpublic static \$ADMINLEVELS = array(\n\t\t/********************* ADMINLEVEL CONFIGURATION *********************/\n\t\t\n";
	private static $CONFIG_END_TEMPLATE =  "\t);\n}\n?>";
	
	
	/**
	* Détermine s'il y a au moins un niveau admin disponible
	*
	* @return bool
	*/
	public static function hasAdminLevel(){
		$out = false;
		
		if( file_exists(self::$CONFIG_PATH.self::$CONFIG_FILENAME) && class_exists('AdminLevelConfig') ){
			if( isset(AdminLevelConfig::$ADMINLEVELS) && count(AdminLevelConfig::$ADMINLEVELS) > 0 ){
				$out = true;
			}
		}
		
		return $out;
	}
	
	/**
	* Récupère la liste des niveaux configurés
	*
	* @return array
	*/
	public static function getList(){
		$out = array();
		
		if( self::hasAdminLevel() ){
			foreach(AdminLevelConfig::$ADMINLEVELS as $admLvlId => $admLvlValue){
				$out[] = $admLvlId;
			}
		}
		else{
			$out = self::$DEFAULT_ADMINLEVEL;
		}
		
		return $out;
	}
}
?>