<?php	// INCLUDES	require_once '../../config/displayserv.cfg.php';	// ServerConfig	if(DisplayServConfig::USE_ADMINSERV_SERVER_CONFIG !== null){		$serverConfig = DisplayServConfig::USE_ADMINSERV_SERVER_CONFIG;	}else{		$serverConfig = '../../config/servers.cfg.php';	}	if( file_exists($serverConfig) ){		require_once $serverConfig;	}	require_once '../displayserv.inc.php';	DisplayServ::getClass();		// DATA	$out = array();	if( class_exists('ServerConfig') ){		if( isset(ServerConfig::$SERVERS) && count(ServerConfig::$SERVERS) > 0 && !isset(ServerConfig::$SERVERS['new server name']) && !isset(ServerConfig::$SERVERS['']) ){						$i = 0;			foreach(ServerConfig::$SERVERS as $serverName => $serverValues){				// Connexion				$client = new IXR_Client_Gbx;				if( !$client->InitWithIp($serverValues['address'], $serverValues['port']) ){					$out['error'] = 'Le serveur n\'est pas accessible.';				}				else{					if( !$client->query('Authenticate', 'User', 'User') ){						$out['error'] = 'Echec d\'authentification.';					}					else{						// Nom						$client->query('GetServerName');						$out['servers'][$i]['name'] = $client->getResponse();												// Login						$client->query('GetSystemInfo');						$system = $client->getResponse();						$out['servers'][$i]['serverlogin'] =  $system['ServerLogin'];												// Connecté sur						$client->query('GetVersion');						$version = $client->getResponse();						$out['servers'][$i]['version'] = $version['Name'];												// Statut						$client->query('GetStatus');						$status = $client->getResponse();						$out['servers'][$i]['status'] = $status['Name'];												// GameMode						$client->query('GetGameMode');						$out['servers'][$i]['gamemode'] = $client->getResponse();												// Map						$client->query('GetCurrentChallengeInfo');						$currentMapInfo = $client->getResponse();						$currentMapEnv = $currentMapInfo['Environnement'];						if($currentMapEnv == 'Speed'){							$currentMapEnv = 'Desert'; 						}						else if($currentMapEnv == 'Alpine'){							$currentMapEnv = 'Snow';						}						$out['servers'][$i]['map']['name'] = htmlspecialchars($currentMapInfo['Name'], ENT_QUOTES, 'UTF-8');						$out['servers'][$i]['map']['env'] = $currentMapEnv;												// Players						$client->query('GetPlayerList', 50, 0);						$out['players'][$i]['list'] = $client->getResponse();												// Count players						$client->query('GetMaxPlayers');						$maxPlayers = $client->getResponse();						$out['players'][$i]['count']['current'] = count($out['players'][$i]['list']);						$out['players'][$i]['count']['max'] = $maxPlayers['NextValue'];					}				}								// Déconnexion				$client->Terminate();				$i++;			}		}	}		// Retour	echo json_encode($out);?>