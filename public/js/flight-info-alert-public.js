(function( $ ) {
	'use strict';

	const alertTable = $('.fia_table').DataTable({
        dom: '<"fia_dataTables_head"Bf><"fia_dataTables_body"tip>',
        buttons: [
            {
                extend: 'colvis',
                collectionLayout: 'fixed columns',
                collectionTitle: 'Column visibility control'
            }
        ],
        ajax: {
            url: fia_api.ajaxurl,
            type: 'POST',
            data: {
                action: 'fetch_alerts',
                security: fia_api.nonce,
            }
        },
        columns: [
            { data: 'name' },
            { data: 'alertType' },
            { data: 'active' },
            { data: 'flightNumber' },
            { data: 'fromFlight' },
            { data: 'toFlight' },
            { data: 'departureAirport' },
            { data: 'arrivalAirport' },
            { data: 'departureDate' },
            { data: 'iataCarrierCode' },
            { data: 'icaoCarrierCode' },
            { data: 'content' },
            { data: 'description' },
        ],
    });

    // setInterval( function() {
    //     alertTable.ajax.reload( null, false );
    // }, 30 * 1000 );

})( jQuery );
