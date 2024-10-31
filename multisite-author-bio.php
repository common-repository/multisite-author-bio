<?php
/**
* Plugin Name:  Multisite Author Bio
* Description:  Allows you to add unique user biographical information for each Multisite instance.
* Version:      1.0.3
* Author:       CodeAdapted
* Author URI:   https://codeadapted.com
* Network:      true
* License:      GPL2 or later
* License URI:  https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:  multisite-author-bio
* Domain Path: /languages/
*
* @package     MultisiteAuthorBio
* @author      CodeAdapted
* @copyright   Copyright (c) 2024, CodeAdapted LLC
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once __DIR__ . '/classes/mab-plugin.php';
require_once __DIR__ . '/classes/mab-user-setup.php';
require_once __DIR__ . '/classes/mab-frontend.php';

if ( ! class_exists( 'MultisiteAuthorBio' ) ) :

	class MultisiteAuthorBio {

		/** @var string The plugin version number. */
		var $version = '1.0.2';

		/** @var string Shortcuts. */
		var $plugin;
		var $frontend;

		/**
		 * __construct
		 *
		 * A dummy constructor to ensure MultisiteAuthorBio is only setup once.
		 *
		 * @param   void
		 * @return  void
		 */
		function __construct() {
			// Do nothing.
		}

		/**
		 * initialize
		 *
		 * Sets up the MultisiteAuthorBio plugin.
		 *
		 * @param   void
		 * @return  void
		 */
		function initialize() {

			// Define constants.
			$this->define( 'MAB', true );
			$this->define( 'MAB_FILE', __FILE__ );
			$this->define( 'MAB_DIRNAME', dirname( __FILE__ ) );
			$this->define( 'MAB_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
			$this->define( 'MAB_BASENAME', basename( dirname( __FILE__ ) ) );

			// Do all the plugin stuff.
			$this->plugin    = new MAB_Plugin();

			// Allow filter to change user bio
			$this->frontend = new MAB_Frontend();

			if ( is_admin() ) {

				// load up our admin classes
				$admin     = new MAB_UserSetup();

			}

		}

		/**
		 * __call
		 *
		 * Sugar function to access class properties
		 *
		 * @param   string $name The property name.
		 * @return  void
		 */
		public function __call( $name, $arguments ) {
			return $this->{$name};
		}

		/**
		 * define
		 *
		 * Defines a constant if doesnt already exist.
		 *
		 * @param   string $name The constant name.
		 * @param   mixed  $value The constant value.
		 * @return  void
		 */
		function define( $name, $value = true ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

	}

	/*
	* mab
	*
	* The main function responsible for returning the one true MultisiteAuthorBio Instance to functions everywhere.
	* Use this function like you would a global variable, except without needing to declare the global.
	*
	* @param   void
	* @return  MultisiteAuthorBio
	*/
	function mab() {
		global $mab;
		// Instantiate only once.
		if ( ! isset( $mab ) ) {
			$mab = new MultisiteAuthorBio();
			$mab->initialize();
		}
		return $mab;
	}

	// Instantiate.
	mab();

endif; // class_exists check
