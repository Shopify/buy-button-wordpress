<?php
/**
 * Shopify Buy Button Modal
 * @version 0.1.0
 * @package Shopify Buy Button
 */

class SBB_Modal {
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
	}

	public function get_modal() {
		$iframe_url = 'https://widgets.shopifyapps.com/embed_admin/embeds/picker';

		$site = get_option( 'sbb-connected-site', false );
		if ( $site ) {
			$iframe_url = add_query_arg( 'shop', $site, $iframe_url );
		}

		$iframe_url = apply_filters( 'sbb_modal_iframe_url', $iframe_url, $site );

		ob_start();
		?>
		<div class="sbb-modal-wrap">
			<div class="sbb-modal">
				<div class="sbb-modal-close"><?php esc_attr_e( 'Close', 'shopify-buy-button' ); ?></div>
				<iframe src="<?php echo esc_url( $iframe_url ); ?>" frameborder="0" class="sbb-modal-iframe"></iframe>
			</div>
			<div class="sbb-modal-background"></div>
		</div>
		<?php
		return ob_get_clean();
	}
}
