<!DOCTYPE html><html lang="<?php echo USER_LANG; ?>">	<head>		<meta charset="UTF-8" />		<meta name="robots" content="noindex, nofollow" />		<title><?php if( isset($GLOBALS['page_title']) ){ echo $GLOBALS['page_title'].' | '; } echo AdminServUI::getTitle(); ?></title>		<link type="image/png" rel="icon" href="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/32/favicon.png" sizes="32x32" />		<?php echo AdminServUI::getCss(); ?>		<?php echo AdminServUI::getJS(); ?>	</head>	<body<?php if( isset($GLOBALS['body_class']) ){ echo ' class="'.$GLOBALS['body_class'].'"'; } ?>>		<div id="page">			<div id="page-inner">				<header>					<div id="header-inner">						<div class="logo-title">							<a href="./">								<?php if(AdminServConfig::LOGO){ ?>									<span id="logo">										<img src="<?php echo AdminServConfig::PATH_RESSOURCES .'images/'. AdminServConfig::LOGO; ?>" alt="" />									</span>								<?php } ?>																<span class="title-subtitle">									<span id="title">										#<?php echo AdminServUI::getTitle('html'); ?>									</span>																		<?php if(AdminServConfig::SUBTITLE){ ?>										<span id="subtitle">											<?php echo AdminServConfig::SUBTITLE; ?>										</span>									<?php } ?>								</span>							</a>						</div>												<div class="theme-lang">							<?php if( count(ExtensionConfig::$THEMES) > 0){ ?>								<div id="theme">									<?php										$currentTheme = array();										if( isset(ExtensionConfig::$THEMES[USER_THEME]) ){											$currentTheme = array(USER_THEME => ExtensionConfig::$THEMES[USER_THEME]);										}										echo AdminServUI::getThemeList($currentTheme);									?>								</div>							<?php } ?>														<?php if( count(ExtensionConfig::$LANG) > 0){ ?>								<div id="lang">									<?php										$currentLang = array();										if( isset(ExtensionConfig::$LANG[USER_LANG]) ){											$currentLang = array(USER_LANG => ExtensionConfig::$LANG[USER_LANG]);										}										echo AdminServUI::getLangList($currentLang);									?>								</div>							<?php } ?>						</div>												<?php if( defined('SERVER_ID') ){ ?>							<div class="speed-admin">								<ul>									<li><a tabindex="-1" class="restart" href="./">RestartMap</a></li>									<li><a tabindex="-1" class="next" href="./">NextMap</a></li>									<li><a tabindex="-1" class="endround" href="./">ForceEndRound</a></li>								</ul>							</div>														<div class="switch-server">								<select tabindex="1" id="switchServerList" name="switchServerList">									<?php echo AdminServUI::getServerList(); ?>								</select>							</div>						<?php } ?>						<div id="error"<?php if( !isset($_SESSION['error']) ){ echo ' hidden="hidden"'; } ?>><?php if( isset($_SESSION['error']) && $_SESSION['error'] != null){ echo $_SESSION['error']; unset($_SESSION['error']); } ?></div>						<div id="info"<?php if( !isset($_SESSION['info']) ){ echo ' hidden="hidden"'; } ?>><?php if( isset($_SESSION['info']) && $_SESSION['info'] != null){ echo $_SESSION['info']; unset($_SESSION['info']); } ?></div>					</div>					<div id="header-color-line"></div>				</header>								<?php if( isset($_SESSION['adminserv']['sid']) && isset($_SESSION['adminserv']['name']) && isset($_SESSION['adminserv']['password']) && $_SESSION['adminserv']['password'] != null && isset($_SESSION['adminserv']['adminlevel']) && !isset($_GET['error']) ){ ?>					<nav class="horizontal-nav">						<div id="nav-inner" class="menu-principal">							<ul class="menu-left">								<li class="accessgeneral"><a tabindex="2" accesskey="1" class="button light<?php if(USER_PAGE == 'index'){ echo ' active'; } ?>" href="./"><?php echo Utils::t('General'); ?></a></li>								<li><a tabindex="3" class="button light<?php if(USER_PAGE == 'srvopts'){ echo ' active'; } ?>" href="?p=srvopts"><?php echo Utils::t('Server options'); ?></a></li>								<li><a tabindex="4" class="button light<?php if(USER_PAGE == 'gameinfos'){ echo ' active'; } ?>" href="?p=gameinfos"><?php echo Utils::t('Game infos'); ?></a></li>								<li><a tabindex="5" class="button light<?php if(USER_PAGE == 'chat'){ echo ' active'; } ?>" href="?p=chat"><?php echo Utils::t('Chat'); ?></a></li>								<li><a tabindex="6" class="button light<?php if( substr(USER_PAGE, 0, 4) == 'maps'){ echo ' active'; } ?>" href="?p=maps-list"><?php echo Utils::t('Maps'); ?></a></li>							</ul>														<ul class="menu-right">								<?php if( defined('IS_RELAY') && IS_RELAY ){ ?>									<li class="accesslink"><a tabindex="7" class="button light" href="<?php echo LINK_PROTOCOL .'://#spectate='. SERVER_LOGIN; ?>"><?php echo Utils::t('Access relay'); ?></a></li>								<?php }else{ ?>									<li class="accesslink"><a tabindex="8" class="button light" href="<?php echo LINK_PROTOCOL .'://#join='. SERVER_LOGIN; ?>"><?php echo Utils::t('Access server'); ?></a></li>								<?php } ?>								<?php if( AdminServPlugin::hasPlugin() ){ ?>									<li><a tabindex="9" class="button light<?php if(USER_PAGE == 'plugins-list' || CURRENT_PLUGIN){ echo ' active'; } ?>" href="?p=plugins-list"><?php echo Utils::t('Plugins'); ?></a></li>								<?php } ?>								<li><a tabindex="10" class="button light<?php if(USER_PAGE == 'guestban'){ echo ' active'; } ?>" href="?p=guestban">Guest-Ban</a></li>								<li><a tabindex="11" class="button light" href="?logout"><?php echo Utils::t('Disconnect'); ?></a></li>							</ul>						</div>					</nav>				<?php }else if(USER_PAGE == 'servers' || USER_PAGE == 'addserver' || USER_PAGE == 'servers-order' || USER_PAGE == 'serversconfigpassword'){ ?>					<nav class="horizontal-nav">						<div id="nav-inner" class="menu-principal">							<ul class="menu-left">								<?php if( OnlineConfig::ADD_ONLY !== true ){ ?>									<li><a class="button light<?php if(USER_PAGE == 'servers'){ echo ' active'; } ?>" href="?p=servers"><?php echo Utils::t('Servers'); ?></a></li>								<?php } ?>								<li><a class="button light<?php if(USER_PAGE == 'addserver'){ echo ' active'; } ?>" href="?p=addserver<?php if( defined('IS_SERVER_EDITION') ){ echo '&id='.$id; } ?>"><?php if( defined('IS_SERVER_EDITION') ){ echo 'Éditer un serveur'; }else{ echo Utils::t('Add server'); } ?></a></li>							</ul>							<ul class="menu-right">								<?php $hasServer = AdminServServerConfig::hasServer(); ?>								<?php if( OnlineConfig::ADD_ONLY !== true && $hasServer ){ ?>									<li><a class="button light<?php if(USER_PAGE == 'servers-order'){ echo ' active'; } ?>" href="?p=servers-order"><?php echo Utils::t('Change order'); ?></a></li>								<?php } ?>								<?php if( OnlineConfig::PASSWORD != null ){ ?>									<li><a class="button light<?php if(USER_PAGE == 'serversconfigpassword'){ echo ' active'; } ?>" href="?p=serversconfigpassword"><?php echo Utils::t('Change password'); ?></a></li>								<?php } ?>								<?php if($hasServer){ ?>									<li><a class="button light" href="./?logout"><?php echo Utils::t('Back'); ?></a></li>								<?php } ?>							</ul>						</div>					</nav>				<?php }else{ ?>					<?php if( !isset($_SESSION['adminserv']['check_password']) && !isset($_SESSION['adminserv']['get_password']) ){ ?>						<div id="connection">							<div id="connection-inner">								<form method="post" action=".">									<div class="connection-label">										<label for="as_server"><?php echo Utils::t('Server'); ?> :</label>										<select tabindex="1" name="as_server" id="as_server">											<?php echo AdminServUI::getServerList(); ?>										</select>																				<label for="as_password"><?php echo Utils::t('Password'); ?> :</label>										<input tabindex="2" class="text" type="password" name="as_password" id="as_password" value="" />																				<label for="as_adminlevel"><?php echo Utils::t('Admin level'); ?> :</label>										<select tabindex="3" name="as_adminlevel" id="as_adminlevel"></select>									</div>									<div class="connection-login">										<input tabindex="4" class="button light" type="submit" name="as_login" id="as_login" value="<?php echo Utils::t('Connection'); ?>" />									</div>								</form>							</div>						</div>					<?php } ?>				<?php } ?>				<div id="content">					<div id="content-inner">