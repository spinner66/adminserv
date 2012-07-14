(function($){
	$.fn.displayServ = function(options){
		var selector = $(this);
		
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
		selector.initialize(settings);
		setInterval(function(){
			selector.initialize(settings);
		}, settings.refresh);
	};
})(jQuery);

(function($){
	$.fn.initialize = function(settings){
		var selector = $(this);
		
		// 1ère étape - Initialiser DisplayServ en créant le html
		$.getJSON(settings.includes+"ajax/ds_initialize.php", {cfg: settings.config}, function(data){
			if(data != null){
				var out = '<ul class="ds-servers-list loading">';
					if(data.servers){
						for(var i = 0; i < data.servers; i++){
							out += '<li id="ds-server-'+i+'" class="ds-server">'
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
												+ '<li class="ds-server-protocol"></li>'
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
								+ '<div class="ds-server-join-wrap">'
										+ '<ul>'
											+ '<li class="ds-server-favourite"><a href="">'+data.label.addfavourite+'</a></li>'
											+ '<li class="ds-server-join"><a href="">'+data.label.accessserver+'</a></li>'
										+ '</ul>'
								+ '</div>'
							+ '</li>';
						}
					}
				out += '</ul>';
				
				// Affichage
				selector.find(".ds-servers-list").remove();
				selector.html(out);
				
				// Calcul de la taille max
				var maxsize = selector.find(".ds-servers-list").width();
				if(maxsize < 380){
					selector.find(".ds-servers-list").addClass("max-width-380");
				}
				else if(maxsize < 580){
					selector.find(".ds-servers-list").addClass("max-width-580");
				}
				
				// 2ème étape - Récupérer les données serveur
				$.getJSON(settings.includes+"ajax/ds_getservers.php", {cfg: settings.config, rsc: settings.ressources}, function(data){
					if(data != null){
						if(data.servers){
							for(var i = 0; i < data.servers.length; i++){
								var serverId = $("#ds-server-"+i);
								
								// Server infos
								serverId.find(".ds-server-name").html(data.servers[i].name);
								serverId.find(".ds-server-login").html(data.servers[i].serverlogin);
								serverId.find(".ds-server-connect").html(data.servers[i].version.name);
								serverId.find(".ds-server-connect").addClass(data.servers[i].version.name.toLowerCase());
								serverId.find(".ds-server-status").html(data.servers[i].status);
								serverId.find(".ds-server-gamemode").html(data.servers[i].gamemode);
								serverId.find(".ds-server-gamemode").addClass(data.servers[i].gamemode.toLowerCase());
								var hasEnvImg = "";
								if(data.servers[i].map.env.filename != null){
									var hasEnvImg = ' <img src="'+data.servers[i].map.env.filename+'" alt="('+data.servers[i].map.env.name+')" title="'+data.servers[i].map.env.name+'" />';
								}
								serverId.find(".ds-server-currentmap").html(data.servers[i].map.name + hasEnvImg);
								serverId.find(".ds-server-players-count").html(data.players[i].count.current+" / "+data.players[i].count.max);
								
								// Join
								serverId.find(".ds-server-join a").attr("href", data.servers[i].version.protocol+"://#join="+data.servers[i].serverlogin);
								serverId.find(".ds-server-favourite a").attr("href", data.servers[i].version.protocol+"://#addfavourite="+data.servers[i].serverlogin);
								
								// Players
								var playerListTable = "<table>";
									if(data.players[i].count.current > 0){
										$.each(data.players[i].list, function(i, n){
											var teamSpan = "";
											if(n.gamemode == "Team"){
												teamSpan = '<span class="team_'+n.teamId+'" title="'+n.teamName+'"></span>';
											}
											playerListTable += '<td>'+teamSpan+n.name+'</td>'
											+ '<td>'+n.status+'</td>'
										});
									}
									else{
										playerListTable += '<td class="no-player" colspan="2">'+data.players[i].list+'</td>';
									}
								playerListTable += "</table>";
								serverId.find(".ds-servers-players-list").html(playerListTable);
							}
						}
						else{
							if(data.error){
								var sid = 0;
								var serverId = $("#ds-server-"+sid);
								serverId.find(".ds-server-name").html(data.error);
							}
						}
						
						selector.find(".ds-servers-list").removeClass("loading");
					}
				});
			}
		});
	};
})(jQuery);