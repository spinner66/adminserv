$(document).ready(function(){
	
	/**
	* Effet de transition CSS
	*/
	$("#theme, #lang").hover(function(){
		$(this).css("height", $(this).children("ul").height()+"px");
	}, function(){
		$(this).css("height", "12px");
	});
	
	
	/**
	* Front
	*/
	if( $("body").hasClass("front") ){
		/**
		* Serveurs
		*/
		if( $("body").hasClass("section-servers") ){
			// Clic sur les lignes
			$("#serverList tr").live("click", function(){
				if( !$(this).hasClass("no-line") ){
					// Si la ligne est déjà sélectionnée, on l'enlève
					if( $(this).hasClass("selected") ){
						$("#serverList tr").removeClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", false);
					}
					// Sinon, on l'ajoute
					else{
						$("#serverList tr").removeClass("selected");
						$(this).addClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", true);
					}
				}
				
				// Mise à jour du nb de lignes sélectionnées
				$(".cadre").updateNbSelectedLines();
			});
		}
		else if( $("body").hasClass("section-addserver") ){
			$("#addServerAdmLvlSA, #addServerAdmLvlADM, #addServerAdmLvlUSR").blur(function(){
				if( $(this).val() == "" ){
					$(this).val("all");
				}
			});
		}
		else if( $("body").hasClass("section-index") ){
			// Adminlevel
			getServerAdminLevel();
			$("select#as_server").change(function(){
				getServerAdminLevel();
			});
			
			// Connexion
			$(document).keypress(function(event){
				if(event.keyCode == 13){
					$("#connexion form").submit();
				}
			});
		}
	}
	/**
	* Not front
	*/
	else{
		/**
		* Scroll doux
		*/
		$("a[href*='#']").click(function(){
			if(location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname){
				var target = $(this.hash);
				target = target.length && target || $('[name=' + this.hash.slice(1) +']');
				if(target.length){
					var targetOffset = target.offset().top;
					$("html, body").animate({scrollTop: targetOffset}, 500);
					return false;
				}
			}
		});
		
		
		/**
		* Nouveau dossier
		*/
		$("a#newfolder").click(function(){
			if( $("#form-new-folder").attr("hidden") ){
				slideDownNewFolderForm();
			}
			else{
				slideUpNewFolderForm();
			}
			
			return false;
		});
		$("#newFolderName").keypress(function(event){
			if(event.keyCode == 13){
				if( $(this).val() != "" ){
					$("form#createFolderForm").submit();
				}
				else{
					slideUpNewFolderForm();
				}
			}
		});
		$("#newFolderValid").click(function(){
			if( $("#newFolderName").val() != "" ){
				return true;
			}
			else{
				slideUpNewFolderForm();
				return false;
			}
		});
		
		
		/**
		* Option du dossier
		*/
		$(".path").scrollLeft(1000);
		$(".folders .option-folder-list h3").click(function(){
			var selector = $(this).parent().children("ul");
			if( selector.attr("hidden") ){
				selector.slideDown("fast");
				selector.removeAttr("hidden");
				$(this).children("span").removeClass("arrow-down");
				$(this).children("span").addClass("arrow-up");
			}
			else{
				selector.slideUp("fast");
				selector.attr("hidden", true);
				$(this).children("span").removeClass("arrow-up");
				$(this).children("span").addClass("arrow-down");
			}
		});
		$("#renameFolder").click(function(){
			$("#optionFolderHiddenFieldAction").val("rename");
			getRenameFolderForm();
			return false;
		});
		$("#moveFolder").click(function(){
			$("#optionFolderHiddenFieldAction").val("move");
			getMoveFolderForm();
			return false;
		});
		$("#deleteFolder").click(function(){
			if( confirm( $(this).data("confirm-text") ) ){
				$("#optionFolderHiddenFieldAction").val("delete");
				$("#optionFolderForm").submit();
			}
			return false;
		});
		
		
		/**
		* SpeedAdmin
		*/
		$(".speed-admin a").click(function(){
			if( !$(this).hasClass("locked") ){
				$(this).addClass("locked");
				speedAdmin( $(this).text() );
			}
			return false;
		});
		
		
		/**
		* SwitchServer
		*/
		$("#switchServerList").change(function(){
			// Si on est pas sur la page index
			var params = location.search;
			if(params != ""){
				// Si il y a qu'un seul paramètre
				if( params.indexOf("&") == -1 ){
					var page = params.substring(3);
				}
				else{
					var page = params.split("&")[0].substring(3);
				}
			}
			
			// Si il y a une page, on prend en compte le paramètre
			if(page){
				location.href = "?p="+page+"&switch="+ $("#switchServerList option:selected").val();
			}else{
				location.href = "?switch="+ $("#switchServerList option:selected").val();
			}
		});
		
		
		/**
		* Général
		*/
		if( $("body").hasClass("section-index") ){
			// Infos serveur
			setInterval(function(){
				getCurrentServerInfo(getMode(), getCurrentSort());
			}, 10000);
			
			// Checkbox
			$("input#checkAll").click(function(){
				$("#playerlist").checkAll( $(this).attr("checked") );
				
				// Mise à jour du nb de lignes sélectionnées
				$(".cadre.right").updateNbSelectedLines();
			});
			
			// Clic sur les lignes
			$("#playerlist tr").live("click", function(){
				if( !$(this).hasClass("no-line") ){
					// Si la ligne est déjà sélectionnée, on l'enlève
					if( $(this).hasClass("selected") ){
						$(this).removeClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", false);
					}
					// Sinon, on l'ajoute
					else{
						$(this).addClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", true);
					}
					
					// Mise à jour du nb de lignes sélectionnées
					$(".cadre.right").updateNbSelectedLines();
					$(".cadre.right").updateCheckAll( $("input#checkAll") );
				}
			});
			
			// Mode détail
			$("#detailMode").click(function(){
				setGeneralDetailMode();
				return false;
			});
			
			// Tris
			$("#playerlist table th a").click(function(){
				var sort = $(this).attr("href");
				sort = sort.split("=")[1];
				$("#playerlist").addClass("loading");
				getCurrentServerInfo(getMode(), sort);
				return false;
			});
		}
		/**
		* Server Options
		*/
		else if( $("body").hasClass("section-srvopts") ){
			// ServerName
			$("input#ServerName").keyup(function(event){
				var key = event.keyCode;
				if(key != 13 && key != 37 && key != 39){
					getPreviewSrvOpts($(this).val(), "#serverNameHtml");
				}
			});
			// ServerComment
			$("textarea#ServerComment").keyup(function(event){
				var key = event.keyCode;
				if(key != 37 && key != 39){
					getPreviewSrvOpts("$i"+ $(this).val(), "#serverCommentHtml");
				}
			});
		}
		/**
		* Game Infos
		*/
		else if( $("body").hasClass("section-gameinfos") || $("body").hasClass("section-maps-creatematchset") ){
			// GameMode
			getCurrentGameModeConfig();
			$("select#NextGameMode").change(function(){
				getCurrentGameModeConfig();
			});
			
			// FinishTimeout
			$("select#NextFinishTimeout").change(function(){
				if( $(this).val() == "more" ){
					$(this).hide();
					$("#NextFinishTimeoutValue").fadeIn("fast");
					$("#NextFinishTimeoutValue").removeAttr("hidden");
					$("#NextFinishTimeoutValue").val("15");
					var defaultValueSelector = $("#NextFinishTimeoutValue").parent("td").parent("tr").children("td.preview").children("a.returnDefaultValue");
					defaultValueSelector.fadeIn("fast");
					defaultValueSelector.parent("td").removeAttr("hidden");
				}
			});
			
			// ForceShowAllOpponents
			$("select#NextForceShowAllOpponents").change(function(){
				if( $(this).val() == "more" ){
					$(this).hide();
					$("#NextForceShowAllOpponentsValue").fadeIn("fast");
					$("#NextForceShowAllOpponentsValue").removeAttr("hidden");
					$("#NextForceShowAllOpponentsValue").val("2");
					var defaultValueSelector = $("#NextForceShowAllOpponentsValue").parent("td").parent("tr").children("td.preview").children("a.returnDefaultValue");
					defaultValueSelector.fadeIn("fast");
					defaultValueSelector.parent("td").removeAttr("hidden");
				}
			});
			
			// Revenir à la valeur par défaut
			$("a.returnDefaultValue").click(function(){
				$(this).fadeOut();
				$(this).attr("hidden", true);
				var selectValueSelector = $(this).parent("td").parent("tr").children("td.next").children("select");
				var inputValueSelector = $(this).parent("td").parent("tr").children("td.next").children("input");
				inputValueSelector.hide();
				inputValueSelector.attr("hidden", true);
				inputValueSelector.val("");
				selectValueSelector.fadeIn("fast");
				selectValueSelector.children("option:first").removeAttr("selected");
				selectValueSelector.children("option:first").select();
				selectValueSelector.removeAttr("hidden");
				return false;
			});
			
			// Affichage sec -> min
			$("#NextTimeAttackLimit, #NextLapsTimeLimit, #hotSeatTimeLimit").click(function(){
				var sec = $(this).val();
				var min = secToMin(sec);
				$(this).parent("td").parent("tr").children("td.preview").html("["+min+" min]");
			});
			$("#NextTimeAttackLimit, #NextLapsTimeLimit, #hotSeatTimeLimit").blur(function(){
				$(this).parent("td").parent("tr").children("td.preview").html("");
			});
			$("#NextTimeAttackLimit, #NextLapsTimeLimit, #hotSeatTimeLimit").keyup(function(){
				var sec = $(this).val();
				var min = secToMin(sec);
				$(this).parent("td").parent("tr").children("td.preview").html("["+min+" min]");
			});
			
			
			/**
			* Create MatchSettings
			*/
			if( $("body").hasClass("section-maps-creatematchset") ){
				// Importer tout le dossier
				$("#mapImport").click(function(){
					$(".creatematchset .maps").addClass("loading");
					matchset_mapImport();
				});
				
				// Faire une sélection
				$("#mapImportSelection").click(function(){
					$(".creatematchset .maps").addClass("loading");
					matchset_mapImportSelection();
				});
				// Checkbox
				$("input#checkAllMapImport").click(function(){
					$("#mapImportSelectionDialog").checkAll( $(this).attr("checked") );
					if( $("#mapImportSelectionDialog tr.current").hasClass("selected") ){
						$("#mapImportSelectionDialog tr.current").removeClass("selected");
					}
				});
				
				// Clic sur les lignes
				$("#mapImportSelectionDialog tr").live("click", function(){
					// Si la ligne est déjà sélectionnée, on l'enlève
					if( $(this).hasClass("selected") ){
						$(this).removeClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", false);
					}
					// Sinon, on l'ajoute
					else{
						$(this).addClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", true);
					}
					// Mise à jour du CheckAll
					$("#mapImportSelectionDialog").updateCheckAll( $("input#checkAllMapImport") );
				});
				
				
				// Voir la sélection du matchsettings
				$("#mapSelection").click(function(){
					$(".creatematchset .maps").addClass("loading");
					matchset_mapSelection();
				});
				// Enlever une map de la sélection
				$("#mapSelectionDialog tr a").live("click", function(){
					matchset_mapSelection( parseInt($(this).parent("td").parent("tr")[0].sectionRowIndex + 1) );
					return false;
				});
				
				// Nom du MatchSettings
				$("#matchSettingName").click(function(){
					$(this).select();
				});
				$("#matchSettingName").blur(function(){
					if( $(this).val() == "" ){
						$(this).val("match_settings");
					}
				});
			}
		}
		/**
		* Chat
		*/
		else if( $("body").hasClass("section-chat") ){
			// ChatServerLines
			var hideServerLines = 0;
			
			// Clique sur "Masquer les lignes du serveur"
			$(".title-detail a").click(function(){
				// Valeur
				hideServerLines = $(this).data("val");
				if(hideServerLines == "0"){ hideServerLines = "1"; }
				else{ hideServerLines = "0"; }
				getChatServerLines(hideServerLines);
				$(this).data("val", hideServerLines);
				
				// Texte
				var text = $(this).text();
				$(this).text( $(this).data("txt") );
				$(this).data("txt", text);
				return false;
			});
			
			// Affichage toutes les 3s
			setInterval(function(){
				getChatServerLines(hideServerLines);
			}, 3000);
			
			// Ajout d'un message
			$("#chatNickname, #chatMessage").click(function(){
				var text = $(this).val();
				var defaultText = $(this).data("default-value");
				
				if(text == defaultText){
					$(this).val("");
				}
			});
			$("#chatNickname, #chatMessage").blur(function(){
				var text = $(this).val();
				var defaultText = $(this).data("default-value");
				
				if(text == ""){
					$(this).val(defaultText);
				}
			});
			$("#chatSend").click(function(){
				var msg = $("#chatMessage").val();
				if( msg != $("#chatMessage").data("default-value") && msg != "" ){
					addChatServerLine();
				}
			});
			$("#chatMessage").keypress(function(event){
				var msg = $(this).val();
				if( msg != $(this).data("default-value") && msg != "" ){
					if( event.keyCode == 13 ){
						addChatServerLine();
					}
				}
			});
		}
		/**
		* Maps-list
		*/
		else if( $("body").hasClass("section-maps-list") ){
			// Mise à jour de la liste
			setInterval(function(){
				getMapList(getMode(), getCurrentSort());
			}, 10000);
			
			// Checkbox
			$("input#checkAll").click(function(){
				$("#maplist").checkAll( $(this).attr("checked") );
				if( $("#maplist tr.current").hasClass("selected") ){
					$("#maplist tr.current").removeClass("selected");
				}
				
				// Mise à jour du nb de lignes sélectionnées
				$(".maps .list").updateNbSelectedLines();
			});
			
			// Clic sur les lignes
			$("#maplist tr").live("click", function(){
				if( !$(this).hasClass("current") ){
					// Si la ligne est déjà sélectionnée, on l'enlève
					if( $(this).hasClass("selected") ){
						$(this).removeClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", false);
					}
					// Sinon, on l'ajoute
					else{
						$(this).addClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", true);
					}
					
					// Mise à jour du nb de lignes sélectionnées
					$(".maps .list").updateNbSelectedLines();
					$(".maps .list").updateCheckAll( $("input#checkAll") );
				}
			});
			
			// Mode détail
			$("#detailMode").click(function(){
				setMapslistDetailMode();
				return false;
			});
			
			// Tris
			$("#maplist table th a").click(function(){
				var sort = $(this).attr("href");
				sort = sort.split("=")[1];
				$("#maplist").addClass("loading");
				getMapList(getMode(), sort);
				return false;
			});
		}
		/**
		* Maps-upload
		*/
		else if( $("body").hasClass("section-maps-upload") ){
			//Upload
			var uploader;
			initializeUploader();
			
			// Mode de transfert
			$(".transferMode li").click(function(){
				$(this).children("input").attr("checked", true);
				$(".transferMode li").removeClass("selected");
				$(this).addClass("selected");
				
				if( $(this).children("input").val() == "local" ){
					$("input#GotoListMaps").attr("checked", false);
				}
				initializeUploader(); // TODO : plutot mettre à jour les paramères
				
				/*uploader.setParams({
					type: $(this).children("input").val(),
				});*/
			});
			
			// Options
			$(".options-checkbox input, .options-checkbox label").click(function(){
				/*uploader.setParams({
					mset: ( $("#SaveCurrentMatchSettings").attr("checked") ) ? true : false,
					gtlm: ( $("#GotoListMaps").attr("checked") ) ? true : false
				});*/
				initializeUploader(); // TODO : plutot mettre à jour les paramères
			});
		}
		/**
		* Maps-local
		*/
		else if( $("body").hasClass("section-maps-local") ){
			// Checkbox
			$("input#checkAll").click(function(){
				$("#maplist").checkAll( $(this).attr("checked") );
				
				// Mise à jour du nb de lignes sélectionnées
				$(".maps .local").updateNbSelectedLines();
			});
			
			// Clic sur les lignes
			$("#maplist tr").live("click", function(){
				if( !$(this).hasClass("no-line") ){
					// Si la ligne est déjà sélectionnée, on l'enlève
					if( $(this).hasClass("selected") ){
						$(this).removeClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", false);
					}
					// Sinon, on l'ajoute
					else{
						$(this).addClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", true);
					}
					
					// Mise à jour du nb de lignes sélectionnées
					$(".maps .local").updateNbSelectedLines();
					$(".maps .local").updateCheckAll( $("input#checkAll") );
				}
			});
			
			// Renommer
			$("#renameMap").click(function(){
				if( $(this).hasClass("active") ){
					slideUpRenameForm();
				}
				else{
					if( $("#moveMap").hasClass("active") ){
						slideUpMoveForm();
					}
					slideDownRenameForm();
				}
			});
			$("#renameMapCancel").live("click", function(){
				slideUpRenameForm();
			});
			
			// Déplacer
			$("#moveMap").click(function(){
				if( $(this).hasClass("active") ){
					slideUpMoveForm();
				}
				else{
					if( $("#renameMap").hasClass("active") ){
						slideUpRenameForm();
					}
					slideDownMoveForm();
				}
			});
			$("#moveMapCancel").live("click", function(){
				slideUpMoveForm();
			});
			
			// Supprimer
			$("#deleteMap").click(function(){
				return confirm( $(this).data("confirm") );
			});
		}
		/**
		* Maps-matchset
		*/
		else if( $("body").hasClass("section-maps-matchset") ){
			// Checkbox
			$("input#checkAll").click(function(){
				$("#matchsetlist").checkAll( $(this).attr("checked") );
				
				// Mise à jour du nb de lignes sélectionnées
				$(".maps .matchset").updateNbSelectedLines();
			});
			
			// Clic sur les lignes
			$("#matchsetlist tr").live("click", function(){
				if( !$(this).hasClass("no-line") ){
					// Si la ligne est déjà sélectionnée, on l'enlève
					if( $(this).hasClass("selected") ){
						$(this).removeClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", false);
					}
					// Sinon, on l'ajoute
					else{
						$(this).addClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", true);
					}
					
					// Mise à jour du nb de lignes sélectionnées
					$(".maps .matchset").updateNbSelectedLines();
					$(".maps .matchset").updateCheckAll( $("input#checkAll") );
					
					// Édition si une seule ligne sélectionné
					if( $(".selected-files-count").text().replace("(", "").replace(")", "") > 1 ){
						$("#editMatchset").attr("hidden", true);
					}
					else{
						if( $("#editMatchset").attr("hidden") ){
							$("#editMatchset").removeAttr("hidden");
						}
					}
				}
			});
		}
		/**
		* Maps-order
		*/
		else if( $("body").hasClass("section-maps-order") ){
			// Tri automatique
			$(".autoSortMode li").click(function(){
				$("#sortableMapList").addClass("loading");
				$(this).children("input").attr("checked", true);
				$(".autoSortMode li").removeClass("selected");
				$(".autoSortMode li span.ui-icon").removeClass("active");
				$(this).addClass("ui-state-default selected");
				$(this).find(".icon .ui-icon-arrowthick-1-n").addClass("active");
				
				// Tri
				setMapsOrderSort($(this).children("input").val(), "asc");
			});
			$(".autoSortMode li span.ui-icon").click(function(){
				$("#sortableMapList").addClass("loading");
				$(".autoSortMode li").removeClass("selected");
				$(".autoSortMode li span.ui-icon").removeClass("active");
				$(this).parents("li").addClass("selected");
				$(this).parents("li").children("input").attr("checked", true);
				$(this).addClass("active");
				
				if( $(this).hasClass("ui-icon-arrowthick-1-n") ){
					var order = "asc";
				}
				else{
					var order = "desc";
				}
				// Tri
				setMapsOrderSort($(this).parent(".icon").parent("li").children("input").val(), order);
				
				return false;
			});
			
			// Tri manuel
			$("#sortableMapList").sortable({
				placeholder: "ui-state-highlight",
				revert: true,
				zIndex: 9999
			});
			$("#reset").click(function(){
				location.href = $(".section-maps-order .cadre.order form").attr("action");
			});
			$("#save").click(function(){
				var listStr = "";
				var list = $("#sortableMapList li .order-map-name");
				if( list.length > 0 ){
					$.each(list, function(i, n){
						listStr += n.title+",";
					});
				$("#list").val(listStr.substring(0, listStr.length-1));
				}
			});
		}
		/**
		* Guest-Ban
		*/
		else if( $("body").hasClass("section-guestban") ){
			// CleanList
			$("a.cleanList").click(function(){
				var out = false;
				var lines = $(this).parent("li").parent("ul").parent("div").parent("div").find("tbody tr");
				
				if(lines.length > 0){
					$.each(lines, function(id, line){
						if(line.className == "no-line"){
							error( $(this).data("empty"), true);
						}
						else{
							out = true;
						}
					});
				}
				
				return out;
			});
			
			// Checkbox
			$("input#checkAllBanlist").click(function(){
				$("#banlist").checkAll( $(this).attr("checked") );
				$(".cadre.left").updateNbSelectedLines();
			});
			$("input#checkAllBlacklist").click(function(){
				$("#blacklist").checkAll( $(this).attr("checked") );
				$(".cadre.left").updateNbSelectedLines();
			});
			$("input#checkAllGuestlist").click(function(){
				$("#guestlist").checkAll( $(this).attr("checked") );
				$(".cadre.left").updateNbSelectedLines();
			});
			$("input#checkAllIgnorelist").click(function(){
				$("#ignorelist").checkAll( $(this).attr("checked") );
				$(".cadre.left").updateNbSelectedLines();
			});
			$("#playlists input#checkAllPlaylists").click(function(){
				$("#playlists").checkAll( $(this).attr("checked") );
				$("#playlists").updateNbSelectedLines();
			});
			
			// Clic sur les lignes
			$("#banlist tr, #blacklist tr, #guestlist tr, #ignorelist tr").live("click", function(){
				if( !$(this).hasClass("no-line") ){
					// Si la ligne est déjà sélectionnée, on l'enlève
					if( $(this).hasClass("selected") ){
						$(this).removeClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", false);
					}
					// Sinon, on l'ajoute
					else{
						$(this).addClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", true);
					}
					
					// Mise à jour du nb de lignes sélectionnées
					$(".cadre.left").updateNbSelectedLines();
					$(".cadre.left").updateCheckAll( $(this).parent("tbody").parent("table").parent("div").find("input[type=checkbox]") );
				}
			});
			$("#playlists tr").live("click", function(){
				if( !$(this).hasClass("no-line") ){
					// Si la ligne est déjà sélectionnée, on l'enlève
					if( $(this).hasClass("selected") ){
						$(this).removeClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", false);
					}
					// Sinon, on l'ajoute
					else{
						$(this).addClass("selected");
						$(this).children("td.checkbox").children("input").attr("checked", true);
					}
					
					// Mise à jour du nb de lignes sélectionnées
					$("#playlists").updateNbSelectedLines();
					$("#playlists").updateCheckAll( $("#playlists input#checkAllPlaylists") );
				}
			});
			
			// Ajouter
			$("#addPlayerList").change(function(){
				if( $(this).val() == "more" ){
					$(this).hide();
					$(this).attr("hidden", true);
					$("#addPlayerLogin").fadeIn("fast");
					$("#addPlayerLogin").removeAttr("hidden");
				}
			});
			$("#addPlayerLogin").click(function(){
				if( $(this).val() == $(this).data("default-value") ){
					$(this).val("");
				}
			});
			$("#addPlayerLogin").blur(function(){
				if( $(this).val() == "" ){
					$(this).val( $(this).data("default-value") );
				}
			});
			
			// Créer une playlist
			$("#clickNewPlaylist").click(function(){
				var selector = $("#form-new-playlist");
				if( selector.attr("hidden") ){
					selector.slideDown("fast");
					selector.removeAttr("hidden");
					$(this).text( $(this).data("cancel") );
				}
				else{
					selector.slideUp("fast");
					selector.attr("hidden", true);
					$(this).text( $(this).data("newplaylist") );
				}
				return false;
			});
			$("#createPlaylistName").click(function(){
				if( $(this).val() == $(this).data("playlistname") ){
					$(this).val("");
				}
			});
			$("#createPlaylistName").blur(function(){
				var val = $(this).val();
				if( val == "" ){
					$(this).val( $(this).data("playlistname") );
				}
				else{
					var extTXT = val.indexOf(".txt");
					var extXML = val.indexOf(".xml");
					if(extTXT === -1 && extXML === -1){
						$(this).val(val+".txt");
					}
				}
			});
		}
	}
});