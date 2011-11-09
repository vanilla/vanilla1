(function($){
	$.HtmlFormatter = {
		margin: 290,

		getDesiredWidth: function() {
			var innerWidth = window.innerWidth || document.documentElement.clientWidth;
			return innerWidth - $.HtmlFormatter.margin;
		},

		changeDimensions: function() {
			var $img = $('img.InlineImage'),
			desiredWidth = $.HtmlFormatter.getDesiredWidth();

			$img.width('auto');
			$img.each(function () {
				var el = $(this); 
				if (el.width() > desiredWidth) {
					el.width(desiredWidth);
				}
			});
		}
	};

	$(window).bind('load resize', $.HtmlFormatter.changeDimensions);
})(jQuery.noConflict());