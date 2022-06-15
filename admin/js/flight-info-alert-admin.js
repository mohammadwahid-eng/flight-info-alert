(function( $ ) {
	'use strict';

	$(document).on('click', '#fetch-alerts', function(e) {
		e.preventDefault();

		if( ! confirm( "Are you sure?" ) ) return;

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
