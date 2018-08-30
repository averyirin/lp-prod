(function ($) {
	
	$.fn.wetMessages = function(options) {

		var settings = $.extend({
			messagesClass: ".elgg-page-messages",
			errorMessageClass: ".elgg-page-messages .elgg-state-error",
			successMessageClass: ".elgg-page-messages .elgg-state-success"
		}, options);

		$(settings.errorMessageClass).addClass('alert');
		$(settings.errorMessageClass).addClass('alert-danger');

		$(settings.successMessageClass).addClass('alert');
		$(settings.successMessageClass).addClass('alert-success');

		return this.prepend($(settings.messagesClass));
	};

}( jQuery ));