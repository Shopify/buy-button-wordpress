<?php
/**
 * Shopify eCommerce Plugin - Shopping Cart Settings
 * @version 0.1.0
 * @package Shopify eCommerce Plugin - Shopping Cart
 */

class SECP_Settings {
	/**
	 * Parent plugin class
	 *
	 * @var    class
	 * @since  NEXT
	 */
	protected $plugin = null;

	/**
	 * Option key, and option page slug
	 *
	 * @var    string
	 * @since  NEXT
	 */
	protected $key = 'shopify_ecommerce_plugin_settings';

	/**
	 * Options page metabox id
	 *
	 * @var    string
	 * @since  NEXT
	 */
	protected $metabox_id = 'shopify_ecommerce_plugin_settings_metabox';

	/**
	 * Options Page title
	 *
	 * @var    string
	 * @since  NEXT
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();

		$this->title = __( 'Shopify', 'shopify-ecommerce-shopping-cart' );
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
	}

	/**
	 * Register our setting to WP
	 *
	 * @since  NEXT
	 */
	public function admin_init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Enqueue admin styles
	 *
	 * @since NEXT
	 */
	public function admin_enqueue_scripts() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'secp-admin', shopify_ecommerce_plugin()->url( 'assets/css/styles' . $min . '.css' ), array(), '160223' );
	}

	/**
	 * Add menu options page
	 *
	 * @since  NEXT
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page(
			$this->title,
			$this->title,
			'manage_options',
			$this->key,
			array( $this, 'admin_page_display' ),
			$this->plugin->url( 'assets/images/shopify_icon_small2.png' )
		);
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 *
	 * @since  NEXT
	 */
	public function admin_page_display() {
		?>
		<iframe class="secp-settings-iframe" src="https://widgets.shopifyapps.com/embed_admin/settings"></iframe>
		<?php
	}
}
