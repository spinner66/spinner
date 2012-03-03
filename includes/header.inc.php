<!DOCTYPE html><html lang="fr">	<head>		<meta charset="UTF-8" />		<meta name="robots" content="noindex, nofollow" />		<title><?php if( isset($GLOBALS['page_title']) ){ echo $GLOBALS['page_title'].' | '; } echo AdminServTemplate::getTitle(); ?></title>		<?php echo AdminServTemplate::getCss(); ?>		<?php echo AdminServTemplate::getJS(); ?>	</head>	<body class="<?php if( isset($GLOBALS['body_class']) ){ echo $GLOBALS['body_class']; } ?>">		<div id="page">			<div id="page-inner">				<header>					<div id="header-inner">						<div class="logo-title">							<a href="./">								<?php if(AdminServConfig::LOGO){ ?>									<span id="logo">										<img src="<?php echo AdminServConfig::PATH_RESSOURCES .'images/'. AdminServConfig::LOGO; ?>" alt="" />									</span>								<?php } ?>																<span class="title-subtitle">									<span id="title">										#<?php echo AdminServTemplate::getTitle('html'); ?>									</span>																		<?php if(AdminServConfig::SUBTITLE){ ?>										<span id="subtitle">											<?php echo AdminServConfig::SUBTITLE; ?>										</span>									<?php } ?>								</span>							</a>						</div>												<div class="theme-lang">							<?php if( count(ExtensionConfig::$THEMES) > 0){ ?>								<div id="theme">									<?php echo AdminServTemplate::getThemeList(); ?>								</div>							<?php } ?>														<?php if( count(ExtensionConfig::$LANG) > 0){ ?>								<div id="lang">									<?php echo AdminServTemplate::getLangList(); ?>								</div>							<?php } ?>						</div>												<?php if( defined('SERVER_ID') ){ ?>							<div class="speed-admin">								<ul>									<li><a class="restart" href="./">RestartMap</a></li>									<li><a class="next" href="./">NextMap</a></li>									<li><a class="endround" href="./">ForceEndRound</a></li>								</ul>							</div>														<div class="switch-server">								<select id="switchServerList" name="switchServerList">									<?php echo AdminServTemplate::getServerList(); ?>								</select>							</div>						<?php } ?>												<div id="error"<?php if( !isset($GLOBALS['error']) ){ echo ' hidden="hidden"'; } ?>><?php if( isset($GLOBALS['error']) && $GLOBALS['error'] != null){ echo $GLOBALS['error']; } ?></div>						<div id="info"<?php if( !isset($GLOBALS['info']) ){ echo ' hidden="hidden"'; } ?>><?php if( isset($GLOBALS['info']) && $GLOBALS['info'] != null){ echo $GLOBALS['info']; } ?></div>					</div>					<div id="header-color-line"></div>				</header>								<?php if( isset($_SESSION['adminserv']['sid']) && isset($_SESSION['adminserv']['name']) && isset($_SESSION['adminserv']['password']) && isset($_SESSION['adminserv']['adminlevel']) && !isset($_GET['error']) ){ ?>					<nav>						<div id="nav-inner" class="menu-principal">							<ul class="menu-left">								<li><a class="button light<?php if(USER_PAGE == 'index'){ echo ' active'; } ?>" href="./">General</a></li>								<li><a class="button light<?php if(USER_PAGE == 'srvopts'){ echo ' active'; } ?>" href="?p=srvopts">Server options</a></li>								<li><a class="button light<?php if(USER_PAGE == 'gameinfos'){ echo ' active'; } ?>" href="?p=gameinfos">Game infos</a></li>								<li><a class="button light<?php if(USER_PAGE == 'chat'){ echo ' active'; } ?>" href="?p=chat">Chat</a></li>								<li><a class="button light<?php if(USER_PAGE == 'maps' || USER_PAGE == 'maps-upload' || USER_PAGE == 'maps-local' || USER_PAGE == 'maps-matchset' || USER_PAGE == 'maps-order'){ echo ' active'; } ?>" href="?p=maps">Maps</a></li>							</ul>														<ul class="menu-right">								<li><a class="button light" href="<?php echo LINK_PROTOCOL .'://#join='. SERVER_LOGIN; ?>">Accéder au serveur</a></li>								<li><a class="button light<?php if(USER_PAGE == 'plugins'){ echo ' active'; } ?>" href="?p=plugins">Plugins</a></li>								<li><a class="button light<?php if(USER_PAGE == 'guestban'){ echo ' active'; } ?>" href="?p=guestban">Guest-Ban</a></li>								<li><a class="button light" href="?logout">Déconnexion</a></li>							</ul>						</div>					</nav>				<?php }else if(USER_PAGE == 'servers' || USER_PAGE == 'addserver'){ ?>					<nav>						<div id="nav-inner" class="menu-principal">							<ul class="menu-left">								<li><a class="button light<?php if(USER_PAGE == 'servers'){ echo ' active'; } ?>" href="./?p=servers">Serveurs</a></li>								<li><a class="button light<?php if(USER_PAGE == 'addserver'){ echo ' active'; } ?>" href="./?p=addserver">Ajouter un serveur</a></li>							</ul>							<?php if( isset(ServerConfig::$SERVERS) && count(ServerConfig::$SERVERS) > 0 && !isset(ServerConfig::$SERVERS['new server name']) ){ ?>								<ul class="menu-right">									<li><a class="button light" href="./">Retour</a></li>								</ul>							<?php } ?>						</div>					</nav>				<?php }else{ ?>					<div id="connexion">						<div id="connexion-inner">							<form method="post" action=".">								<div class="connexion-label">									<label for="as_server">Serveur :</label>									<select name="as_server" id="as_server">										<?php echo AdminServTemplate::getServerList(); ?>									</select>																		<label for="as_password">Mot de passe :</label>									<input class="text" type="password" name="as_password" id="as_password" value="" />																		<label for="as_adminlevel">Niveau admin :</label>									<select name="as_adminlevel" id="as_adminlevel"></select>								</div>								<div class="connexion-login">									<input class="button light" type="submit" name="as_login" id="as_login" value="Connexion" />								</div>							</form>						</div>					</div>				<?php } ?>				<div id="content">					<div id="content-inner">