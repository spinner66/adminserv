**DisplayServ** permet d’afficher le statut de vos serveurs dédiés pour TrackMania Forever et ManiaPlanet.


# Requis #
  * Un serveur web (de préférence évitez les serveurs gratuits)
  * PHP version 5.3+
  * Un serveur dédié TrackMania Forever / ManiaPlanet en fonctionnement


# Installation #
Extraire le fichier "displayserv.zip" où bon vous semble sur un serveur web.

## Fichiers à inclure dans votre page : ##
```
<link rel="stylesheet" href="ressources/styles/displayserv.css" />
<script src="includes/js/jquery.js"></script>
<script src="includes/js/displayserv.js"></script>
```

## Exécuter le script : ##
```
<script>
    $(document).ready(function(){
        $('#displayserv').displayServ();
    });
</script>

<div id="displayserv"></div>
```

Par défaut, DisplayServ utilise le compte User pour se connecter au serveur avec le mot de passe par défaut : `User`.


# Options #
## Exemple de définition des paramètres : ##
```
<script>
    $(document).ready(function(){
        $('#displayserv').displayServ({
            refresh: 15,
            color: 'red',
            links: {
                spectate: false,
                addfavourite: true
            }
        });
    });
</script>
```

## Vous pouvez définir les paramètres suivants : ##
  * **config** : chemin vers le fichier de configuration des serveurs. Par défaut : `config/servers.cfg.php`
  * **includes** : chemin vers le dossier includes. Par défaut : `includes/`
  * **ressources** : chemin vers le dossier ressources. Par défaut : `ressources/`
  * **timeout** : temps de connexion maximal en seconde. Par défaut : `3`
  * **refresh** :  temps pour réactualiser le statut des serveurs en seconde. Par défaut : `30`. Minimum 10 secondes.
  * **color** : couleur d'affichage en hexa/rgb(a).
  * **links** : liens affichés au survol du serveur. Plusieurs paramètres possibles :
    * **join** : activer le lien pour accéder au serveur en tant que joueur. Valeur `true` ou `false`, par défaut `true`.
    * **spectate** : pour accéder au serveur en tant que spectateur. Valeur `true` ou `false`, par défaut `true`.
    * **addfavourite** : affiche un lien pour ajouter le serveur aux favoris. Valeur `true` ou `false`, par défaut `false`.