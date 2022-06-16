(function( $ ) {
	'use strict';

	$(document).on('keyup', 'input[type=number].fia-form-control', function(e) {
		let maxlength = $(this).attr('maxlength');
		if (typeof maxlength !== 'undefined' && maxlength !== false) {
			if( $(this).val().length ) {
				$(this).val( $(this).val().substr(0, maxlength) );
			}
		}
	});

	$(document).on('click', '#fetch-alerts', function(e) {
		e.preventDefault();

		if( ! confirm( "It will delete all your local alerts. Do you want to continue?" ) ) return;

		let btn = $(this),
			btnText = btn.text();
		btn.prop("disabled", true).text('Processing. Please wait...');

		$.ajax({
			type: "POST",
			dataType: "json",
			url: fia_api_fetch.ajaxurl,
			data: {
				action: "fetch_alerts",
				security: fia_api_fetch.nonce
			},
			success: function( response ) {
				btn.prop("disabled", false).text(btnText);
				alert( response.message );
			}
		});
	});

})( jQuery );
