(function($) {
	com.michouafroshop.Categories = function(){
		var that = new com.michouafroshop.Core();
		that.flag = 'categories';
		return that;
	}
	new com.michouafroshop.Categories().register();
})(jQuery);