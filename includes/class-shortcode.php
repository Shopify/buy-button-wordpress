<?php
/**
 * Shopify eCommerce Plugin - Shopping Cart Shortcode
 * @version 0.1.0
 * @package Shopify eCommerce Plugin - Shopping Cart
 */

class SECP_Shortcode {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 */
	public function hooks() {
		add_action( 'media_buttons', array( $this, 'media_buttons' ), 10 );
		add_shortcode( 'shopify', array( $this, 'shortcode' ) );
	}

	/**
	 * Add Shopify eCommerce Plugin - Shopping Cart next to the add media button.
	 *
	 * @since NEXT
	 * @param string $editor_id ID of content editor for button.
	 */
	public function media_buttons( $editor_id ) {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'secp-admin-shortcode', $this->plugin->url( 'assets/js/admin-shortcode' . $min . '.js' ), array( 'jquery' ), '160223', true );
		wp_localize_script( 'secp-admin-shortcode', 'secpAdminModal', array(
			'modal' => $this->plugin->modal->get_modal(),
		) );

		?>
		<button id="secp-add-shortcode" class="button secp-add-shortcode" data-editor-id="<?php echo esc_attr( $editor_id ); ?>">
			<?php esc_html_e( 'Add Product', 'shopify-ecommerce-plugin-shopping-cart' ); ?>
		</button>
		<?php
	}

	/**
	 * Shortcode rendering
	 * Just passes arguments to output function.
	 *
	 * @since NEXT
	 * @param  array $args Shortcode attributes.
	 * @return string      HTML output.
	 */
	public function shortcode( $args ) {
		return $this->plugin->output->get_button( $args );
	}
}
