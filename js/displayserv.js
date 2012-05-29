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
									
								+ '</div>'
								+ '<div class="ds-content">'
									
								+ '</div>'
							+ '</div>'
							+ '<div class="ds-part-middle">'
								+ '<div class="ds-header">'
									
								+ '</div>'
								+ '<div class="ds-content">'
									
								+ '</div>'
							+ '</div>'
							+ '<div class="ds-part-right">'
								+ '<div class="ds-header">'
									
								+ '</div>'
								+ '<div class="ds-content">'
									
								+ '</div>'
							+ '</div>'
						+ '</li>';
					}
				out += '</ul>';
				
				// Affichage
				$(_this).html(out);
			}
		});
	};
})(jQuery);