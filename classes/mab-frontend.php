<?php

class MAB_Frontend {

	/**
	 * Constructor to initialize hooks.
	 *
	 * @param   void
	 * @return  void
	 */
	public function __construct() {

		// Hook into the author bio filter
		add_filter( 'get_the_author_user_description', array( $this, 'mab_author_description_filter' ) );

	}

	/**
	 * Override standard user bio if translation exists for the current site.
	 * 
	 * @param   string $bio The standard user bio.
	 * @return  string Either the standard user bio or the translated one for the multisite.
	 */
	public function mab_author_description_filter( $bio ) {

		// Get current site's host
		$site_slug = $this->mab_get_current_site_slug();

		// Get the post's author ID
		$user_id = get_post_field( 'post_author', get_the_ID() );

		// Get the user's bio variation for the current site
		$bio_variation = get_user_meta( $user_id, 'mab_profile_bio_' . $site_slug, true );

		// Return the bio variation if it exists, otherwise return the original bio
		if ( ! empty( $bio_variation ) ) {
			return esc_textarea( $bio_variation );
		} 

		// Return the original bio if no variation exists
		return esc_textarea( $bio );

	}

	/**
	 * Retrieve the current site's slug (hostname).
	 * 
	 * @return string The sanitized site slug (hostname).
	 */
	private function mab_get_current_site_slug() {

		// Parse the URL and retrieve the hostname
		$site_url = wp_parse_url( home_url(), PHP_URL_HOST );

		// Sanitize and return the site slug
		return sanitize_text_field( $site_url );

	}

}
