<?php
class AdminLevelConfig {
	public static $ADMINLEVELS = array(
		'SuperAdmin' => array(
			'adminlevel' => array(
				'password' => '',
				'selected' => true,
			),
			'maniaconnect' => array(),
			'access' => array(
				'server_options' => true,
				'game_infos' => true,
				'chat' => true,
				'maps_list' => true,
				'maps_local' => true,
				'maps_upload' => true,
				'maps_order' => true,
				'maps_matchsettings' => true,
				'maps_create_matchsettings' => true,
				'link_access_server' => true,
				'plugins_list' => true,
				'guest_ban' => true,
			),
			'permission' => array(
				'speed_admin' => true,
				'switch_server' => true,
				'player_kick' => true,
				'player_ban' => true,
				'player_guest' => true,
				'player_ignore' => true,
				'player_forcetospectator' => true,
				'player_forcetoplayer' => true,
				'player_changeteam' => true,
				'force_scores' => true,
				'cancel_vote' => true,
				'srvopts_general_name' => true,
				'srvopts_general_comment' => true,
				'srvopts_general_serverpassword' => true,
				'srvopts_general_spectatorpassword' => true,
				'srvopts_general_nbplayers' => true,
				'srvopts_general_nbspectators' => true,
				'srvopts_advanced' => true,
				'srvopts_adminlevelpassword' => true,
				'srvopts_importexport' => true,
				'gameinfos_general_gamemode' => true,
				'gameinfos_general_options' => true,
				'gameinfos_gamemode_options' => true,
				'chat_sendmessage' => true,
				'maps_list_moveaftercurrent' => true,
				'maps_list_removetolist' => true,
				'maps_local_add' => true,
				'maps_local_insert' => true,
				'maps_local_download' => true,
				'maps_local_rename' => true,
				'maps_local_move' => true,
				'maps_local_delete' => true,
				'maps_upload_add' => true,
				'maps_upload_insert' => true,
				'maps_upload_folder' => true,
				'maps_matchsettings_save' => true,
				'maps_matchsettings_load' => true,
				'maps_matchsettings_add' => true,
				'maps_matchsettings_insert' => true,
				'maps_matchsettings_edit' => true,
				'maps_matchsettings_delete' => true,
				'folder_new' => true,
				'folder_rename' => true,
				'folder_move' => true,
				'folder_delete' => true,
				'guestban_addplayer' => true,
				'guestban_removeplayer' => true,
				'guestban_playlist_new' => true,
				'guestban_playlist_save' => true,
				'guestban_playlist_load' => true,
				'guestban_playlist_delete' => true,
			)
		),
		'Admin' => array(
			
		),
		'User' => array(
			
		)
	);
}
?>