<?php
/**
 * Author: Gonçalo Neves
 * Author URI: https://github.com/goncaloneves
 * Plugin Name: REST Redirect Domain
 * Plugin URI: https://github.com/goncaloneves/rest-domain-redirect
 * Description: Redirects users to root domain before template loading. Useful when the WordPress installation is a sub-folder REST API and you want to restrict its public access. This behaviour won't affect requests to administration and other plugin's output (ex. JSON REST API plugin on /wp-json).
 * License: License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Version: 1.0
 * Text Domain: rest-domain-redirect
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly
}

if ( ! class_exists( 'Rest_Redirect_Domain' ) ) {

	final class Rest_Redirect_Domain {

		private static $_instance;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {
			$this->actions();
        	load_plugin_textdomain( 'rest-domain-redirect' );
		}

		/**
         * Cloning is forbidden.
         */
        public function __clone() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'rest-domain-redirect' ), '1.0' );
        }

        /**
         * Unserializing instances of this class is forbidden.
         */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'rest-domain-redirect' ), '1.0' );
        }

      	/**
         * Action Hooks.
         */
        private function actions() {
        	add_action( 'template_redirect', array( $this , 'rest_redirect_domain' ) );
        }

        /**
         * Redirect To Index function.
         */
        public function rest_redirect_domain() {
        	$root_domain_url = 'http://' . $_SERVER['SERVER_NAME'];
        	$wp_url = get_site_url();

        	// Prevent loop if WordPress exists in root path.
        	if ( $root_domain_url !== $wp_url ) {
        		wp_redirect( $root_domain_url, 301 );
    			exit();
        	}
        }
	}
}

function Run_Rest_Redirect_Domain_Class() {
	return Rest_Redirect_Domain::instance();
}

Run_Rest_Redirect_Domain_Class();