(function($){
	$.Notifi = {
		root: null,
		ajaxUrl: '/extensions/Notifi/ajax.php',

		/**
		 * Set $.Notifi.root to the forum base url and register event Notifi's
		 * checkbox and link switch.
		 */
		init: function() {
			var pathFinder = new PathFinder();
			
			this.root =
				pathFinder.getRootPath('link', 'href', 'themes/vanilla/styles/default/favicon.ico') ||
				pathFinder.getRootPath('script', 'src', 'extensions/Notifi/functions.js') || '';

			$('.notifiToggleCBox input').click(this.toggleCBox)
			$('.notifiToggleLink').click(this.toggleLink);
		},

		/**
		 * Sent the Ajax request to enable/disable a Notifi option.
		 *
		 * @param type  Type of Notifi option (ALL|CATEGORY|DISCUSSION|COMMENT|OWN|KEEPEMAILING).
		 * @param id    ID of category, discussion or comment to enable notification for.
		 * @param value Value to set the option to.
		 * @param cb    Function to call on successful answer.
		 */
		update: function(type, id, value, cb) {
			var param = {
				PostBackAction: 'ChangeNotifi',
				Type: type,
				ElementID: id,
				Value: value
			};
			
			$.post(this.root + this.ajaxUrl, param, cb);
			return true;
		},


		/**
		 * Toggle a Notifi option represented by a checkbox.
		 *
		 * Toggle a Notifi option via an Ajax request and add a
		 * "PreferenceProgress" class to the checkbox container during
		 * the request.
		 *
		 * On type ALL or COMMENT, it will show/hide more specific option
		 */
		toggleCBox: function() {
			var $input = $(this),
				$cont = $input.parents('.notifiToggleCBox'),
				className = 'PreferenceProgress',
				value = $input.attr('checked') ? 1 : 0,
				inputName, type, id;

			// Get type and id of category or discussion from imput name
			// e.g.: name="NOTIFI_CATEGORY_1"
			inputName = $input.attr('name').split('_');
			type = inputName[1];
			id = inputName.length === 3 ? inputName[2] : 0;

			$cont.addClass(className);
			$.Notifi.update(type, id, value, function(){
				$cont.removeClass(className);

				if (type === 'ALL') {
					if (value === 1) {
						$('#NotifiOwnCont').hide();
						$('#NotifiCommentCont').hide();
						$('#categoriesContainer').hide();
						$('#discussionsContainer').hide();
					} else {
						$('#NotifiOwnCont').show();
						$('#NotifiCommentCont').show();
						$('#categoriesContainer').show();
						$('#discussionsContainer').show();
					}
				}

				if (type === 'COMMENT') {
					if (value === 1) {
						$('#NotifiOwnCont').hide();
					} else {
						$('#NotifiOwnCont').show();
					}
				}

				
			});
		},

		/**
		 * Toggle a subscribe/unsubscribe link to enable a Notifi option
		 */
		toggleLink: function(event) {
			var $elem = $(this),
				progressClassName = 'Progress',
				activeClassName = 'notifiActive',
				value, attr, type, id;

			event.preventDefault();

			// Retrieve type and id from link URL
			attr = $elem.attr('href').split('_');
			type = attr[1];
			id = attr.length === 3 ? attr[2] : 0;

			if ($('.notifiSubscribe', this).hasClass(activeClassName)) {
				value = 0;
			} else {
				value = 1;
			}

			$elem.addClass(progressClassName);
			$.Notifi.update(type, id, value, function(){
				$elem.removeClass(progressClassName);
				$('.notifiSubscribe', $elem).toggleClass(activeClassName);
				$('.notifiUnSubscribe', $elem).toggleClass(activeClassName)
			});
		}

	};

	// On DOM ready, initialize Notifi
	$(function(){
		$.Notifi.init();
	});
	
})(jQuery.noConflict())