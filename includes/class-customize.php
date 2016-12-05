<?php
/**
 * Shopify eCommerce Plugin - Shopping Cart Customize
 * @version 1.1.4
 * @package Shopify eCommerce Plugin - Shopping Cart
 */

require_once dirname( __FILE__ ) . '/../vendor/cmb2/init.php';

class SECP_Customize {
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
	protected $key = 'shopify_ecommerce_plugin_customize';

	/**
	 * Options page metabox id
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $metabox_id = 'shopify_ecommerce_plugin_customize_metabox';

	/**
	 * Options Page title
	 *
	 * @var    string
	 * @since  1.0.0
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
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
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
	 * Add menu options page
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
		$this->options_page = add_submenu_page(
			'shopify_ecommerce_plugin_settings',
			__( 'Customize', 'shopify-ecommerce-shopping-cart' ),
			__( 'Customize', 'shopify-ecommerce-shopping-cart' ),
			'manage_options',
			$this->key,
			array( $this, 'admin_page_display' )
		);

		// Include CMB CSS in the head to avoid FOUC.
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 *
	 * @since  1.0.0
	 */
	public function admin_page_display() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'secp-admin-customize', $this->plugin->url( 'assets/js/admin-customize' . $min . '.js' ), array( 'jquery' ), '160223', true );
		?>
		<div class="wrap cmb2-options-page <?php echo esc_attr( $this->key ); ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<div class="secp-customize">
				<div class="secp-customize-right">
					<h4><?php _e( 'Preview', 'shopify-ecommerce-shopping-cart' ); ?></h4>
					<iframe class="secp-customize-preview" src="<?php
					echo esc_url( add_query_arg( array(
						'shop'           => 'embeds.myshopify.com',
						'product_handle' => 'yello-w',
						'show_cart'      => true,
						'vcenter'        => true,
					), site_url() ) );
					?>"></iframe>
				</div>
				<div class="secp-customize-left">
					<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add custom fields to the options page.
	 *
	 * @since  1.0.0
	 */
	public function add_options_page_metabox() {
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				'key'   => 'options-page',
				'value' => array( $this->key ),
			),
		) );

		/*
		Add your fields here
		*/
		$cmb->add_field( array(
			'name'    => __( 'Colors', 'shopify-ecommerce-shopping-cart' ),
			'id'   => 'color_title',
			'type'    => 'title',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Button color', 'shopify-ecommerce-shopping-cart' ),
			'id'      => 'button_background_color',
			'type'    => 'colorpicker',
			'default' => '7db461',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Button text', 'shopify-ecommerce-shopping-cart' ),
			'id'      => 'button_text_color',
			'type'    => 'colorpicker',
			'default' => 'ffffff',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Accent', 'shopify-ecommerce-shopping-cart' ),
			'id'      => 'accent_color',
			'type'    => 'colorpicker',
			'default' => '000000',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Text', 'shopify-ecommerce-shopping-cart' ),
			'id'      => 'text_color',
			'type'    => 'colorpicker',
			'default' => '000000',
		) );

		$cmb->add_field( array(
			'desc'    => __( 'Background', 'shopify-ecommerce-shopping-cart' ),
			'id'      => 'background',
			'type'    => 'checkbox',
			'default' => false,
		) );

		$cmb->add_field( array(
			'name'    => __( 'Background', 'shopify-ecommerce-shopping-cart' ),
			'id'      => 'background_color',
			'type'    => 'colorpicker',
			'default' => 'ffffff',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Button text', 'shopify-ecommerce-shopping-cart' ),
			'id'      => 'buy_button_text',
			'type'    => 'text',
			'default' => __( 'Buy now', 'shopify-ecommerce-shopping-cart' ),
		) );

		$cmb->add_field( array(
			'name'    => __( 'Cart title text', 'shopify-ecommerce-shopping-cart' ),
			'id'      => 'cart_title',
			'type'    => 'text',
			'default' => __( 'Your cart', 'shopify-ecommerce-shopping-cart' ),
		) );

		$cmb->add_field( array(
			'name'    => __( 'Checkout button text', 'shopify-ecommerce-shopping-cart' ),
			'id'      => 'checkout_button_text',
			'type'    => 'text',
			'default' => __( 'Checkout', 'shopify-ecommerce-shopping-cart' ),
		) );

		$cmb->add_field( array(
			'name'    => __( 'Where this button links to (single product only)', 'shopify-ecommerce-shopping-cart' ),
			'id'      => 'redirect_to',
			'type'    => 'select',
			'default' => 'checkout',
			'options' => array(
				'checkout' => __( 'Checkout', 'shopify-ecommerce-shopping-cart' ),
				'modal'  => __( 'Product Modal', 'shopify-ecommerce-shopping-cart' ),
				'cart'     => __( 'Cart', 'shopify-ecommerce-shopping-cart' ),
			),
		) );

	}

	/**
	 * Register settings notices for display
	 *
	 * @since  1.0.0
	 * @param  int   $object_id Option key.
	 * @param  array $updated   Array of updated fields.
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}
		add_settings_error( $this->key . '-notices', '', __( 'Customize updated.', 'shopify-ecommerce-shopping-cart' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}
}
