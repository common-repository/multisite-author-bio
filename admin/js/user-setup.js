(function (window, document) {
	'use strict';

	async function getTranslatedBio() {

		// Ajax url and nonce
		const ajaxUrl = mab_user_obj.ajax_url;
		const nonce = mab_user_obj.mab_nonce;

		// Set elements
		const userId = document.querySelector( '.mab-form-container' ).dataset.user;
		const selectElement = document.querySelector( '.mab-select-bio-variation' );
		const bioTextarea = document.querySelector( '.mab-bio-variation-text' );
		const bioLabel = document.querySelector( '.mab-bio-variation-label' );
		const siteName = selectElement.value;

		// Set data object and action
		const _$data = new FormData();
		_$data.append( 'action', 'mab_get_bio_variation' );
		_$data.append( 'site_name', siteName );
		_$data.append( 'user_id', userId );
		_$data.append( 'mab_nonce', nonce );

		// If a value is selected, send a fetch request
		if( siteName ) {
			try {
	
				// Send fetch request and wait for the response
				const response = await fetch( ajaxUrl, {
					method: 'POST',
					body: _$data
				});

				// Check if response ok
				if( response.ok ) {

					// Get response json data
					const data = await response.json();
					
					// Update the textarea with the response data
					bioTextarea.value = data.data.message || '';
					bioTextarea.dispatchEvent( new Event( 'change' ) );
					bioTextarea.classList.remove( 'hidden' );
					bioLabel.classList.remove( 'hidden' );

				}
			} catch ( error ) {
				console.error( 'Error fetching bio variation:', error );
			}

		} else {

			// Hide elements if no value is selected
			bioTextarea.classList.add( 'hidden' );
			bioLabel.classList.add( 'hidden' );

		}

	}

	// Document ready
	document.addEventListener( 'DOMContentLoaded', function () {

		// Fetch the initial translated bio
		getTranslatedBio();

		// Add change event listener to the select element
		const selectElement = document.querySelector( '.mab-select-bio-variation' );
		selectElement.addEventListener( 'change', getTranslatedBio );

	});

})(window, document);
