/**
* Récupère la liste des niveaux admin suivant le serveur sélectionné
*/
function getServerAdminLevel(){
	var serverName = $("select#as_server").val();
	var adminLevelList = "";
	
	$.getJSON("includes/ajax/get_server_adminlevel.php", {srv: serverName}, function(response){
		if(response != null){
			$.each(response.levels, function(i, n){
				if(response.last != null && response.last == n){ var selected = ' selected="selected"'; }
				else{ var selected = ""; }
				adminLevelList += '<option value="'+n+'"'+selected+'>'+n+'</option>';
			});
			
			// On met à jour la liste
			$("select#as_adminlevel").html(adminLevelList);
		}
		else{
			error("Aucun niveau admin configuré pour ce serveur.");
		}
	});
}


/**
* Administration rapide (restart, next, endround)
*
* @param string cmd -> Le nom de la méthode à utiliser
*/
function speedAdmin(cmd){
	$.post("includes/ajax/speed_admin.php", {cmd: cmd}, function(response){
		if(response != "null"){
			error("Error: "+response);
		}
		setTimeout(function(){
			if( $("body").hasClass("section-index") ){
				getCurrentServerInfo();
			}
			else if( $("body").hasClass("section-maps") ){
				getMapList();
			}
			$(".speed-admin a.locked").removeClass("locked");
		}, 2000);
	});
}


/**
* Récupère les informations du serveur actuel (map, serveur, stats, joueurs)
*/
function getCurrentServerInfo(){
	$.getJSON("includes/ajax/get_current_serverinfo.php", function(data){
		if(data != null){
			// Map
			if(data.map != null){
				$("#map_name").html(data.map.name);
				$("#map_author").html(data.map.author);
				$("#map_enviro").html(data.map.enviro+'<img src="'+data.cfg.path_rsc+'images/env/'+data.map.enviro.toLowerCase()+'.png" alt="" />');
				$("#map_uid").html(data.map.uid);
				$("#map_gamemode").html(data.srv.game_mode);
				$("#map_gamemode").attr("class", "");
				$("#map_gamemode").addClass("value");
				$("#map_gamemode").addClass( data.srv.game_mode.toLowerCase() );
				if(data.map.thumb != ""){
					$("#map_thumbnail").html('<img src="data:image/jpeg;base64,'+data.map.thumb+'" alt="No thumbnail" />');
				}
			}
			
			// Server
			if(data.srv != null){
				$("#server_name").html(data.srv.name);
				$("#server_status").html(data.srv.status);
			}
			
			// Stats
			if(data.net != null){
				$("#network_uptime").html(data.net.uptime);
				$("#network_nbrconnection").html(data.net.nbrconnection);
				$("#network_meanconnectiontime").html(data.net.meanconnectiontime);
				$("#network_meannbrplayer").html(data.net.meannbrplayer);
				$("#network_recvnetrate").html(data.net.recvnetrate);
				$("#network_sendnetrate").html(data.net.sendnetrate);
				$("#network_totalreceivingsize").html(data.net.totalreceivingsize);
				$("#network_totalsendingsize").html(data.net.totalsendingsize);
			}
			
			// Players
			if(data.ply != null && !$("#playerlist tbody input").attr("checked") ){
				var out = null;
				
				// Création du tableau
				if( typeof(data.ply) == "object" && data.ply.length > 0 ){
					$.each(data.ply, function(i, player){
						out += '<tr class="'; if(i%2){ out += 'even'; }else{ out += 'odd'; } out += '">'
							+'<td class="imgleft"><img src="'+data.cfg.path_rsc+'images/16/solo.png" alt="" />'+player.NickName+'</td>'
							+'<td>'+player.Login+'</td>'
							+'<td>'+player.PlayerStatus+'</td>'
							+'<td class="checkbox"><input type="checkbox" name="player[]" value="'+player.Login+'" /></td>'
						+'</tr>';
					});
					
					if( $("input#checkAll").attr("disabled") ){
						$("input#checkAll").attr("disabled", false);
					}
				}
				else{
					if( !$("input#checkAll").attr("disabled") ){
						$("input#checkAll").attr("disabled", true);
					}
					out += '<tr class="no-line"><td class="center" colspan="4">'+data.ply+'</td></tr>';
				}
				
				// HTML
				$("#playerlist table tbody").html(out);
			}
		}
	});
}


/**
* Récupère et affiche le nom du serveur et son commentaire
*
* @param string str  -> La chaine de caractère à transformer en HTML
* @param string dest -> Selecteur Jquery pour afficher les données
*/
function previewSrvOpts(str, dest){
	$.getJSON("includes/ajax/preview_srvopts.php", {t: str}, function(data){
		if(data != null){
			$(dest).html('['+data.str+']');
		}
	});
}


/**
* Récupère la configuration du gameMode sélectionné
*/
function getCurrentGameModeConfig(){
	var gameMode = $("select#NextGameMode option:selected").text();
	var selector = $("#gameMode-"+gameMode.toLowerCase() );
	
	// Fermeture de tous les modes par défaut
	$.each( $(".section-gameinfos .content fieldset"), function(i, n){
		if( !$(this).hasClass("gameinfos_general") ){
			if( $(this).attr("hidden") ){
				$(this).hide();
			}
			else{
				$(this).hide();
				$(this).attr("hidden", true);
			}
		}
	});
	
	// Affichage du mode de jeu sélectionné
	if( selector.attr("hidden") ){
		selector.slideDown("fast");
		selector.removeAttr("hidden");
	}
}


/**
* Affiche la valeur seconde -> minute
*
* @param int sec -> La valeur en seconde
*/
function secToMin(sec){
	if(sec == "" || sec == undefined || isNaN(sec) ){
		sec = 0;
	}
	return round( (parseInt(sec) / 60), 1);
}


/**
* Math.round avec précision
*
* @param int value     -> Valeur à arrondir
* @param int precision -> Nombre de caractère après la virgule
*/
function round(value, precision){
	power = Math.pow(10, precision);
	return (Math.ceil(value * power)) / power;
}


/**
* Récupère les lignes du chat du serveur
*
* @param bool hideServerLines -> Afficher ou non les lignes provenant d'un gestionnaire de serveur
* @return string html
*/
function getChatServerLines(hideServerLines){
	$.getJSON("includes/ajax/get_chatserverlines.php", {s: hideServerLines}, function(data){
		if(data != null){
			$("#chat").html(data);
		}
	});
}


/**
* Ajoute une ligne (pseudo + message) dans le chat du serveur
*/
function addChatServerLine(){
	var nickname = $("#chatNickname").val();
	if( nickname == $("#chatNickname").attr("data-default-value") ){
		nickname = "";
	}
	var color = $("#chatColor").val();
	var message = $("#chatMessage").val();
	var destination = $("#chatDestination").val();
	var hideServerLines = $("#checkServerLines").attr("data-val");
	
	$.post("includes/ajax/add_chatserverline.php", {nic: nickname, clr: color, msg: message, dst: destination}, function(response){
		if(response != null){
			getChatServerLines(hideServerLines);
			$("#chatMessage").val("");
		}
	});
}


/**
* Initialisation de l'uploader Ajax
*/
function initializeUploader(){
	uploader = new qq.FileUploader({
		element: $("#formUpload")[0],
		action: 'includes/ajax/upload.php',
		maxConnections: 2,
		params: {
			path: getPath(),
			type: $(".transferMode li.selected input").val(),
			mset: ( $("#SaveCurrentMatchSettings").attr("checked") ) ? true : false,
			gtlm: ( $("#GotoListMaps").attr("checked") ) ? true : false
		},
		template:
		'<div class="qq-uploader">' + 
			'<div class="qq-upload-drop-area"><span>'+ t('Drop files here to upload') +'</span></div>' +
			'<div class="qq-upload-button">'+ t('Upload a file') +'</div>' +
			'<ul class="qq-upload-list"></ul>' + 
		'</div>',
		fileTemplate:
		'<li>' +
			'<span class="qq-upload-file"></span>' +
			'<span class="qq-upload-spinner"><span class="qq-upload-bar"></span></span>' +
			'<span class="qq-upload-size"></span>' +
			'<a class="qq-upload-cancel" href="./">'+ t('Cancel') +'</a>' +
			'<span class="qq-upload-failed-text">'+ t('Failed') +'</span>' +
		'</li>',
		onProgress: function(id, fileName, loaded, total){
			window.onbeforeunload = function(){
				return "L'upload n'est pas terminé";
			}
			$.each( $(".qq-upload-list li"), function(key, value){
				// Récupèration des données
				var text = $(this).children(".qq-upload-size").text();
				var newtext = t(text);
				var lastpos = text.indexOf("%");
				var pourcent = text.substring(0, lastpos);
				
				// Modification des données
				$(this).children(".qq-upload-size").text(newtext);
				$(this).children(".qq-upload-spinner").children(".qq-upload-bar").css("width", pourcent+"px");
			});
		},
		onComplete: function(id, fileName, responseJSON){
			window.onbeforeunload = function(){}
			if(responseJSON.success == true){
				if(uploader._options.params.gtlm){
					location.href = "?p=maps";
				}
			}
		},
		onCancel: function(id, fileName){
			window.onbeforeunload = function(){}
		},
		messages: {
			typeError: t("{file} has invalid extension. Only {extensions} are allowed."),
			sizeError: t("{file} is too large, maximum file size is {sizeLimit}."),
			minSizeError: t("{file} is too small, minimum file size is {minSizeLimit}."),
			emptyError: t("{file} is empty, please select files again without it."),
			onLeave: t("The files are being uploaded, if you leave now the upload will be cancelled.")
		},
		showMessage: function(message){
			error(message);
		}
	});
}

function t(text){
	return text;
}


/**
* Affichage du texte d'erreur
*/
function error(text, hide){
	$("#error").fadeIn("fast");
	if( $("#error").attr("hidden") ){
		$("#error").removeAttr("hidden");
	}
	$("#error").text(text);
	
	if(hide){
		setTimeout(function(){
			$("#error").attr("hidden", true);
			$("#error").fadeOut("fast");
		}, 4000);
	}
}


/**
* Affichage du texte d'erreur
*/
function info(text, hide){
	$("#info").fadeIn("fast");
	if( $("#info").attr("hidden") ){
		$("#info").removeAttr("hidden");
	}
	$("#info").text(text);
	
	if(hide){
		setTimeout(function(){
			$("#info").attr("hidden", true);
			$("#info").fadeOut("fast");
		}, 4000);
	}
}

function getPath(){
	return $.trim( $(".path").text() );
}


/**
* Coche toutes les checkbox d'un selecteur
*
* @param string selector -> Le selecteur de la liste des checkbox à cocher
*/
(function($){
	$.fn.checkAll = function(isChecked){
		var lineSelector = $(this).find("tbody tr");
		var checkboxSelector = $(this).find("tbody td.checkbox input[type=checkbox]");
		if(isChecked){
			checkboxSelector.attr("checked", true);
			lineSelector.addClass("selected");
		}
		else{
			checkboxSelector.attr("checked", false);
			lineSelector.removeClass("selected");
		}
	};
})(jQuery);


/**
* Met à jour le nombre de fichiers sélectionnés
*/
(function($){
	$.fn.updateNbSelectedLines = function(){
		// On récupère le nombre d'élements sélectionnés;
		var nb = $(this).find("tbody tr.selected").length;
		
		// Mise à jour
		if(nb > 0){
			$(this).find(".selected-files-label").removeClass("locked");
		}else{
			$(this).find(".selected-files-label").addClass("locked");
		}
		$(this).find(".selected-files-count").text("("+nb+")");
	};
})(jQuery);



/**
* Récupère la liste des maps du serveur
*/
function getMapList(){
	$.getJSON("includes/ajax/get_maplist.php", function(data){
		if(data != null){
			if(data.lst != null && !$("#maplist tbody .checkbox input").attr("checked") ){
				var out = null;
				
				// Création du tableau
				if( typeof(data.lst) == "object" && data.lst.length > 0 ){
					$.each(data.lst, function(i, map){
						out += '<tr class="'; if(i%2){ out += 'even'; }else{ out += 'odd'; } if(data.cid == i){ out += ' current'; } out += '">'
							+'<td class="imgleft"><img src="'+data.cfg.path_rsc+'images/16/map.png" alt="" />'+map.Name+'</td>'
							+'<td class="imgcenter"><img src="'+data.cfg.path_rsc+'images/env/'+map.Environnement.toLowerCase()+'.png" alt="" />'+map.Environnement+'</td>'
							+'<td>'+map.Author+'</td>'
							+'<td class="checkbox">'; if(data.cid != i){ out += '<input type="checkbox" name="map[]" value="'+map.FileName+'" />'; } out += '</td>'
						+'</tr>';
					});
					
					if( $("input#checkAll").attr("disabled") ){
						$("input#checkAll").attr("disabled", false);
					}
				}
				else{
					if( !$("input#checkAll").attr("disabled") ){
						$("input#checkAll").attr("disabled", true);
					}
					out += '<tr class="no-line"><td class="center" colspan="4">'+data.lst+'</td></tr>';
				}
				
				// HTML
				$("#maplist table tbody").html(out);
			}
		}
	});
}


/**
* Récupère la liste des fichiers map pour les renommer
*/
(function($){
	$.fn.getMapRenameList = function(){
		var out = "";
		var list = $(this);
		if( list.length > 0 ){
			out += '<ul>';
			$.each(list, function(i, n){
				if(n.value.length > 36){
					var title = ' title="'+n.value+'"';
				}else{
					var title = "";
				}
				out += '<li>'
					+ '<span class="rename-map-name"'+title+'>'+n.value+'</span>'
					+ '<span class="rename-map-arrow">&nbsp;</span>'
					+ '<input class="text width3" type="text" name="renameMapList[]" value="'+n.value+'" />'
				+ '</li>';
			});
			out += '</ul>';
		}
		
		// HTML
		out += '<div class="form-input-submit">'
			+'<input class="button dark" type="submit" id="renameMapValid" name="renameMapValid" value="Enregistrer" />'+"\n"
			+ '<input class="button dark" type="button" id="renameMapCancel" name="renameMapCancel" value="Annuler" />'
		+ '</div>';
		$("#form-rename-map").html(out);
	};
})(jQuery);


/**
* Récupère la liste des fichiers map pour les renommer
*/
(function($){
	$.fn.getMoveMapList = function(){
		var out = "";
		var list = $(this);
		var nb = list.length;
		if(nb > 1){
			var nbName = nb + ' maps';
		}else{
			var nbName = '1 map';
		}
		
		out += '<p>Déplacer '+nbName+' dans le dossier :</p>'
		+ '<select name="moveDirectoryList" id="moveDirectoryList">'
			+ '<option value=".">Racine</option>'
			+ getMapsDirectoryOptionList()
		+ '</select>';
		
		out += '<div class="form-input-submit">'
			+'<input class="button dark" type="submit" id="moveMapValid" name="moveMapValid" value="Enregistrer" />'+"\n"
			+ '<input class="button dark" type="button" id="moveMapCancel" name="moveMapCancel" value="Annuler" />'
		+ '</div>';
		
		// HTML
		$("#form-move-map").html(out);
	};
})(jQuery);


function getMapsDirectoryOptionList(){
	var out = "";
	
	$.getJSON("includes/ajax/get_directory_list.php", {path: getPath()}, function(data){
		if(data != null){
			alert("ok");
			$.each(data, function(dirPath, dirName){
				out += '<option value="'+dirPath+'">'+dirName+'</option>';
			});
		}
	});
	
	return out;
}