<?php

class MAB_Plugin {

	/**
	 * Install the plugin.
	 * Ensures activation happens only in Network Admin.
	 *
	 * @param   void
	 * @return  void
	 */
	public static function install() {

		// Ensure activation happens only in Network Admin
		if ( ! is_network_admin() ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );

			wp_die(
				esc_html__( 'This plugin can only be activated from the Network Admin.', 'multisite-author-bio' ),
				esc_html__( 'Plugin Activation Error', 'multisite-author-bio' ),
				array( 'back_link' => true )
			);
		}

		// Set mab_activated to true
		update_option( sanitize_key( 'mab_activated' ), true );

	}

	/**
	 * Deactivate the plugin.
	 *
	 * @param   void
	 * @return  void
	 */
	public static function deactivate() {

		// Remove mab_activated option upon deactivation
		delete_option( sanitize_key( 'mab_activated' ) );

	}

	/**
	 * Uninstall the plugin and clear data if applicable.
	 *
	 * @param   void
	 * @return  void
	 */
	public static function uninstall() {

		// Check if mab_clear_data option is enabled and clear the data
		if( sanitize_text_field( get_option( sanitize_key( 'mab_clear_data' ) ) ) ) {

			// Clear all data related to the plugin
			self::mab_clear_data();

			// Delete the mab_clear_data option
			delete_option( sanitize_key( 'mab_clear_data' ) );

		}

	}

	/**
	 * Constructor to initialize hooks.
	 *
	 * @param   void
	 * @return  void
	 */
	public function __construct() {

		// Register hooks for uninstall, deactivation, and activation
		register_uninstall_hook( MAB_FILE, array( __CLASS__, 'uninstall' ) );
		register_deactivation_hook( MAB_FILE, array( __CLASS__, 'deactivate' ) );
		register_activation_hook( MAB_FILE, array( __CLASS__, 'install' ) );

		// Admin-specific hooks
		if ( is_admin() ) {

			// Load translations as early as possible.
			add_action( 'plugins_loaded', array( $this, 'mab_load_plugin_textdomain' ) );

			// Force the plugin to be network-activated only
			add_filter( 'user_has_cap', array( $this, 'mab_force_network_activation' ), 10, 3 );

			// Add a settings link to the plugin page
			add_filter( 'network_admin_plugin_action_links_' . MAB_BASENAME . '/multisite-author-bio.php', array( $this, 'mab_add_settings_link' ) );

			// Initialize admin scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'mab_enqueue_scripts' ) );

			// Add the admin page menu
			add_action( 'network_admin_menu', array( $this, 'mab_admin_page' ) );

			// Handle AJAX requests for saving the admin page settings
			add_action( 'wp_ajax_mab_save_admin_page', array( $this, 'mab_save_admin_page' ) );

		}

	}

	/**
	 * Clear translated user bio data.
	 *
	 * @param   void
	 * @return  void
	 */
	private function mab_clear_data() {

		// Get main site id
		$main_site_id = get_main_site_id();

		// Switch to main site if multisite
		if ( function_exists( 'is_multisite' ) && is_multisite() && get_current_blog_id() != $main_site_id ) {
			switch_to_blog( $main_site_id );
		}

		// Use delete_metadata instead of direct DB query
		delete_metadata( 'user', 0, 'mab_profile_bio%', '', true );

		// Restore to the original blog
		if ( function_exists( 'restore_current_blog' ) ) {
			restore_current_blog();
		}

	}

	/**
	 * Save admin page settings.
	 *
	 * @param   void
	 * @return  void
	 */
	public function mab_save_admin_page() {

		// Nonce validation
		check_ajax_referer( 'mab_nonce_action', 'mab_nonce' );

		// Check if clear data set
		$clear_data = isset( $_POST['clear_data'] ) ? sanitize_text_field( wp_unslash( $_POST['clear_data'] ) ) : false;

		// Get main site id
		$main_site_id = get_main_site_id();

		// Switch to main site if multisite
		if ( function_exists( 'is_multisite' ) && is_multisite() && get_current_blog_id() != $main_site_id ) {
			switch_to_blog( $main_site_id );
		}

		// Update or delete the clear_data option
		if ( $clear_data ) {
			update_option( 'mab_clear_data', true );
		} else {
			delete_option( 'mab_clear_data' );
		}

		// Restore the original blog
		if ( function_exists( 'restore_current_blog' ) ) {
			restore_current_blog();
		}

		// Send json
		wp_send_json_success( __( 'User bio variations set to be cleared on uninstall', 'multisite-author-bio' ) );

	}

	/**
	 * Add settings link on plugin page
	 *
	 * @param   array $links The links array.
	 * @return  array The links array.
	 */
	public function mab_add_settings_link( $links ) {

		// Check if we're in multisite, and it's not in the network admin
		if ( ! is_network_admin() && is_multisite() && plugin_basename( __FILE__ ) === $plugin_file ) {

			// Remove the activate and deactivate links and return links
			unset( $links['activate'] );
			unset( $links['deactivate'] );
			return $links;

		}

		// Add the settings link to the plugin's action links
		$links[] = '<a href="' . $this->mab_get_admin_url() . '">' . __( 'Settings' ) . '</a>';
		return $links;

	}

	/**
	 * Register and enqueue admin stylesheet & scripts
	 *
	 * @param   void
	 * @return  void
	 */
	public function mab_enqueue_scripts() {

		// Only enqueue scripts and styles on the settings page
		if ( strpos( $this->mab_get_current_admin_url(), $this->mab_get_admin_url() ) === false ) {
			return;
		}

		// Enqueue custom stylesheet for user setup
		wp_enqueue_style( 'mab_stylesheet', MAB_PLUGIN_DIR . 'admin/css/admin.css', array(), '1.0.0' );

		// Create a nonce for secure AJAX requests
		$nonce = wp_create_nonce( 'mab_nonce_action' );

		// Enqueue the main admin script (dependent on jQuery)
		wp_enqueue_script( 'mab_script', MAB_PLUGIN_DIR . 'admin/js/admin.js', array( 'jquery' ), '1.0.0', true );

		// Localize the script to pass AJAX URL and nonce to the JavaScript file
		wp_localize_script( 'mab_script', 'mab_obj',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ), // The admin AJAX URL
				'mab_nonce' => $nonce // The nonce for AJAX security
			)
		);

	}

	/**
	 * Register admin page and menu.
	 *
	 * @param   void
	 * @return  void
	 */
	public function mab_admin_page() {
		add_submenu_page(
			'settings.php',
			__( 'Multisite Author Bio', 'multisite-author-bio' ),
			__( 'Multisite Author Bio', 'multisite-author-bio' ),
			'manage_network_options',
			MAB_DIRNAME,
			array( $this, 'mab_admin_page_settings' ),
			100
		);
	}

	/**
	 * Render admin view
	 *
	 * @param   void
	 * @return  void
	 */
	public function mab_admin_page_settings() {
		require_once MAB_DIRNAME . '/admin/view.php';
	}

	/**
	 * Get the current admin url.
	 *
	 * @param   void
	 * @return  void
	 */
	public function mab_get_current_admin_url() {

		// Get the current request URI
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		
		// Ensure there's a valid URI
		if ( empty( $uri ) ) {
			return '';
		}
		
		// Sanitize and clean the URI
		$uri = esc_url_raw( $uri );
	
		// Strip the path to ensure we're only working within the wp-admin area
		$uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );
	
		// Return the sanitized current admin URL, without _wpnonce
		return remove_query_arg( array( '_wpnonce' ), admin_url( $uri ) );

	}

	/**
	 * Add settings link on plugin page
	 *
	 * @param   void
	 * @return  string the admin url
	 */
	public function mab_get_admin_url() {
		return network_admin_url( 'settings.php?page=' . MAB_BASENAME );
	}

	/**
	 * Load the plugin's text domain for translations.
	 * Ensures the plugin is translatable by loading the correct language files.
	 */
	public function mab_load_plugin_textdomain() {
		load_plugin_textdomain( 'multisite-author-bio', false, MAB_BASENAME . '/languages/' );
	}

	/**
	 * Force the plugin to be activated network-wide only.
	 * Prevents the plugin from being activated on individual sites in a multisite network.
	 * 
	 * @param array $allcaps The array of user capabilities.
	 * @param array $cap The specific capability being checked.
	 * @param array $args Additional arguments passed to the capability check.
	 * @return array Modified array of capabilities, ensuring network activation only.
	 */
	public function mab_force_network_activation( $allcaps, $cap, $args ) {

		// Prevent individual sites from activating the plugin
		if ( isset( $args[0] ) && 'activate_plugin' === $args[0] && ! is_network_admin() && is_multisite() ) {
			$allcaps[ $cap[0] ] = false;
		}

		// Return capabilities object
		return $allcaps;

	}

}