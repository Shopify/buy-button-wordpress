<?php
/**
 * Shopify Buy Button Settings
 * @version 0.1.0
 * @package Shopify Buy Button
 */

class SBB_Settings {
	/**
	 * Parent plugin class
	 *
	 * @var    class
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * Option key, and option page slug
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $key = 'shopify_buy_button_settings';

	/**
	 * Options page metabox id
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $metabox_id = 'shopify_buy_button_settings_metabox';

	/**
	 * Options Page title
	 *
	 * @var    string
	 * @since  0.1.0
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
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();

		$this->title = __( 'Shopify', 'shopify-buy-button' );
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
	}

	/**
	 * Register our setting to WP
	 *
	 * @since  0.1.0
	 */
	public function admin_init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Enqueue admin styles
	 *
	 * @since 0.1.0
	 */
	public function admin_enqueue_scripts() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'sbb-admin', shopify_buy_button()->url( 'assets/css/shopify-buy-button' . $min . '.css' ), array(), '160223' );
	}

	/**
	 * Add menu options page
	 *
	 * @since  0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page(
			$this->title,
			$this->title,
			'manage_options',
			$this->key,
			array( $this, 'admin_page_display' ),
			$this->plugin->url( 'assets/images/shopify_icon_small.png' )
		);
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 *
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<iframe class="sbb-settings-iframe" src="https://widgets.shopifyapps.com/embed_admin/settings"></iframe>
		<?php
	}
}
