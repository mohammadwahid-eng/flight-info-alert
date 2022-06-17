(function( $ ) {
	'use strict';

	const fiaAlertTable = $('.fia_table').DataTable({
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
               action: 'fia_fetch_alerts',
               security: fia_api.nonce,
            }
        },
        columns: [
            { data: 'name', defaultContent: '-' },
            {
				data: 'alertType',
				defaultContent: '-',
				render: function ( data ) {
					const tempData = data;
					return data?.charAt(0).toUpperCase() + tempData?.slice(1);
				}
			},
            {
				data: 'active',
				defaultContent: '-',
				render: function ( data ) {
					return data == true ? 'Yes' : 'No';
				}
			},
            { data: 'flightNumber', defaultContent: '-' },
            { data: 'fromFlight', defaultContent: '-' },
            { data: 'toFlight', defaultContent: '-' },
            {
				data: 'departureAirport',
				defaultContent: '-',
				render: function ( data ) {
					return data?.toUpperCase();
				}
			},
            {
				data: 'arrivalAirport',
				defaultContent: '-',
				render: function ( data ) {
					return data?.toUpperCase();
				}
			},
            { data: 'departureDate', defaultContent: '-' },
            {
				data: 'iataCarrierCode',
				defaultContent: '-',
				render: function ( data ) {
					return data?.toUpperCase();
				}
			},
            {
				data: 'icaoCarrierCode',
				defaultContent: '-',
				render: function ( data ) {
					return data?.toUpperCase();
				}
			},
            { data: 'description', defaultContent: '-' },
            { data: 'lastUpdatedTimestamp', defaultContent: '-' },
        ],
    });

    setInterval( function() {
        fiaAlertTable.ajax.reload( null, false );
    }, 30 * 1000 );

})( jQuery );
