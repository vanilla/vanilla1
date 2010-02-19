(function($){
	
	$.Notifi = {
		root: null,
		ajaxUrl: '/extensions/Notifi/ajax.php',
		postBackKey: '',

		/**
		 * Set $.Notifi.root to the forum base url and register event Notifi's
		 * checkbox and link switch.
		 *
		 */
		init: function() {
			var pathFinder = new PathFinder();

			// To find the root path, the forum need a theme with a favicon
			// or the page need a script from an extension.
			this.root =
				pathFinder.getRootPath(
					'link',
					'href',
					/themes\/[-\d\w]+\/styles\/[-\d\w]+\/favicon.ico$/
				) ||
				pathFinder.getRootPath(
					'script',
					'src',
					/extensions\/[-\d\w]+\/[-\/\d\w]+\.(js|php)/
				) ||
				'';

			$('.notifiToggleCBox input').click(this.toggleCBox)
			$('.notifiToggleLink').click(this.toggleLink);
		},

		/**
		 * Sent the Ajax request to enable/disable a Notifi option.
		 *
		 * @param type  Type of Notifi option
		 *				(ALL|CATEGORY|DISCUSSION|COMMENT|OWN|KEEPEMAILING).
		 * @param id    ID of category, discussion or comment to
		 *				enable notification for.
		 * @param value Value to set the option to.
		 * @param cb    Should hold the success, beforeSend (jQuery.Ajax option)
		 *				and undo functions (undo is called after an error that
		 *				can be fix).
		 */
		update: function(type, id, value, cb) {
			var param, authRetry;

			// Used in case of error dues to invalid PostBack key.
			// Will try twice to resent the request with an updated PostBackKey
			authRetry = (function(){
				var tries = 1;
				return function(wwwAuthenticate) {

					if (wwwAuthenticate.indexOf('Vanilla-Csrf-Check') >= 0 &&
						$.Notifi.updatePostBackKey(wwwAuthenticate) &&
						tries++ < 3
					) {
						param.data.PostBackKey = $.Notifi.postBackKey;
						$.Notifi._update(param);
						return true;
					}
					return false;
					
				};

			})();

			param = {
				type: "POST",
				dataType: "text",
				url: this.root + this.ajaxUrl,
				beforeSend: cb.beforeSend,
				success: cb.success,
				error: function(resp) {
					var wwwAuthenticate;

					if (resp.status === 401) {
						wwwAuthenticate = resp.getResponseHeader('Www-Authenticate');
					}

					if (!wwwAuthenticate || !authRetry(wwwAuthenticate)) {
						cb.undo && cb.undo();
						window.alert(resp.responseText);
					}
				},
				data: {
					PostBackAction: 'ChangeNotifi',
					Type: type,
					ElementID: id,
					Value: value,
					PostBackKey: this.postBackKey
				}};

			$.Notifi._update(param);
			return true;
		},

		/**
		 * Send an Ajax request with a copy of the parameters.
		 */
		_update: function(param) {
			$.ajax($.extend({}, param));
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


			$.Notifi.update(type, id, value, {
				beforeSend: function(){
					$cont.addClass(className);
				},
				success: function(){
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


				},
				undo: function(){
					$cont.removeClass(className);
					$input.attr('checked', !$input.attr('checked'));
				}
			});
		},

		/**
		 * Toggle a subscribe/unsubscribe link to enable/disable a Notifi option
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

			$.Notifi.update(type, id, value, {
				beforeSend: function() {
					$elem.addClass(progressClassName);
				},
				success: function() {
					$elem.removeClass(progressClassName);
					$('.notifiSubscribe', $elem).toggleClass(activeClassName);
					$('.notifiUnSubscribe', $elem).toggleClass(activeClassName)
				},
				undo: function() {
					$elem.removeClass(progressClassName);
				}
			});
		},

		updatePostBackKey: function(wwwAuthentication) {
			var parts = wwwAuthentication.split('=');

			if (parts.length != 2 ||
				wwwAuthentication[0].indexOf('Vanilla-Csrf-Check') >= 0
			) {
				return false;
			}

			this.postBackKey = parts[1].replace('"', '');
			return true;
		}

	};

	// On DOM ready, initialize Notifi
	$(function(){
		$.Notifi.init();
	});
	
})(jQuery.noConflict())