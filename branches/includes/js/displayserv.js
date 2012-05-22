(function($){
	$.fn.displayServ = function(){
		// Classes
		$(this).addClass("displayserv");
		
		// Requête
		var serverId = 0;
		$.getJSON("includes/ajax/initialize.php", function(data){
			var data;
		});
	};
})(jQuery);