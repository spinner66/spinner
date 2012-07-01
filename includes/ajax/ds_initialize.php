<?php
	// ISSET
	if( isset($_GET['cfg']) ){ $path_cfg = $_GET['cfg']; }else{ $path_cfg = null; }
	
	
	// INCLUDES
	require_once '../class/utils.class.php';
	$serverConfig = '../../'.$path_cfg;
	if( file_exists($serverConfig) ){
		require_once $serverConfig;
	}
	$lang = Utils::getLang();
	$langFile = '../lang/'.$lang.'.php';
	if( file_exists($langFile) ){
		require_once $langFile;
	}
	
	// DATA
	$out = array();
	if( class_exists('ServerConfig') ){
		if( isset(ServerConfig::$SERVERS) && count(ServerConfig::$SERVERS) > 0 && !isset(ServerConfig::$SERVERS['new server name']) && !isset(ServerConfig::$SERVERS['']) ){
			$out['servers'] = count(ServerConfig::$SERVERS);
			$out['label']['server'] = Utils::t('Server');
			$out['label']['name'] = Utils::t('Server name');
			$out['label']['login'] = Utils::t('Server login');
			$out['label']['connect'] = Utils::t('Connected on');
			$out['label']['status'] = Utils::t('Status');
			$out['label']['gamemode'] = Utils::t('Game mode');
			$out['label']['currentmap'] = Utils::t('Current map');
			$out['label']['players'] = Utils::t('Players');
			$out['label']['serveraccess'] = Utils::t('Server access');
		}
	}
	echo json_encode($out);
?>