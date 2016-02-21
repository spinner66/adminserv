Plugins are extensions to add features to Adminserv.


# Install a plugin #
See all plugins: http://dl.zone-kev717.info/adminserv/plugins/
  * Unzip the plugin and place its contents into the "plugins" folder of Adminserv.
  * In the Configuration Extension, add the name of the plugin folder previously extracted.
```
public static $PLUGINS = array(
    'PluginName',
);                             
```
If you do not see the plugin in the list on the **Plugins** page,  it may not be compatible with your server and / or your admin level. Check the configuration in the `config.ini` file of the plugin.

# Create a new plugin #
To create a plugin, go to the `plugins` folder and duplicate `_newplugin`. Replace the values ​​in the `config.ini` file and folder name.
Then there are two PHP files:
  * **script.php**: this file is executed before the header of the site. This is where all plugin script will be placed.
  * **view.php**: this file is the display of the plugin executed after the header. This is where everything will be placed the HTML code.
You need to create resources (classes, js, css) then include these files.

# Global variables and constants #
By default, Adminserv provided some global variables and constants.

## Global variables are: ##
  * **$category**: parameter `c` of the url.
  * **$view**: parameter `view` of the url.
  * **$index**: parameter `i` of the url.
  * **$id**: `id` parameter of the url.
  * **$directory**: parameter `d` of the url.
Each of these variables are tested and return null (category, view, directory) or -1 (index, id) if the parameters are not present in the URL.
  * **$client**: XMLRPC client object for communicating with the dedicated server.

## Constants are: ##
  * **USER\_PAGE**: name of the current page
  * **USER\_THEME**: name of the current theme
  * **USER\_LANG**: code of current lang
  * **USER\_ADMINLEVEL**: admin level used
  * **USER\_MODE**: display mode `simple` or `detail`
  * **CURRENT\_PLUGIN**: name of the current plugin folder
  * **SERVER\_ID**: server number in relation to the order of the configuration
  * **SERVER\_NAME**: server name
  * **SERVER\_ADDR**: address of the server
  * **SERVER\_XMLRPC\_PORT**: xmlrpc port of the server
  * **SERVER\_MATCHSET**: matchsettings name
  * **SERVER\_ADMINLEVEL**: table containing all admin levels configuration
  * **SERVER\_VERSION\_NAME**: set name used by the `TmForever` or `Maniaplanet` server
  * **SERVER\_VERSION**: dedicated server version
  * **SERVER\_BUILD**: date of the dedicated server version
  * **SERVER\_LOGIN**: dedicated server login
  * **SERVER\_TITLE**: game title used on the server: TMCanyon, SMStorm, etc.. Is `null` if the server is connected on TmForever.
  * **SERVER\_PUBLISHED\_IP**: public IP address to connect to the server
  * **SERVER\_PORT**: the port used by the server
  * **SERVER\_P2P\_PORT**: P2P port used by the server
  * **IS\_SERVER**: value of GetSystemInfo()
  * **IS\_DEDICATED**: value of GetSystemInfo()
  * **IS\_RELAY**: determines if the server is a relay
  * **API\_VERSION**: date version of the API
  * **LINK\_PROTOCOL**: name of the protocol used in the access links `tmtp` or `maniaplanet`

You can view it with `AdminServ::debug();`