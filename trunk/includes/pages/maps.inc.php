<?php	// LECTURE	if( in_array(USER_PAGE, array('maps.inc', 'maps-local', 'maps-upload', 'maps-matchset', 'maps-creatematchset')) ){		$mapsDirectoryPath = AdminServ::getMapsDirectoryPath();		if(USER_PAGE == 'maps-creatematchset'){			$mapsDirectoryList = AdminServUI::getMapsDirectoryList($mapsDirectoryPath, $directory, false);		}		else{			$mapsDirectoryList = AdminServUI::getMapsDirectoryList($mapsDirectoryPath, $directory);		}	}		// ACTIONS	$hasDirectory = null;	if($directory){		$hasDirectory = '&d='.$directory;	}		if( isset($_POST['newFolderValid']) && $_POST['newFolderName'] != null || isset($_POST['newFolderName']) ){		if( Folder::create($mapsDirectoryPath.$directory.Str::replaceChars($_POST['newFolderName'])) !== true ){			AdminServ::error('Impossible de créer le dossier : '.$_POST['newFolderName']);		}		else{			Utils::redirection(false, '?p='. $_GET['goto'].$hasDirectory);		}	}	else if( isset($_POST['optionFolderHiddenField']) && $_POST['optionFolderHiddenField'] == 'delete'){		if( ! $result = Folder::delete($mapsDirectoryPath.$directory) ){			AdminServ::error('Impossible de supprimer le dossier : '.$directory.' ('.$result.')');		}		else{			Utils::redirection(false, '?p='. $_GET['goto']);		}	}?>