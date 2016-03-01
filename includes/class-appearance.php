<?php
/**
 * Shopify Buy Button Appearance
 * @version 0.1.0
 * @package Shopify Buy Button
 */

require_once dirname(__FILE__) . '/../vendor/cmb2/init.php';

class SBB_Appearance {
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
	protected $key = 'shopify_buy_button_appearance';

	/**
	 * Options page metabox id
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $metabox_id = 'shopify_buy_button_appearance_metabox';

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
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();

		$this->title = __( 'Appearance', 'shopify-buy-button' );
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}

	/**
	 * Register our setting to WP
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function admin_init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function add_options_page() {
		$this->options_page = add_submenu_page(
			'shopify_buy_button_settings',
			$this->title,
			$this->title,
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
	 * @since  0.1.0
	 * @return void
	 */
	public function admin_page_display() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'sbb-admin-appearance', $this->plugin->url( 'assets/js/admin-appearance' . $min . '.js' ), array( 'jquery' ), '160223', true );
		?>
		<div class="wrap cmb2-options-page <?php echo esc_attr( $this->key ); ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<div class="sbb-appearance">
				<div class="sbb-appearance-right">
					<h4><?php _e( 'Preview', 'shopify-buy-button' ); ?></h4>
					<iframe class="sbb-appearance-preview" src="<?php
					echo esc_url( add_query_arg( array(
						'shop'           => 'embeds.myshopify.com',
						'product_handle' => 'yello-w',
					), site_url() ) );
					?>"></iframe>
				</div>
				<div class="sbb-appearance-left">
					<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add custom fields to the options page.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function add_options_page_metabox() {

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
			'name'    => __( 'Button', 'shopify-buy-button' ),
			'id'      => 'button_background_color',
			'type'    => 'colorpicker',
			'default' => '7db461',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Button Text', 'shopify-buy-button' ),
			'id'      => 'button_text_color',
			'type'    => 'colorpicker',
			'default' => 'ffffff',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Title Color', 'shopify-buy-button' ),
			'id'      => 'product_title_color',
			'type'    => 'colorpicker',
			'default' => '000000',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Background', 'shopify-buy-button' ),
			'id'      => 'background_color',
			'type'    => 'colorpicker',
			'default' => 'ffffff',
		) );

		$cmb->add_field( array(
			'desc'    => __( 'Background', 'shopify-buy-button' ),
			'id'      => 'background',
			'type'    => 'checkbox',
			'default' => false,
		) );

		$cmb->add_field( array(
			'name'    => __( 'Button Text', 'shopify-buy-button' ),
			'id'      => 'buy_button_text',
			'type'    => 'text',
			'default' => __( 'Buy now', 'shopify-buy-button' ),
		) );

	}
}
