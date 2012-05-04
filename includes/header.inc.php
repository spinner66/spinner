<!DOCTYPE html><html lang="<?php echo USER_LANG; ?>">	<head>		<meta charset="UTF-8" />		<meta name="robots" content="noindex, nofollow" />		<title><?php if( isset($GLOBALS['page_title']) ){ echo $GLOBALS['page_title'].' | '; } echo AdminServUI::getTitle(); ?></title>		<link type="image/x-icon" rel="shortcut icon" href="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/favicon.ico" />		<?php echo AdminServUI::getCss(); ?>		<?php echo AdminServUI::getJS(); ?>	</head>	<body<?php if( isset($GLOBALS['body_class']) ){ echo ' class="'.$GLOBALS['body_class'].'"'; } ?>>		<div id="page">			<div id="page-inner">				<header>					<div id="header-inner">						<div class="logo-title">							<a href="./">								<?php if(AdminServConfig::LOGO){ ?>									<span id="logo">										<img src="<?php echo AdminServConfig::PATH_RESSOURCES .'images/'. AdminServConfig::LOGO; ?>" alt="" />									</span>								<?php } ?>																<span class="title-subtitle">									<span id="title">										#<?php echo AdminServUI::getTitle('html'); ?>									</span>																		<?php if(AdminServConfig::SUBTITLE){ ?>										<span id="subtitle">											<?php echo AdminServConfig::SUBTITLE; ?>										</span>									<?php } ?>								</span>							</a>						</div>												<div class="theme-lang">							<?php if( count(ExtensionConfig::$THEMES) > 0){ ?>								<div id="theme">									<?php echo AdminServUI::getThemeList( array(USER_THEME => ExtensionConfig::$THEMES[USER_THEME]) ); ?>								</div>							<?php } ?>														<?php if( count(ExtensionConfig::$LANG) > 0){ ?>								<div id="lang">									<?php echo AdminServUI::getLangList( array(USER_LANG => ExtensionConfig::$LANG[USER_LANG]) ); ?>								</div>							<?php } ?>						</div>												<?php if( defined('SERVER_ID') ){ ?>							<div class="speed-admin">								<ul>									<li><a class="restart" href="./">RestartMap</a></li>									<li><a class="next" href="./">NextMap</a></li>									<li><a class="endround" href="./">ForceEndRound</a></li>								</ul>							</div>														<div class="switch-server">								<select id="switchServerList" name="switchServerList">									<?php echo AdminServUI::getServerList(); ?>								</select>							</div>						<?php } ?>						<div id="error"<?php if( !isset($_SESSION['error']) ){ echo ' hidden="hidden"'; } ?>><?php if( isset($_SESSION['error']) && $_SESSION['error'] != null){ echo $_SESSION['error']; unset($_SESSION['error']); } ?></div>						<div id="info"<?php if( !isset($_SESSION['info']) ){ echo ' hidden="hidden"'; } ?>><?php if( isset($_SESSION['info']) && $_SESSION['info'] != null){ echo $_SESSION['info']; unset($_SESSION['info']); } ?></div>					</div>					<div id="header-color-line"></div>				</header>								<?php if( isset($_SESSION['adminserv']['sid']) && isset($_SESSION['adminserv']['name']) && isset($_SESSION['adminserv']['password']) && isset($_SESSION['adminserv']['adminlevel']) && !isset($_GET['error']) ){ ?>					<nav class="horizontal-nav">						<div id="nav-inner" class="menu-principal">							<ul class="menu-left">								<li><a class="button light<?php if(USER_PAGE == 'index'){ echo ' active'; } ?>" href="./">General</a></li>								<li><a class="button light<?php if(USER_PAGE == 'srvopts'){ echo ' active'; } ?>" href="?p=srvopts">Server options</a></li>								<li><a class="button light<?php if(USER_PAGE == 'gameinfos'){ echo ' active'; } ?>" href="?p=gameinfos">Game infos</a></li>								<li><a class="button light<?php if(USER_PAGE == 'chat'){ echo ' active'; } ?>" href="?p=chat">Chat</a></li>								<li><a class="button light<?php if(USER_PAGE == 'maps-list' || USER_PAGE == 'maps-upload' || USER_PAGE == 'maps-local' || USER_PAGE == 'maps-matchset' || USER_PAGE == 'maps-order'){ echo ' active'; } ?>" href="?p=maps-list">Maps</a></li>							</ul>														<ul class="menu-right">								<li><a class="button light" href="<?php echo LINK_PROTOCOL .'://#join='. SERVER_LOGIN; ?>"><?php echo Utils::t('Access server'); ?></a></li>								<li><a class="button light<?php if(USER_PAGE == 'plugins'){ echo ' active'; } ?>" href="?p=plugins">Plugins</a></li>								<li><a class="button light<?php if(USER_PAGE == 'guestban'){ echo ' active'; } ?>" href="?p=guestban">Guest-Ban</a></li>								<li><a class="button light" href="?logout"><?php echo Utils::t('Disconnect'); ?></a></li>							</ul>						</div>					</nav>				<?php }else if(USER_PAGE == 'servers' || USER_PAGE == 'addserver'){ ?>					<nav class="horizontal-nav">						<div id="nav-inner" class="menu-principal">							<ul class="menu-left">								<?php if( OnlineConfig::ADD_ONLY !== true ){ ?>									<li><a class="button light<?php if(USER_PAGE == 'servers'){ echo ' active'; } ?>" href="?p=servers"><?php echo Utils::t('Servers'); ?></a></li>								<?php } ?>								<li><a class="button light<?php if(USER_PAGE == 'addserver'){ echo ' active'; } ?>" href="?p=addserver"><?php if( defined('IS_SERVER_EDITION') ){ echo 'Éditer un serveur'; }else{ echo Utils::t('Add server'); } ?></a></li>							</ul>							<?php if( AdminServ::hasServer() ){ ?>								<ul class="menu-right">									<li><a class="button light" href="./?logout"><?php echo Utils::t('Back'); ?></a></li>								</ul>							<?php } ?>						</div>					</nav>				<?php }else{ ?>					<?php if( !isset($_SESSION['adminserv']['check_password']) ){ ?>						<div id="connexion">							<div id="connexion-inner">								<form method="post" action=".">									<div class="connexion-label">										<label for="as_server"><?php echo Utils::t('Server'); ?> :</label>										<select name="as_server" id="as_server">											<?php echo AdminServUI::getServerList(); ?>										</select>																				<label for="as_password"><?php echo Utils::t('Password'); ?> :</label>										<input class="text" type="password" name="as_password" id="as_password" value="" />																				<label for="as_adminlevel"><?php echo Utils::t('Admin level'); ?> :</label>										<select name="as_adminlevel" id="as_adminlevel"></select>									</div>									<div class="connexion-login">										<input class="button light" type="submit" name="as_login" id="as_login" value="<?php echo Utils::t('Connection'); ?>" />									</div>								</form>							</div>						</div>					<?php } ?>				<?php } ?>				<div id="content">					<div id="content-inner">