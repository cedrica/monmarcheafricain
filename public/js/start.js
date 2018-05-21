(function($) {
	 com.michouafroshop.Start = function() {
		 var that = new com.michouafroshop.Core(); // call Core()
		 that.flag='start';
		 return that;
	};
	new com.michouafroshop.Start().register();
})(jQuery);

