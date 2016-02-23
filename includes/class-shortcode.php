<?php
/**
 * Shopify Buy Button Shortcode
 * @version 0.1.0
 * @package Shopify Buy Button
 */

class SBB_Shortcode {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since 0.1.0
	 */
	protected $plugin = null;

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
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'media_buttons', array( $this, 'media_buttons' ), 10 );
		add_shortcode( 'shopify-buy-button', array( $this, 'shortcode' ) );
	}

	/**
	 * Add Shopify Buy Button next to the add media button.
	 *
	 * @since 0.1.0
	 * @param string $editor_id ID of content editor for button.
	 * @return void
	 */
	public function media_buttons( $editor_id ) {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'sbb-admin-shortcode', $this->plugin->url( 'assets/js/admin-shortcode' . $min . '.js' ), array( 'jquery' ), '160223', true );
		wp_localize_script( 'sbb-admin-shortcode', 'sbbAdminModal', array(
			'modal' => $this->plugin->modal->get_modal(),
		) );

		wp_enqueue_style( 'sbb-admin', $this->plugin->url( 'assets/css/shopify-buy-button' . $min . '.css' ), array(), '160223' );

		?>
		<button id="sbb-add-shortcode" class="button sbb-add-shortcode" data-editor-id="<?php echo esc_attr( $editor_id ); ?>">
			<?php esc_html_e( 'Add Product', 'shopify-buy-button' ); ?>
		</button>
		<?php
	}

	/**
	 * Shortcode rendering
	 * Just passes arguments to output function.
	 *
	 * @since 0.1.0
	 * @param  array $args Shortcode attributes
	 * @return string      HTML output.
	 */
	public function shortcode( $args ) {
		return $this->plugin->output->get_button( $args );
	}
}
