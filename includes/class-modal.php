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
	}

	/**
	 * Get the buy button creation modal
	 *
	 * @since NEXT
	 * @return string HTML markup of modal.
	 */
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
				<div class="sbb-modal-close"><div class="screen-reader-text"><?php esc_attr_e( 'Close', 'shopify-buy-button' ); ?></div></div>
				<iframe src="<?php echo esc_url( $iframe_url ); ?>" frameborder="0" class="sbb-modal-iframe"></iframe>
				<div class="sbb-modal-secondpage">
					<div class="sbb-modal-header">
						<h2><?php esc_html_e( 'Embed Type', 'shopify-buy-button' ); ?></h2>
					</div>
					<div class="sbb-modal-content">
						<label class="sbb-show-label">
							<span class="sbb-show-preview">
							</span>
							<input class="sbb-show" type="radio" name="sbb-show" value="all" checked="checked">
							<?php esc_html_e( 'Product image, price and button', 'shopify-buy-button' ); ?>
						</label>
						<label class="sbb-show-label">
							<span class="sbb-show-preview">
							</span>
							<input class="sbb-show" type="radio" name="sbb-show" value="button-only">
							<?php esc_html_e( 'Buy button only', 'shopify-buy-button' ); ?>
						</label>
					</div>
					<div class="sbb-modal-footer">
						<button class="button button-primary sbb-modal-add-button"><?php esc_html_e( 'Ok', 'shopify-buy-button' ); ?></button>
					</div>
				</div>
			</div>
			<div class="sbb-modal-background"></div>
		</div>
		<?php
		return ob_get_clean();
	}
}
