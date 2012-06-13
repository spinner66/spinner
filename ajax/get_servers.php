<?php	// INCLUDES	$serverConfig = '../../config/servers.cfg.php';	if( file_exists($serverConfig) ){		require_once $serverConfig;	}	require_once '../class/GbxRemote.inc.php';	require_once '../class/tmnick.class.php';		// DATA	$out = array();	if( class_exists('ServerConfig') ){		if( isset(ServerConfig::$SERVERS) && count(ServerConfig::$SERVERS) > 0 && !isset(ServerConfig::$SERVERS['new server name']) && !isset(ServerConfig::$SERVERS['']) ){						$i = 0;			foreach(ServerConfig::$SERVERS as $serverName => $serverValues){				// Connexion				$client = new IXR_ClientMulticall_Gbx;				if( !$client->InitWithIp($serverValues['address'], $serverValues['port']) ){					$out['error'] = 'Le serveur n\'est pas accessible.';				}				else{					if( !$client->query('Authenticate', 'User', 'User') ){						$out['error'] = 'Echec d\'authentification.';					}					else{						// Connecté sur						$client->query('GetVersion');						$version = $client->getResponse();						$out['servers'][$i]['version'] = $version['Name'];												// Jeu						if($version['Name'] == 'TmForever'){							$queryName = array(								'getMapInfo' => 'GetCurrentChallengeInfo',							);						}						else{							$queryName = array(								'getMapInfo' => 'GetCurrentMapInfo',							);						}												// Requêtes						$client->addCall('GetServerName');						$client->addCall('GetSystemInfo');						$client->addCall('GetStatus');						$client->addCall('GetGameMode');						$client->addCall($queryName['getMapInfo']);						$client->addCall('GetPlayerList', array(50, 0) );						$client->addCall('GetMaxPlayers');						$client->multiquery();						$queriesData = $client->getMultiqueryResponse();												// Nom						$out['servers'][$i]['name'] = TmNick::toHtml($queriesData['GetServerName'], 10, true, false, '#999');												// Login						$system = $queriesData['GetSystemInfo'];						$out['servers'][$i]['serverlogin'] =  $system['ServerLogin'];												// Statut						$status = $queriesData['GetStatus'];						$out['servers'][$i]['status'] = $status['Name'];												// GameMode						$gameMode = $queriesData['GetGameMode'];						$gameModeListName = array(							'Script',							'Rounds',							'TimeAttack',							'Team',							'Laps',							'Laps',							'Stunts',							'Cup'						);						if($version['Name'] == 'TmForever'){							$gameMode++;						}						$gameModeName = $gameModeListName[$gameMode];						$out['servers'][$i]['gamemode'] = $gameModeName;												// Map						$currentMapInfo = $queriesData[$queryName['getMapInfo']];						$currentMapEnv = $currentMapInfo['Environnement'];						if($currentMapEnv == 'Speed'){							$currentMapEnv = 'Desert'; 						}						else if($currentMapEnv == 'Alpine'){							$currentMapEnv = 'Snow';						}						$out['servers'][$i]['map']['name'] = htmlspecialchars($currentMapInfo['Name'], ENT_QUOTES, 'UTF-8');						$out['servers'][$i]['map']['env'] = $currentMapEnv;												// Players						$out['players'][$i]['list'] = $queriesData['GetPlayerList'];												// Count players						$maxPlayers = $queriesData['GetMaxPlayers'];						$out['players'][$i]['count']['current'] = count($out['players'][$i]['list']);						$out['players'][$i]['count']['max'] = $maxPlayers['NextValue'];					}				}								// Déconnexion				$client->Terminate();				$i++;			}		}	}		// Retour	echo json_encode($out);?>