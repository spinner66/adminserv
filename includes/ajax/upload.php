<?php	// INCLUDES	session_start();	$pathConfig = '../../'.$_SESSION['adminserv']['path'].'config/';	require_once $pathConfig.'adminserv.cfg.php';	require_once $pathConfig.'servers.cfg.php';	require_once '../adminserv.inc.php';	AdminServUI::getClass();		// ISSET	if( isset($_GET['path']) ){ $path = $_GET['path']; }else{ $path = null; }	if( substr($path, -1, 1) != '/'){ $path = $path.'/'; }	if( isset($_GET['gtlm']) ){ $gtlm = $_GET['gtlm']; }else{ $gtlm = null; }	if( isset($_GET['mset']) ){ $mset = $_GET['mset']; }else{ $mset = null; }	if( isset($_GET['type']) ){ $type = $_GET['type']; }else{ $type = null; }		// DATA	if($path != null){		if( AdminServ::initialize() ){			// Fonction de rappel pour le remplacement des caractères spéciaux			function replaceFilename($str){				return Str::replaceChars($str);			}						// Jeu			if(SERVER_VERSION_NAME == 'TmForever'){				$queries = array(					'insert' => 'InsertChallenge',					'add' => 'AddChallenge',					'type' => $type				);			}			else{				$queries = array(					'insert' => 'InsertMap',					'add' => 'AddMap',					'type' => $type				);			}						// Taille max			if(AdminServConfig::SIZE_LIMIT == 'auto'){				$sizeLimit = (intval(ini_get('post_max_size')) * 1024);			}			else{				$sizeLimit = (AdminServConfig::SIZE_LIMIT * 1024 * 1024);			}						// Enregistrement du fichier			$result = FileUploader::saveUploadedFileToManiaPlanetServer($client, $path, $queries, AdminServConfig::$ALLOWED_EXTENSIONS, $sizeLimit, 'replaceFilename');									// Sauvegarde du MatchSettings			if($mset && SERVER_MATCHSET){				if( !$client->query('SaveMatchSettings', AdminServ::getMapsDirectoryPath().SERVER_MATCHSET) ){					$result['error'] = '['.$client->getErrorCode().'] '.$client->getErrorMessage();				}			}		}		$client->Terminate();		echo $result;	}?>