<?php
/**
 * Shopify eCommerce Plugin - Shopping Cart Settings
 * @version 1.1.4
 * @package Shopify eCommerce Plugin - Shopping Cart
 */

class SECP_Settings {
	/**
	 * Parent plugin class
	 *
	 * @var    class
	 * @since  1.0.0
	 */
	protected $plugin = null;

	/**
	 * Option key, and option page slug
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $key = 'shopify_ecommerce_plugin_settings';

	/**
	 * Options page metabox id
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $metabox_id = 'shopify_ecommerce_plugin_settings_metabox';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 * @param  object $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  1.0.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
	}

	/**
	 * Register our setting to WP
	 *
	 * @since  1.0.0
	 */
	public function admin_init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Enqueue admin styles
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue_scripts() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'secp-admin', shopify_ecommerce_plugin()->url( 'assets/css/styles' . $min . '.css' ), array(), '160223' );
	}

	/**
	 * Add menu options page
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
		add_menu_page(
			__( 'Shopify', 'shopify-ecommerce-shopping-cart' ),
			__( 'Shopify', 'shopify-ecommerce-shopping-cart' ),
			'manage_options',
			$this->key,
			array( $this, 'admin_page_display' ),
			$this->plugin->url( 'assets/images/shopify_icon_small2.png' )
		);
		$this->options_page = add_submenu_page(
			$this->key,
			__( 'Shopify', 'shopify-ecommerce-shopping-cart' ),
			__( 'Settings', 'shopify-ecommerce-shopping-cart' ),
			'manage_options',
			$this->key,
			array( $this, 'admin_page_display' )
		);
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 *
	 * @since  1.0.0
	 */
	public function admin_page_display() {
		$settings_url = "https://widgets.shopifyapps.com/embed_admin/settings?ref=".AFFILIATE_CODE;
		?>
		<iframe class="secp-settings-iframe" src="<?php echo esc_url( $settings_url ); ?>"></iframe>
		<?php
	}
}
