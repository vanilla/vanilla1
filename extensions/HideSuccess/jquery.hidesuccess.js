(function($){

	$.hideSuccess = {
		selector: '#Success',
		delay: 2000,
		speed: 'slow',
		
		init: function() {
			var self = $.hideSuccess;

			setTimeout(self.hide, self.delay)
		},
		
		hide: function() {
			var self = $.hideSuccess;

			$(self.selector).slideUp(self.speed)
		}
	};

	$($.hideSuccess.init);

})(jQuery.noConflict());