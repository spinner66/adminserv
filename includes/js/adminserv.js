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
		else if( $("body").hasClass("section-srvopts")  ){
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
		else if( $("body").hasClass("section-gameinfos")  ){
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
		
	}
});