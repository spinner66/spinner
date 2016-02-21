**AdminServ** est un contrôleur de serveur dédié pour TrackMania Forever et ManiaPlanet. Il permet de gérer ses serveurs à distance via son navigateur internet.


# Requis #
  * Un serveur web (de préférence évitez les serveurs gratuits)
  * PHP version 5.3+
  * Un serveur dédié TrackMania Forever / ManiaPlanet en fonctionnement


# Installation #
Extraire le fichier "adminserv.zip" où bon vous semble sur un serveur web, et accédez au site via votre navigateur internet.


# Configuration #

## Serveurs ##

### En ligne ###
La configuration en ligne est activée par défaut. S'il n'y a aucun serveur, AdminServ détermine que c'est votre 1ère connexion et demande la création d'un mot de passe pour gérer la configuration des serveurs en ligne.

Une fois le mot de passe enregistré, vous pouvez ajouter votre serveur :
  * **Nom du serveur** : Ce nom est utilisé dans les listes déroulantes pour le choix du serveur à administrer. Les caractères spéciaux sont automatiquement enlevés.
  * **Adresse** : L'adresse IP ou le nom de domaine pointant vers la machine du serveur dédié.
  * **Port XMLRPC** : Ce port permet de contrôler le serveur. Il est défini dans le fichier "dedicated\_cfg.txt" et il doit être ouvert dans le parfeu et routeur pour y accéder à distance.


Les informations suivantes sont optionnelles :
  * **Dossier de base pour les maps** : Chemin par défaut à partir du dossier **Maps** ou **Tracks** pour lister les maps et les matchsettings (ajouté dans la 2.0.4).
  * **MatchSettings du serveur** : En général, on utilise un matchsettings spécifique pour le serveur contenant la liste des maps et les réglages du mode de jeu. Vous pouvez mettre le nom du matchsettings à partir du dossier **Maps** ou **Tracks** du serveur dédié pour l'enregistrer automatiquement par la suite. L'extension du fichier n'est pas utile. Si le matchsettings n'existe pas, il sera créé.
  * **Niveaux SuperAdmin, Admin et User** : Vous pouvez pour les 3 niveaux d'autorisations en limiter l'accès. Les valeurs possibles sont :
    * **all** : accès à tous
    * **local** : accès au réseau local
    * **192.168.0.1, 192.168.0.2** : accès à une ou plusieurs adresses IP
    * **none** : accès enlevé de la liste


Une fois le serveur enregistré, vous pouvez visualiser la liste des serveurs dans l'onglet **Serveurs** ou commencer à administrer le serveur en cliquant sur **Retour** en haut à droite.

Pour revenir à la configuration en ligne, il suffit de rajouter **/config** dans l'url. Vous pouvez aussi modifier le mot de passe de connexion à tout moment dans l'onglet **Changer le mot de passe**.


### Hors ligne ###
Il est aussi possible d'éditer le fichier de configuration des serveurs dans le dossier d'AdminServ : **/config/servers.cfg.php**
Quelques notions de PHP vous serons utiles. La configuration d'un serveur est la suivante :
```
'Server name' => array(
    'address' => 'localhost',
    'port' => 5000,
    'mapsbasepath' => '',
    'matchsettings' => 'MatchSettings/',
    'adminlevel' => array(
        'SuperAdmin' => 'all’,
        'Admin' => 'all',
        'User' => 'all',
    )
),
```


## AdminServ ##

La configuration d'AdminServ est optionnelle. Le fichier correspondant est : **config/adminserv.cfg.php**

### OnlineConfig ###
  * **ACTIVATE** : Activer ou non la configuration en ligne.
  * **PASSWORD** : Mot de passe en MD5 généré automatiquement par le configurateur de serveurs.
  * **ADDRESS** : Limiter l'accès à la configuration suivant l'adresse IP. Désactivé par défaut. Possibilité de mettre `localhost`.
  * **ADD\_ONLY** : Ajouter seulement les serveurs. Vous ne pouvez pas voir les autres serveurs, ni les modifier ou les supprimer.

### AdminServConfig ###
  * **TITLE** : Le titre de l'application. Par défaut **Admin,Serv** sachant que la virgule sépare la couleur.
  * **SUBTITLE** : Le sous titre de l'application. Mettez `null` pour désactiver.
  * **LOGO** : Le chemin de l'image principale de l'application à partir du dossier /ressources/images/. Mettez `null` pour désactiver.
  * **DEFAULT\_THEME** : Thème par défaut chargé à la 1ère connexion. La valeur correspond à la configuration des thèmes : [AdminServ#Extension](AdminServ#Extension.md)
  * **DEFAULT\_LANGUAGE** : Langue par défaut chargée à la 1ère connexion. La valeur correspond à la configuration des langues : [AdminServ#Extension](AdminServ#Extension.md). Si la valeur est auto, alors la langue est récupérée automatiquement.
  * **USE\_DISPLAYSERV** : Utiliser l'outil **DisplayServ** sur la page de connexion. Pour la documentation : [DisplayServFR](DisplayServFR.md)
  * **AUTOSAVE\_MATCHSETTINGS** : Permet d'enregistrer automatiquement le MatchSettings à chaque modification des informations de jeu et d'ajout de map. Le matchsettings enregistré est celui défini dans la configuration du serveur.
  * **MD5\_PASSWORD** : Si activé, le mot de passe pour la connexion sera testé en MD5. Il faudra alors mettre le mot de passe du serveur dédié en MD5 également.
  * **LIMIT\_PLAYERS\_LIST** : Permet de définir le nombre de lignes max sur la page "général".
  * **LIMIT\_MAPS\_LIST** : Permet de définir le nombre de ligne max sur la page maps-list. Pas de limite pour les maps locales.
  * **LOCAL\_GET\_MAPS\_ON\_SERVER** : Si activé, AdminServ va charger la liste des maps du serveur pour faire une comparaison avec les maps locales. L'icône change d'état et il n'est pas possible de renommer, déplacer ou supprimer une map utilisée sur le serveur. Cela permet d'éviter les erreurs (pour les chemins des fichiers dans le matchsettings) et aussi pour visualiser facilement lesquelles sont déjà sur le serveur. L'inconvénient est que le temps de chargement sera augmenté s'il y a beaucoup de maps sur le serveur.
  * **RECENT\_STATUS\_PERIOD** : Période pour définir le statut d’un fichier en tant que récent. Le temps est à spécifier en heure. Dans certaines pages, les lignes modifiées récemment seront surlignées en jaune pendant le temps définit par ce paramètre.
  * **SERVER\_CONNECTION\_TIMEOUT** : Temps maximum à attendre pour se connecter au serveur.
  * **COOKIE\_EXPIRE** : Nombre de jours avant que les paramètres de l'utilisateur expirent. Les paramètres enregistrés dans le cookie sont : thème, langue, pseudo de chat, couleur de chat, dernier serveur administré avec son niveau d'administration.
  * **FOLDERS\_OPTIONS** : Vous pouvez activer ou désactiver les options de dossier tel que : nouveau, renommer, déplacer, supprimer. Pour chaque action, il y a 2 paramètres à définir. L'activation de l'action et le niveau admin minimum pour utiliser cette action.
  * **MAPS\_HIDDEN\_FOLDERS** : Permet de cacher les dossiers inutilisés dans les pages maps.
  * **MATCHSET\_HIDDEN\_FOLDERS** : Permet de cacher les dossiers inutilisés dans les pages matchsettings.
  * **MAP\_EXTENSION** : Double extension pour lister les maps.
  * **MATCHSET\_EXTENSION** : Extension pour lister les matchsettings.
  * **PLAYLIST\_EXTENSION** : Extension pour lister les playlists (guestlist, blacklist, etc). Les deux playlists générées par le serveur dédié (guestlist et blacklist) sont déjà prises en compte.
  * **ALLOWED\_EXTENSIONS** : Extensions autorisées pour l'upload de fichier.
  * **SIZE\_LIMIT** : La taille limite par fichier envoyé. Si la valeur est `auto`, c'est la taille du serveur web qui est utilisée (configuration du `php.ini`).
  * **UPLOAD\_ONLINE\_FOLDER** : Chemin vers le dossier où les maps seront envoyées en mode distant à partir du dossier **Maps** ou **Tracks** (ajouté dans la 2.0.3).
  * **LOGS** : Logs d'AdminServ. Vous pouvez activer les logs pour chaque fichier. Accès aux pages (`access`), actions utilisées (`action`), erreurs rencontrées (`error`). Il est possible d'ajouter des fichiers de logs dans la configuration. Chaque fichier est crée s'il n'existe pas. Pour écrire un log, utilisez :
```
AdminServLogs ::add ('nom_du_fichier_log', 'texte à écrire');
```
  * **MULTI\_ADMINSERV** : Permet d'utiliser plusieurs instances d'AdminServ. Utile pour ceux qui gèrent beaucoup de serveurs. Cela permet de modifier l'emplacement des dossiers utilisés pour toutes les instances et faciliter la maintenance d'AdminServ ainsi que l'ajout mutualisé de plugins.
  * **PATH\_INCLUDES** : Chemin du dossier `includes`.
  * **PATH\_PLUGINS** : Chemin du dossier `plugins`.
  * **PATH\_RESSOURCES** : Chemin du dossier `ressources`.
  * **PLUGINS\_LIST** : Chemin du fichier de configuration des plugins. Par défaut, AdminServ utilise la configuration `Extension.cfg.php`. Cette option permet de définir une liste de plugins pour plusieurs instances d'AdminServ. Dans le fichier php spécifié, il faut une variable :
```
    $PLUGINS = array(
        'pluginfoldername',
    );
```
  * **PLUGINS\_LIST\_TYPE**: La méthode utilisée pour le fichier de configuration des plugins. Par défaut `replace`, le nouveau fichier remplace la configuration actuelle. Mais vous pouvez aussi ajouter les plugins à la suite avec `add`.

### DataBaseConfig & FTPConfig ###
Ces 2 configurations ne sont pas utilisées par AdminServ. Mais elles pourront servir pour d’éventuels plugins...

## Extension ##
La configuration pour l'extension d'AdminServ est dans : **config/extension.cfg.php**

  * **PLUGINS** : Liste des plugins installés. Il faut mettre le nom du dossier du plugin téléchargé. Pour tout savoir sur les plugins : [PluginsFR](PluginsFR.md)
  * **THEMES** : Liste des thèmes de couleurs. Pour chaque nom de thème, il y a 2 couleurs à définir. La 1ère est la couleur principale (couleur vive) et la 2ème est la couleur utilisée pour les éléments cliqués (couleur clair).
```
    'nom_de_la_couleur_du_theme' => array(
        'red', // couleur vive
        'rgba(0, 0, 0, .1)' // couleur clair
    )
```
> > Les couleurs peuvent être en texte, hexadécimal et rgb(a).
  * **LANG** :  Liste des langues disponibles:
```
    'code_lang' => 'titre'
```
> > Le code langue correspond à la fois au nom de l’image (ressources/images/lang/) et au nom du fichier (/includes/lang/). Le titre est indicatif.
  * **GAMEMODES** : Liste des modes de jeu. La liste doit être faite à partir de ManiaPlanet.
  * **TEAMSCRIPTS** : Liste des scripts qui se jouent en mode équipe. Permet d'optimiser l'affichage en mode équipe si le serveur utilise un des script listés.
  * **MAPSMENU** : Liste du menu dans les pages **Maps**. Vous pouvez modifier l'ordre ou rajouter une entrée redirigeant vers un plugin par exemple.

# Connexion #
Une fois un serveur configuré, il suffit de le sélectionner et d'entrer le mot de passe du serveur dédié pour se connecter à l'administration.

A la prochaine connexion, AdminServ enregistre automatiquement le dernier serveur utilisé et le sélectionne. Vous pouvez aussi sélectionner un serveur avec le paramètre `?server=1` pour sélectionner le 2ème serveur par exemple.

Le mot de passe peut être crypté en MD5 si activé dans la configuration `adminserv.cfg.php`. Dans ce cas, il faudra aussi un mot de passe en md5 pour le serveur dédié.

Vous pouvez enlever des niveaux admin dans la configuration des serveurs. Il suffit de définir la valeur du niveau à `none`.

Vous pouvez appuyer sur entrée dans toute la page pour se connecter automatiquement si toutes les valeurs demandées sont remplies.

Pour masquer l'affichage de DisplayServ, vous devez désactiver l'option `use_displayserv` dans la configuration `adminserv.cfg.php`. Tout savoir sur Displayserv : [DisplayServFR](DisplayServFR.md)

# Spécifications sur les pages #

## Général ##
  * Les données sont mises à jour toutes les 10 secondes.
  * L'affichage de la page diffère si le mode de jeu est **Team**. Sous les infos de la map, il y a la possibilité de forcer les scores (envoi un message sur le serveur à chaque modification), et les joueurs sont triés et affichés par équipe. Il y a également 2 nouveaux boutons pour modifier l'équipe des joueurs.
  * Le bouton **Annuler le vote** apparaît seulement s'il y a un vote en cours sur le serveur.
  * Il est possible de stopper le serveur seulement sur cette page. Utilisez le paramètre `?stop` dans l'url. Cette action est disponible que pour le niveau SuperAdmin.

## Options du serveur ##
  * Les informations sont modifiées en direct sur le serveur mais ne sont pas enregistrées dans le fichier de configuration `dedicated_cfg.txt`
  * Pour le nom et le commentaire du serveur, il y a un aperçu de l'affichage dans le jeu.

## Informations de jeu ##
  * A chaque sélection d'un nouveau mode de jeu, sa configuration est chargée en dessous.
  * Ces informations de jeu peuvent être sauvegardées dans le matchsettings courant.

## Chat ##
  * Les données sont récupérées toutes les 3 secondes.
  * Le lien en haut à droite **Masquer les lignes du serveur** permet de masquer les lignes qui sont générées par le gestionnaire du serveur. Seules les lignes des joueurs seront alors affichées.
  * Le pseudo, si spécifié, s'affiche dans le chat comme cela : `[admin:Pseudo] Message`
  * Vous pouvez appuyer sur entrée dans le champ message pour envoyer votre texte.

## Maps - Liste ##
  * La map en cours est surlignée en jaune. Aucune action n'est possible pour cette map.
  * A partir de 25 maps, un lien **Allez à la map en cours** apparaît pour scroller vers celle-ci.

## Maps - Local ##
  * Si vous cliquez sur un dossier, vous aurez les options du dossier. Vous pouvez alors renommer, déplacer ou supprimer un dossier.
  * Toutes les actions fonctionnent avec plusieurs maps. Pour l'action téléchargement, vous aurez un fichier zip contenant toutes les maps. Pour les actions de suppression, il y a toujours une confirmation à effectuer.
  * Si les maps sont surlignées en jaune, c'est qu'elles ont étés ajoutées ou modifiées récemment.

## Maps - Envoyer ##
  * **Ajouter** : pour ajouter les maps en fin de liste
  * **Insérer** : pour insérer les maps après la map en cours
  * **Envoyer** : pour simplement envoyer dans le dossier choisi.
  * Pour l'upload, vous pouvez envoyer plusieurs fichiers en même temps. Soit par glissez-déposez dans le cadre correspondant ou en cliquant sur **Parcourir...**

## Maps - Ordonner ##
  * La map en cours est exclue de l'affichage. A l'enregistrement, la liste triée sera après la map en cours.

## Maps - MatchSettings ##
  * Si la ligne est surlignée en jaune, c'est que le matchsettings a été modifié récemment.

## Maps - Create MatchSettings ##
  * Un message s'affiche si le nom du matchsettings existe déjà. Si vous enregistrez sans changer le nom, il sera écrasé.

## Plugins ##
  * Liste seulement les plugins compatible avec la version du serveur (ManiaPlanet ou TmForever) ainsi qu'avec le niveau admin.
  * Tout savoir sur les plugins : [PluginsFR](PluginsFR.md)

## Invités-Bannis ##
  * Vous pouvez ajouter un joueur présent sur le serveur (ou taper le login). Si vous voulez ajouter un joueur à une playlist spécifique, il faut d'abord charger la playlist.