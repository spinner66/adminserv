(function($){
	$.fn.displayServ = function(options){
		// Options
		var settings = {
			config: "config/servers.cfg.php",
			includes: "includes/",
			ressources: "ressources/",
			timeout: 3,
			refresh: 30,
			color: "",
		}
		$.extend(settings, options);
		settings.refresh = settings.refresh*1000;
		if(settings.color){
			settings.color = ' style="color: '+settings.color+';"';
		}
		
		// Refresh
		$(this).initialize(settings);
		setInterval(function(){
			$(this).initialize(settings);
		}, settings.refresh);
	};
})(jQuery);

(function($){
	$.fn.initialize = function(settings){
		var _this = $(this);
		
		// 1ère étape - Initialiser DisplayServ en créant le html
		$.getJSON(settings.includes+"ajax/initialize.php", {cfg: settings.config}, function(data){
			if(data != null){
				var out = '<ul class="ds-servers-list">';
					if(data.servers){
						for(var i = 0; i < data.servers; i++){
							out += '<li id="ds-server-'+i+'" class="ds-server loading">'
								+ '<table>'
									+ '<tr class="ds-header">'
										+ '<th class="first"'+settings.color+'>'+data.label.server+" n°"+(i+1)+'</th>'
										+ '<th class="middle"></th>'
										+ '<th class="last"'+settings.color+'>'+data.label.players+'</th>'
									+ '</tr>'
									+ '<tr class="ds-space"><td colspan="3"></td></tr>'
									+ '<tr class="ds-content">'
										+ '<td class="ds-part-left">'
											+ '<ul>'
												+ '<li>'+data.label.name+'</li>'
												+ '<li>'+data.label.login+'</li>'
												+ '<li>'+data.label.connect+'</li>'
												+ '<li>'+data.label.status+'</li>'
												+ '<li>'+data.label.gamemode+'</li>'
												+ '<li>'+data.label.currentmap+'</li>'
												+ '<li>'+data.label.players+'</li>'
											+ '</ul>'
										+ '</td>'
										+ '<td class="ds-part-middle">'
											+ '<ul>'
												+ '<li class="ds-server-name"></li>'
												+ '<li class="ds-server-login"></li>'
												+ '<li class="ds-server-connect"></li>'
												+ '<li class="ds-server-status"></li>'
												+ '<li class="ds-server-gamemode"></li>'
												+ '<li class="ds-server-currentmap"></li>'
												+ '<li class="ds-server-players-count"></li>'
											+ '</ul>'
										+ '</td>'
										+ '<td class="ds-part-right">'
											+ '<div class="ds-servers-players-list"></div>'
										+ '</td>'
									+ '</tr>'
								+ '</table>'
							+ '</li>';
						}
					}
				out += '</ul>';
				
				// Affichage
				$(_this).html(out);
				
				// 2ème étape - Récupérer les données serveur
				$.getJSON(settings.includes+"ajax/get_servers.php", {cfg: settings.config}, function(data){
					if(data != null){
						if(data.servers){
							for(var i = 0; i < data.servers.length; i++){
								var serverId = $("#ds-server-"+i);
								
								// Server infos
								serverId.find(".ds-server-name").html(data.servers[i].name);
								serverId.find(".ds-server-login").html(data.servers[i].serverlogin);
								serverId.find(".ds-server-connect").html(data.servers[i].version);
								serverId.find(".ds-server-connect").addClass(data.servers[i].version.toLowerCase());
								serverId.find(".ds-server-status").html(data.servers[i].status);
								serverId.find(".ds-server-gamemode").html(data.servers[i].gamemode);
								serverId.find(".ds-server-gamemode").addClass(data.servers[i].gamemode.toLowerCase());
								serverId.find(".ds-server-currentmap").html(data.servers[i].map.name+' <img src="./ressources/images/env/'+data.servers[i].map.env.toLowerCase()+'.png" alt="+data.servers[i].map+" />');
								serverId.find(".ds-server-players-count").html(data.players[i].count.current+" / "+data.players[i].count.max);
								
								// Players
								var playerListTable = "<table>";
									if(data.players[i].list.length > 0){
										$.each(data.players[i].list, function(i, n){
											playerListTable += '<td>'+n.name+'</td>'
											+ '<td>'+n.status+'</td>'
										});
									}
									else{
										playerListTable += '<td>'+data.players[i].list+'</td>';
									}
								playerListTable += "</table>";
								serverId.removeClass("loading");
								serverId.find(".ds-servers-players-list").html(playerListTable);
							}
						}
						else{
							if(data.error){
								var sid = 0;
								var serverId = $("#ds-server-"+sid);
								serverId.removeClass("loading");
								serverId.find(".ds-server-name").html(data.error);
							}
						}
					}
				});
			}
		});
	};
})(jQuery);