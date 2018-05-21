(function($) {
	 com.michouafroshop.Panier = function() {
		 var that = new com.michouafroshop.Core(); // call Core()
		 that.flag='panier';
		 return that;
	};
	new com.michouafroshop.Panier().register();
})(jQuery);

