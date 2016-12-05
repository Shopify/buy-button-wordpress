<?php
	include_once("affiliate_config.php");
/**
 * Plugin Name: Shopify eCommerce Plugin - Shopping Cart
 * Plugin URI:  https://www.shopify.com/buy-button
 * Description: Sell products on your WordPress site using Shopify’s powerful, easy-to-use Buy Buttons.
 * Version:     1.1.4
 * Author:      Shopify
 * Author URI:  https://www.shopify.com/
 * Donate link: https://www.shopify.com/buy-button
 * License:     GPLv2
 * Text Domain: shopify-ecommerce-shopping-cart
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2016 Shopify (email : buybutton@shopify.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using generator-plugin-wp
 */


/**
 * Autoloads files with classes when needed
 *
 * @since  1.0.0
 * @param  string $class_name Name of the class being requested.
 * @return void
 */
function shopify_ecommerce_plugin_autoload_classes( $class_name ) {
	if ( 0 !== strpos( $class_name, 'SECP_' ) ) {
		return;
	}

	$filename = strtolower( str_replace(
		'_', '-',
		substr( $class_name, strlen( 'SECP_' ) )
	) );

	Shopify_ECommerce_Plugin::include_file( $filename );
}
spl_autoload_register( 'shopify_ecommerce_plugin_autoload_classes' );

/**
 * Main initiation class
 *
 * @since  1.0.0
 * @var  string $version  Plugin version
 * @var  string $basename Plugin basename
 * @var  string $url      Plugin URL
 * @var  string $path     Plugin Path
 */
class Shopify_ECommerce_Plugin {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  1.0.0
	 */
	const VERSION = '1.1.4';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var Shopify_ECommerce_Plugin
	 * @since  1.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Instance of SECP_Output
	 *
	 * @var SECP_Output
	 */
	protected $output;

	/**
	 * Instance of SECP_Shortcode
	 *
	 * @var SECP_Shortcode
	 */
	protected $shortcode;

	/**
	 * Instance of SECP_Modal
	 *
	 * @var SECP_Modal
	 */
	protected $modal;

	/**
	 * Instance of SECP_Settings
	 *
	 * @var SECP_Settings
	 */
	protected $settings;

	/**
	 * Instance of SECP_Customize
	 *
	 * @var SECP_Customize
	 */
	protected $customize;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  1.0.0
	 * @return Shopify_ECommerce_Plugin A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  1.0.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );

		$this->plugin_classes();
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  1.0.0
	 */
	public function plugin_classes() {
		// Attach other plugin classes to the base plugin class.
		$this->output     = new SECP_Output( $this );
		$this->shortcode  = new SECP_Shortcode( $this );
		$this->modal      = new SECP_Modal( $this );
		$this->settings   = new SECP_Settings( $this );
		$this->customize  = new SECP_Customize( $this );
		require( self::dir( 'includes/class-widget.php' ) );
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters
	 *
	 * @since  1.0.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init hooks
	 *
	 * @since  1.0.0
	 */
	public function init() {
		if ( $this->check_requirements() ) {
			load_plugin_textdomain( 'shopify-ecommerce-shopping-cart', false, dirname( $this->basename ) . '/languages/' );
		}
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  1.0.0
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {

			// Add a dashboard notice.
			add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

			// Deactivate our plugin.
			add_action( 'admin_init', array( $this, 'deactivate_me' ) );

			return false;
		}

		return true;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  1.0.0
	 */
	public function deactivate_me() {
		deactivate_plugins( $this->basename );
	}

	/**
	 * Check that all plugin requirements are met
	 *
	 * @since  1.0.0
	 * @return boolean True if requirements are met.
	 */
	public static function meets_requirements() {
		// Do checks for required classes / functions
		// function_exists('') & class_exists('').
		// We have met all requirements.
		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met
	 *
	 * @since  1.0.0
	 */
	public function requirements_not_met_notice() {
		// Output our error.
		echo '<div id="message" class="error">';
		echo '<p>' . sprintf( __( 'Shopify eCommerce Plugin - Shopping Cart is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'shopify-ecommerce-shopping-cart' ), admin_url( 'plugins.php' ) ) . '</p>';
		echo '</div>';
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  1.0.0
	 * @param string $field Field to get.
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'output':
			case 'shortcode':
			case 'modal':
			case 'settings':
			case 'customize':
				return $this->$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory
	 *
	 * @since  1.0.0
	 * @param  string $filename Name of the file to be included.
	 * @return bool   Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( 'includes/class-'. $filename .'.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory
	 *
	 * @since  1.0.0
	 * @param  string $path (optional) appended path.
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url
	 *
	 * @since  1.0.0
	 * @param  string $path (optional) appended path.
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}
}

/**
 * Grab the Shopify_ECommerce_Plugin object and return it.
 * Wrapper for Shopify_ECommerce_Plugin::get_instance()
 *
 * @since  1.0.0
 * @return Shopify_ECommerce_Plugin  Singleton instance of plugin class.
 */
function shopify_ecommerce_plugin() {
	return Shopify_ECommerce_Plugin::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( shopify_ecommerce_plugin(), 'hooks' ) );
