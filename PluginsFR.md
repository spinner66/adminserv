Les plugins sont des extensions permettant d'ajouter des fonctionnalités pour AdminServ.

# Installer un plugin #
Voir tous les plugins : http://dl.zone-kev717.info/adminserv/plugins/
  * Dézippez le plugin et placez son contenu dans le dossier "plugins" d'AdminServ.
  * Dans la configuration Extension, ajoutez le nom du dossier du plugin précédemment ajouté.
```
public static $PLUGINS = array(
    'PluginName',
);                             
```
Si vous ne voyez pas le plugin dans la liste sur la page **Plugins**, c'est qu'il n'est peut-être pas compatible avec votre serveur et/ou votre niveau admin. Vérifiez la configuration dans le fichier `config.ini` du plugin.

# Créer un nouveau plugin #
Pour créer son plugin, allez dans le dossier `plugins` et dupliquez le dossier `_newplugin`. Remplacez les valeurs dans le fichier `config.ini` ainsi que le nom du dossier.
Il y a ensuite 2 fichiers PHP :
  * **script.php** : ce fichier est exécuté avant le header du site. C'est là que tout le script du plugin sera placé.
  * **view.php** : ce fichier est l'affichage du plugin exécuté après le header. C'est là que sera placé tout le code html.
A vous de créer les ressources (classes, js, css) puis de les inclure dans ces fichiers.

# Variables globales et constantes #
Par défaut, AdminServ fourni quelques variables globales et constantes.

## Les variables globales sont : ##
  * **$category** : paramètre `c` de l'url.
  * **$view** : paramètre `view` de l'url.
  * **$index** : paramètre `i` de l'url.
  * **$id** : paramètre `id` de l'url.
  * **$directory** : paramètre `d` de l'url.
Chacune de ces variables sont testées et retournent null (category, view, directory) ou -1 (index, id) si les paramètres ne sont pas présents dans l'url.
  * **$client** : objet du client XMLRPC permettant de communiquer avec le serveur dédié.

## Les constantes sont : ##
  * **USER\_PAGE** : nom de la page actuelle
  * **USER\_THEME** : nom du thème actuel
  * **USER\_LANG** : code lang actuel
  * **USER\_ADMINLEVEL** : niveau admin utilisé
  * **USER\_MODE** : mode d'affichage `simple` ou `detail`
  * **CURRENT\_PLUGIN** : nom du dossier plugin actuel
  * **SERVER\_ID** : numéro du serveur par rapport à l'ordre de la configuration
  * **SERVER\_NAME** : nom du serveur de la config
  * **SERVER\_ADDR** : adresse du serveur de la config
  * **SERVER\_XMLRPC\_PORT** : port xmlrpc du serveur de la config
  * **SERVER\_MATCHSET** : nom du matchsettings de la configuration
  * **SERVER\_ADMINLEVEL** : tableau contenant toute la configuration des niveaux admins (utiliser unserialize pour exploiter la chaîne de caractère)
  * **SERVER\_VERSION\_NAME** : nom du jeu utilisé par le serveur `TmForever` ou `ManiaPlanet`
  * **SERVER\_VERSION** : version du serveur dédié
  * **SERVER\_BUILD** : date de la version du serveur dédié
  * **SERVER\_LOGIN** : login du serveur dédié
  * **SERVER\_TITLE** : Titre du jeu utilisé sur le serveur : TMCanyon, SMStorm, etc. Vaut `null` si le serveur est connecté sur TmForever.
  * **SERVER\_PUBLISHED\_IP** : adresse IP publique pour se connecter au serveur
  * **SERVER\_PORT** : port utilisé par le serveur
  * **SERVER\_P2P\_PORT** : port pour le P2P utilisé par le serveur
  * **IS\_SERVER** : Valeur de GetSystemInfo()
  * **IS\_DEDICATED** : Valeur de GetSystemInfo()
  * **IS\_RELAY** : détermine si le serveur est un relai
  * **API\_VERSION** : date de la version de l'API
  * **LINK\_PROTOCOL** : nom du protocole utilisé dans les liens d'accès `tmtp` ou `maniaplanet`

Vous pouvez tout visualiser avec `AdminServ::debug();`