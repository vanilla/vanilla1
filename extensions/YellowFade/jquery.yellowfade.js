/**
 * This technique is a combination of a technique Michael Raichelson used
 * for highlighting FAQ's using anchors and the ever popular
 * yellow-fade technique used by 37 Signals in Basecamp.
 * 
 * Copyright 2005 Michael Raichelson
 * Copyright 2010 Damien Lebrun
 */

(function($){

	$.yellowFade = {
		fadeTo: '#ff9',
		duration: 2800,
		repeat: 1,
		selector: 'a',

		/**
		 * Highlight a target item from the URL (browser address bar)
		 * if one is present and setup all anchor tags with targets pointing to
		 * the current page to cause a fade on the target element when clicked.
		 *
		 * Pre-condition: jQuery should be loaded and the DOM should be ready.
		 */
		init: function() {
			var self = $.yellowFade;

			$(window).load(self.onWindowLoad);
			$(self.selector).click(self.onClick);
		},


		/**
		 * Handler for the window load event
		 *
		 * Get the the element to highlight from the page url fragment
		 * (the part after the "#").
		 */
		onWindowLoad: function(){
			$.yellowFade.highlight(unescape(window.location));
		},

		/**
		 * Handler for anchor click even.
		 *
		 * Get the the element to highlight from the href url fragment.
		 *
		 * Will remove the window load event handler, it case the link is
		 * clicked while the page is loading.
		 * 
		 */
		onClick: function() {
			var self = $.yellowFade;

			$(window).unbind('load', self.onWindowLoad);
			self.highlight($(this).attr('href'));
		},

		/**
		 * Highlight the element with id equal to the url fragment
		 *
		 * @param url
		 */
		highlight: function(url) {
			var hashPos = url.indexOf('#'),
				self = $.yellowFade,
				speed = self.duration / self.repeat,
				id;

			if (hashPos > -1) {
				id = url.substring(hashPos);
				for (var i=0; i < self.repeat; i++) {
					$(id).effect("highlight", {color: self.fadeTo}, speed);
				}
			}
		}
	};

	$($.yellowFade.init);

})(jQuery.noConflict());

