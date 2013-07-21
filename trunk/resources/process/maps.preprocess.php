<?php	// LOCAL MODE	$localPages = array(		'maps-local',		'maps-upload',		'maps-matchset',		'maps-creatematchset'	);	$data['mapsDirectoryPath'] = AdminServ::getMapsDirectoryPath();	define('IS_LOCAL', file_exists($data['mapsDirectoryPath']));		// LECTURE	if( IS_LOCAL && in_array(USER_PAGE, $localPages) ){		if( !Utils::isWinServer() ){			$checkRightsList[$data['mapsDirectoryPath']] = 755;			$checkRightsList[AdminServConfig::$PATH_RESOURCES . 'cache'] = 777;			AdminServ::checkRights($checkRightsList);		}				// Read current directory		$matchsettingsPages = in_array(USER_PAGE, array('maps-matchset', 'maps-creatematchset'));		$hiddenFolders = ($matchsettingsPages) ? AdminServConfig::$MATCHSET_HIDDEN_FOLDERS : AdminServConfig::$MAPS_HIDDEN_FOLDERS;		$hiddenFiles = ($matchsettingsPages) ? AdminServConfig::$MAP_EXTENSION : AdminServConfig::$MATCHSET_EXTENSION;		$currentDir = Folder::read($data['mapsDirectoryPath'].$directory, $hiddenFolders, $hiddenFiles, intval(AdminServConfig::RECENT_STATUS_PERIOD * 3600) );		$data['mapsDirectoryList'] = AdminServUI::getMapsDirectoryList($currentDir, $directory);	}	else{		$notLocalPages = array(			'maps-local',			'maps-matchset',			'maps-creatematchset'		);		if( in_array(USER_PAGE, $notLocalPages) ){			Utils::redirection(false, '?p=maps-list');		}	}		// ACTIONS	$data['hasDirectory'] = null;	if($directory){		$data['hasDirectory'] = '&d='.$directory;	}		// Nouveau dossier	if( isset($_POST['newFolderValid']) && $_POST['newFolderName'] != null || isset($_POST['newFolderName']) ){		if( Folder::create($data['mapsDirectoryPath'].$directory.Str::replaceChars($_POST['newFolderName'])) !== true ){			AdminServ::error(Utils::t('Unable to create the folder').' : '.$_POST['newFolderName']);		}		else{			AdminServLogs::add('action', 'Create new folder: '.$_POST['newFolderName']);			Utils::redirection(false, '?p='.USER_PAGE .$data['hasDirectory']);		}	}	// Renommer un dossier	else if( isset($_POST['optionFolderHiddenFieldAction']) && $_POST['optionFolderHiddenFieldAction'] == 'rename'){		$newDirectory = addslashes($_POST['optionFolderHiddenFieldValue']);				if( ($result = Folder::rename($data['mapsDirectoryPath'].$directory, $data['mapsDirectoryPath'].$newDirectory)) !== true ){			AdminServ::error(Utils::t('Unable to rename the folder').' : '.$directory.' ('.$result.')');		}		else{			AdminServLogs::add('action', 'Rename folder: '.$directory.' to '.$newDirectory);			Utils::redirection(false, '?p='.USER_PAGE .'&d='.$newDirectory.'/');		}	}	// Déplacer un dossier	else if( isset($_POST['optionFolderHiddenFieldAction']) && $_POST['optionFolderHiddenFieldAction'] == 'move'){		$newPath = addslashes($_POST['optionFolderHiddenFieldValue']);		if($newPath == '.'){			$newPath = $data['mapsDirectoryPath'];		}		$newPath .= basename($directory).'/';		$newPathFromMapsPath = str_replace($data['mapsDirectoryPath'], '', $newPath);		if($newPathFromMapsPath){			$newPathFromMapsPath = '&d='.$newPathFromMapsPath;		}				if( ($result = Folder::rename($data['mapsDirectoryPath'].$directory, $newPath)) !== true ){			AdminServ::error(Utils::t('Unable to move the folder').' : '.$directory.' ('.$result.')');		}		else{			AdminServLogs::add('action', 'Move folder: '.$directory.' to '.$newPathFromMapsPath);			Utils::redirection(false, '?p='.USER_PAGE .$newPathFromMapsPath);		}	}	// Supprimer un dossier	else if( isset($_POST['optionFolderHiddenFieldAction']) && $_POST['optionFolderHiddenFieldAction'] == 'delete'){		if( ($result = Folder::delete($data['mapsDirectoryPath'].$directory)) !== true ){			AdminServ::error(Utils::t('Unable to delete the folder').' : '.$directory.' ('.$result.')');		}		else{			// Clean cache			$cache = new AdminServCache();			$cacheKey = 'mapslist-'.Str::replaceChars($data['mapsDirectoryPath'].$directory);			$cache->delete($cacheKey);						AdminServLogs::add('action', 'Delete folder: '.$directory);			Utils::redirection(false, '?p='.USER_PAGE);		}	}?>