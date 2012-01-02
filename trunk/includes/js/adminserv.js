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
	/**
	* Not front
	*/
	else{
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
			setInterval(function(){
				getCurrentServerInfo();
			}, 10000);
		}
		/**
		* Server Options
		*/
		else if( $("body").hasClass("section-srvopts") ){
			// ServerName
			$("input#ServerName").keyup(function(event){
				var key = event.keyCode;
				if(key != 13 && key != 37 && key != 39){
					previewSrvOpts($(this).val(), "#serverNameHtml");
				}
			});
			// ServerComment
			$("textarea#ServerComment").keyup(function(event){
				var key = event.keyCode;
				if(key != 37 && key != 39){
					previewSrvOpts("$i"+ $(this).val(), "#serverCommentHtml");
				}
			});
		}
		/**
		* Game Infos
		*/
		else if( $("body").hasClass("section-gameinfos") ){
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
					$("#NextFinishTimeoutValue").removeClass("displaynone");
					$("#NextFinishTimeoutValue").val("15");
					var defaultValueSelector = $("#NextFinishTimeoutValue").parent("td").parent("tr").children("td.preview").children("a.returnDefaultValue");
					defaultValueSelector.fadeIn("fast");
					defaultValueSelector.removeClass("displaynone");
				}
			});
			
			// ForceShowAllOpponents
			$("select#NextForceShowAllOpponents").change(function(){
				if( $(this).val() == "more" ){
					$(this).hide();
					$("#NextForceShowAllOpponentsValue").fadeIn("fast");
					$("#NextForceShowAllOpponentsValue").removeClass("displaynone");
					$("#NextForceShowAllOpponentsValue").val("2");
					var defaultValueSelector = $("#NextForceShowAllOpponentsValue").parent("td").parent("tr").children("td.preview").children("a.returnDefaultValue");
					defaultValueSelector.fadeIn("fast");
					defaultValueSelector.removeClass("displaynone");
				}
			});
			
			// Revenir à la valeur par défaut
			$("a.returnDefaultValue").click(function(){
				$(this).fadeOut();
				$(this).addClass("displaynone");
				var selectValueSelector = $(this).parent("td").parent("tr").children("td.next").children("select");
				var inputValueSelector = $(this).parent("td").parent("tr").children("td.next").children("input");
				inputValueSelector.hide();
				inputValueSelector.addClass("displaynone");
				inputValueSelector.val("");
				selectValueSelector.fadeIn("fast");
				selectValueSelector.children("option:first").removeAttr("selected");
				selectValueSelector.children("option:first").select();
				selectValueSelector.removeClass("displaynone");
				return false;
			});
			
			// Affichage sec -> min
			$("#NextTimeAttackLimit, #NextLapsTimeLimit").click(function(){
				var sec = $(this).val();
				var min = secToMin(sec);
				$(this).parent("td").parent("tr").children("td.preview").html("["+min+" min]");
			});
			$("#NextTimeAttackLimit, #NextLapsTimeLimit").blur(function(){
				$(this).parent("td").parent("tr").children("td.preview").html("");
			});
			$("#NextTimeAttackLimit, #NextLapsTimeLimit").keyup(function(){
				var sec = $(this).val();
				var min = secToMin(sec);
				$(this).parent("td").parent("tr").children("td.preview").html("["+min+" min]");
			});
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
				hideServerLines = $(this).attr("data-val");
				if(hideServerLines == "0"){ hideServerLines = "1"; }
				else{ hideServerLines = "0"; }
				getChatServerLines(hideServerLines);
				$(this).attr("data-val", hideServerLines);
				
				// Texte
				var text = $(this).text();
				$(this).text( $(this).attr("data-txt") );
				$(this).attr("data-txt", text);
				return false;
			});
			
			// Affichage toutes les 3s
			setInterval(function(){
				getChatServerLines(hideServerLines);
			}, 3000);
			
			// Ajout d'un message
			$("#chatNickname, #chatMessage").click(function(){
				var text = $(this).val();
				var defaultText = $(this).attr("data-default-value");
				
				if(text == defaultText){
					$(this).val("");
				}
			});
			$("#chatNickname, #chatMessage").blur(function(){
				var text = $(this).val();
				var defaultText = $(this).attr("data-default-value");
				
				if(text == ""){
					$(this).val(defaultText);
				}
			});
			$("#chatSend").click(function(){
				var msg = $("#chatMessage").val();
				if( msg != $("#chatMessage").attr("data-default-value") && msg != "" ){
					addChatServerLine();
				}
			});
			$("#chatMessage").keypress(function(event){
				var msg = $(this).val();
				if( msg != $(this).attr("data-default-value") && msg != "" ){
					if( event.keyCode == 13 ){
						addChatServerLine();
					}
				}
			});
		}
		/**
		* Guest-Ban
		*/
		else if( $("body").hasClass("section-guestban") ){
			// Ajouter
			$("#addPlayerList").change(function(){
				if( $(this).val() == "more" ){
					$(this).hide();
					$(this).addClass("displaynone");
					$("#addPlayerLogin").fadeIn("fast");
					$("#addPlayerLogin").removeClass("displaynone");
				}
			});
			$("#addPlayerLogin").click(function(){
				if( $(this).val() == $(this).attr("data-default-value") ){
					$(this).val("");
				}
			});
			$("#addPlayerLogin").blur(function(){
				if( $(this).val() == "" ){
					$(this).val( $(this).attr("data-default-value") );
				}
			});
		}
	}
});