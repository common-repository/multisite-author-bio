(function (window, document) {
	'use strict';

	// Wait until the DOM is fully loaded
	document.addEventListener('DOMContentLoaded', function () {

		// Ajax url and nonce
		const ajaxUrl = mab_obj.ajax_url;
		const nonce = mab_obj.mab_nonce;

		// Add submit event listener to the form
		mabForm.addEventListener( 'submit', async function (e) {

			// Prevent defautl functionality
			e.preventDefault();

			// Get the clearData checkbox input
			var clearDataInput = document.querySelector( '#admin-view .section.clear-data input' );

			// Determine if the checkbox is checked or not
			var clearData = clearDataInput.checked ? 1 : 0;

			// Set data object and action
			const _$data = new FormData();
			_$data.append( 'action', 'mab_save_admin_page' );
			_$data.append( 'clear_data', clearData );
			_$data.append( 'mab_nonce', nonce );

			try {
	
				// Send fetch request and wait for the response
				const response = await fetch( ajaxUrl, {
					method: 'POST',
					body: _$data
				});

				// Check if response ok
				if( response.ok ) {
					
					// Reload the page after 1 second
					setTimeout(function () {
						location.reload();
					}, 1000);

				}
			} catch ( error ) {
				console.error( 'Error:', error );
			}

		});
	});

})(window, document);
