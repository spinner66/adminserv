<?php	// LOCAL MODE	$localPages = array(		'maps-local',		'maps-upload',		'maps-matchset',		'maps-creatematchset'	);	$mapsDirectoryPath = AdminServ::getMapsDirectoryPath();	if( file_exists($mapsDirectoryPath) ){		define('IS_LOCAL', true);	}	else{		define('IS_LOCAL', false);	}		// LECTURE	if( IS_LOCAL && in_array(USER_PAGE, $localPages) ){		if( Utils::isWinServer() ){			$checkRightsList[$mapsDirectoryPath] = 666;			$checkRightsList[AdminServConfig::PATH_INCLUDES . 'cache'] = 666;		}		else{			$checkRightsList[$mapsDirectoryPath] = 755;			$checkRightsList[AdminServConfig::PATH_INCLUDES . 'cache'] = 755;		}		AdminServ::checkRights($checkRightsList);				// Read current directory		$matchsettingsPages = in_array(USER_PAGE, array('maps-matchset', 'maps-creatematchset'));		$hiddenFolders = ($matchsettingsPages) ? AdminServConfig::$MATCHSET_HIDDEN_FOLDERS : AdminServConfig::$MAPS_HIDDEN_FOLDERS;		$hiddenFiles = ($matchsettingsPages) ? AdminServConfig::$MAP_EXTENSION : AdminServConfig::$MATCHSET_EXTENSION;		$currentDir = Folder::read($mapsDirectoryPath.$directory, $hiddenFolders, $hiddenFiles, intval(AdminServConfig::RECENT_STATUS_PERIOD * 3600) );		$mapsDirectoryList = AdminServUI::getMapsDirectoryList($currentDir, $directory);	}	else{		$notLocalPages = array(			'maps-local',			'maps-matchset',			'maps-creatematchset'		);		if( in_array(USER_PAGE, $notLocalPages) ){			Utils::redirection(false, '?p=maps-list');		}	}		// ACTIONS	$hasDirectory = null;	if($directory){		$hasDirectory = '&d='.$directory;	}		// Nouveau dossier	if( isset($_POST['newFolderValid']) && $_POST['newFolderName'] != null || isset($_POST['newFolderName']) ){		if( Folder::create($mapsDirectoryPath.$directory.Str::replaceChars($_POST['newFolderName'])) !== true ){			AdminServ::error(Utils::t('Unable to create the folder').' : '.$_POST['newFolderName']);		}		else{			AdminServLogs::add('action', 'Create new folder: '.$_POST['newFolderName']);			Utils::redirection(false, '?p='. USER_PAGE .$hasDirectory);		}	}	// Renommer un dossier	else if( isset($_POST['optionFolderHiddenFieldAction']) && $_POST['optionFolderHiddenFieldAction'] == 'rename'){		$newDirectory = addslashes($_POST['optionFolderHiddenFieldValue']);				if( ($result = Folder::rename($mapsDirectoryPath.$directory, $mapsDirectoryPath.$newDirectory)) !== true ){			AdminServ::error(Utils::t('Unable to rename the folder').' : '.$directory.' ('.$result.')');		}		else{			AdminServLogs::add('action', 'Rename folder: '.$directory.' to '.$newDirectory);			Utils::redirection(false, '?p='. USER_PAGE .'&d='.$newDirectory.'/');		}	}	// Déplacer un dossier	else if( isset($_POST['optionFolderHiddenFieldAction']) && $_POST['optionFolderHiddenFieldAction'] == 'move'){		$newPath = addslashes($_POST['optionFolderHiddenFieldValue']);		if($newPath == '.'){			$newPath = $mapsDirectoryPath;		}		$newPath .= basename($directory).'/';		$newPathFromMapsPath = str_replace($mapsDirectoryPath, '', $newPath);		if($newPathFromMapsPath){			$newPathFromMapsPath = '&d='.$newPathFromMapsPath;		}				if( ($result = Folder::rename($mapsDirectoryPath.$directory, $newPath)) !== true ){			AdminServ::error(Utils::t('Unable to move the folder').' : '.$directory.' ('.$result.')');		}		else{			AdminServLogs::add('action', 'Move folder: '.$directory.' to '.$newPathFromMapsPath);			Utils::redirection(false, '?p='. USER_PAGE .$newPathFromMapsPath);		}	}	// Supprimer un dossier	else if( isset($_POST['optionFolderHiddenFieldAction']) && $_POST['optionFolderHiddenFieldAction'] == 'delete'){		if( ($result = Folder::delete($mapsDirectoryPath.$directory)) !== true ){			AdminServ::error(Utils::t('Unable to delete the folder').' : '.$directory.' ('.$result.')');		}		else{			AdminServLogs::add('action', 'Delete folder: '.$directory);			Utils::redirection(false, '?p='. USER_PAGE);		}	}?>