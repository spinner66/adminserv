(function($){
	$.fn.displayServ = function(){
		var _this = $(this);
		
		
		// 1ère étape - Initialiser DisplayServ en créant le html
		$.getJSON("includes/ajax/initialize.php", function(data){
			if(data != null){
				var out = '<ul class="ds-servers-list">';
					for(var i = 0; i < data.servers; i++){
						out += '<li id="ds-server-'+i+'" class="ds-server">'
							+ '<div class="ds-part-left">'
								+ '<div class="ds-header">'
									+ data.label.server+" n°"+(i+1)
								+ '</div>'
								+ '<div class="ds-content">'
									+ '<ul>'
										+ '<li>'+data.label.name+'</li>'
										+ '<li>'+data.label.login+'</li>'
										+ '<li>'+data.label.connect+'</li>'
										+ '<li>'+data.label.status+'</li>'
										+ '<li>'+data.label.gamemode+'</li>'
										+ '<li>'+data.label.currentmap+'</li>'
										+ '<li>'+data.label.players+'</li>'
									+ '</ul>'
								+ '</div>'
							+ '</div>'
							+ '<div class="ds-part-middle">'
								+ '<div class="ds-header"></div>'
								+ '<div class="ds-content">'
									+ '<ul>'
										+ '<li class="ds-server-name"></li>'
										+ '<li class="ds-server-login"></li>'
										+ '<li class="ds-server-connect"></li>'
										+ '<li class="ds-server-status"></li>'
										+ '<li class="ds-server-gamemode"></li>'
										+ '<li class="ds-server-currentmap"></li>'
										+ '<li class="ds-server-players-count"></li>'
									+ '</ul>'
								+ '</div>'
							+ '</div>'
							+ '<div class="ds-part-right">'
								+ '<div class="ds-header">'
									+ data.label.players
								+ '</div>'
								+ '<div class="ds-content">'
									+ '<div class="ds-servers-players-list"></div>'
								+ '</div>'
							+ '</div>'
						+ '</li>';
					}
				out += '</ul>';
				
				// Affichage
				$(_this).html(out);
				
				// 2ème étape - Récupérer les données serveur
				$.getJSON("includes/ajax/get_servers.php", function(data){
					if(data != null){
						if(data.servers.length > 0){
							for(var i = 0; i < data.servers.length; i++){
								var serverId = $("#ds-server-"+i);
								
								// Server infos
								serverId.find(".ds-server-name").html(data.servers[i].name);
								serverId.find(".ds-server-login").html(data.servers[i].serverlogin);
								serverId.find(".ds-server-connect").html(data.servers[i].version);
								serverId.find(".ds-server-connect").addClass(data.servers[i].version.toLowerCase());
								serverId.find(".ds-server-status").html(data.servers[i].status);
								serverId.find(".ds-server-gamemode").html(data.servers[i].gamemode);
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
										// aucun joueur
									}
								playerListTable += "</table>";
								serverId.find(".ds-servers-players-list").html(playerListTable);
							}
						}
					}
				});
			}
		});
	};
})(jQuery);