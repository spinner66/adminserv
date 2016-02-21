**DisplayServ** displays the status of your dedicated servers for TrackMania Forever and ManiaPlanet.


# Requirements #
  * A web server (preferably avoid free servers)
  * PHP version 5.3+
  * A dedicated server TrackMania Forever / ManiaPlanet in use


# Installation #
Extract the file "displayserv.zip" where you want on a web server.

## Files to include on your page: ##
```
<link rel="stylesheet" href="ressources/styles/displayserv.css" />
<script src="includes/js/jquery.js"></script>
<script src="includes/js/displayserv.js"></script>
```

## Execute the script: ##
```
<script>
    $(document).ready(function(){
        $('#displayserv').displayServ();
    });
</script>

<div id="displayserv"></div>
```

By default, DisplayServ uses the User account to connect to the server with the default password: `User`.


# Options #
## Example of setting parameters: ##
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

## You can define the next parameters: ##
  * **config**: path of the configuration file of your servers. By default: `config/servers.cfg.php`
  * **includes**: path of the includes folder. By default: `includes/`
  * **ressources**: path of the ressouces folder. By default: `ressources/`
  * **timeout**: limit time to try to connect in seconds. By default: `3`
  * **refresh**: time before a refresh of servers info in seconds. By default: `30`. Minimum 10 seconds.
  * **color**: display color in hexa/rgb(a).
  * **links**: links posted in overview of the server. Several possible parameters:
    * **join**: activate the link to access the server as a player. Value `true` or `false`, by default `true`.
    * **spectate**: activate the link to access the server as a spectator. Value `true` or `false`, by default `true`.
    * **addfavourite**: show a link to add the server to your favorites. Value `true` or `false`, by default `false`.