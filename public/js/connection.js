(function($) {
	com.michouafroshop.Connection = function(){
		var that = new com.michouafroshop.Core();
		that.flag = 'connection';
		return that;
	}
	new com.michouafroshop.Connection().register();
})(jQuery);