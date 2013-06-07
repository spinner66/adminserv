<?php	// INCLUDES	session_start();	if( !isset($_SESSION['adminserv']['sid']) ){ exit; }	if( isset($_SESSION['adminserv']['path']) ){ $adminservPath = $_SESSION['adminserv']['path']; }	else{ $adminservPath = null; }	$pathConfig = '../../'.$adminservPath.'config/';	require_once $pathConfig.'adminserv.cfg.php';	require_once $pathConfig.'extension.cfg.php';	require_once $pathConfig.'servers.cfg.php';	require_once '../adminserv.inc.php';	AdminServUI::getClass();	$langCode = Utils::getLang();	$langFile = '../lang/'.$langCode.'.php';	if( file_exists($langFile) ){		require_once $langFile;	}		// ISSET	if( isset($_GET['path']) ){ $path = $_GET['path']; }else{ $path = null; }	if( isset($_GET['gtlm']) ){ $gtlm = $_GET['gtlm']; }else{ $gtlm = null; }	if( isset($_GET['mset']) ){ $mset = $_GET['mset']; }else{ $mset = null; }	if( isset($_GET['type']) ){ $type = $_GET['type']; }else{ $type = null; }		// DATA	if( AdminServ::initialize() ){		// Path		$mapDirectoryPath = AdminServ::getMapsDirectoryPath();		if($path === null){			$path = $mapDirectoryPath;		}		if( substr($path, -1, 1) != '/'){			$path = $path.'/';		}		if( !file_exists($path) ){			$path = null;			if( defined('AdminServConfig::UPLOAD_ONLINE_FOLDER') && AdminServConfig::UPLOAD_ONLINE_FOLDER != null ){				$path = AdminServConfig::UPLOAD_ONLINE_FOLDER;			}		}				// Fonction de rappel pour le remplacement des caractères spéciaux		function replaceFilename($str){			return Str::replaceChars($str);		}				// Jeu		if(SERVER_VERSION_NAME == 'TmForever'){			$queries = array(				'insert' => 'InsertChallenge',				'add' => 'AddChallenge',				'type' => $type			);		}		else{			$queries = array(				'insert' => 'InsertMap',				'add' => 'AddMap',				'type' => $type			);		}				// Taille max		if(AdminServConfig::SIZE_LIMIT == 'auto'){			$sizeLimit1 = (intval(ini_get('post_max_size')) * 1024 * 1024);			$sizeLimit2 = (intval(ini_get('upload_max_filesize')) * 1024 * 1024);			$sizeLimit = min($sizeLimit1, $sizeLimit2);		}		else{			$sizeLimit = (intval(AdminServConfig::SIZE_LIMIT) * 1024 * 1024);		}				// Enregistrement du fichier		$result = FileUploader::saveUploadedFileToManiaPlanetServer($client, $path, $queries, AdminServConfig::$ALLOWED_EXTENSIONS, $sizeLimit, 'replaceFilename');				// Sauvegarde du MatchSettings		if($mset && SERVER_MATCHSET && $type != 'local' && isset($result['success']) && $result['success'] == true){			if( !$client->query('SaveMatchSettings', $mapDirectoryPath.SERVER_MATCHSET) ){				$result['error'] = '['.$client->getErrorCode().'] '.$client->getErrorMessage();			}		}	}	$client->Terminate();	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);?>