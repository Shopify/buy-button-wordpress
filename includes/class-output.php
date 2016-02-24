<?php
/**
 * Shopify Buy Button Output
 * @version 0.1.0
 * @package Shopify Buy Button
 */

class SBB_Output {
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
		add_action( 'init', array( $this, 'button_endpoint' ), 30 );
	}

	/**
	 * Get markup for frontend buy button iframe.
	 *
	 * @since 0.1.0
	 * @param  array $args Arguments for buy button
	 * @return string      HTML markup
	 */
	public function get_button( $args ) {
		static $js_added = false;

		/**
		 * Arguments for buy button data attributes
		 *
		 * @see https://docs.shopify.com/manual/sell-online/buy-button/edit-delete
		 * @var array
		 */
		$args = wp_parse_args( $args, array(
			// * Provided by iframe -- product/collection
			'embed_type'                          => 'product',
			// * Provided by iframe -- The myshopify domain (such as storename.myshopify.com) connected to the button. Your Shopify domain
			'shop'                                => get_option( 'sbb-connected-site', false ),
			// * Provided by iframe -- The product_handle of the featured product, which is based on the product's title. Each of your products has a unique handle in Shopify.
			'product_handle'                      => '',
			// The title of the featured product.
			'product_name'                        => '',
			// The maximum width of the embed. Can be 'compact' (230px) or 'regular' (450px).
			'display_size'                        => 'compact',
			// Whether it's a full product embed ('true') or buy button only ('false').
			'has_image'                           => 'true',
			// Where the buy button links to. Can be 'checkout', 'product', or 'cart'. If you want the buy button to connect with an embedded cart on the same page, data-redirect_to must be cart. (product_modal is a recent addition that has not yet been added to the docs)
			'redirect_to'                         => 'checkout',
			// The text displayed on the buy button.
			'buy_button_text'                     => __( 'Buy now', 'shopify' ),
			// The hex code of the color of the buy button, without the #. Can be three hex characters or six.
			'button_background_color'             => '7db461',
			// The hex code of the color of the buy button's text, without the #. Can be three hex characters or six.
			'button_text_color'                   => 'ffffff',
			// The background color of the area surrounding the Buy button. It can be a hex code (per rules above), or transparent. If transparent, no padding is applied to the embed's content.
			'background_color'                    => 'transparent',
			// The text that appears when a product is out of stock.
			'buy_button_out_of_stock_text'        => __( 'Out of Stock', 'shopify' ),
			// The text that appears when a product is unavailable.
			'buy_button_product_unavailable_text' => __( 'Unavailable', 'shopify' ),
			// The hex code of the color of the product title's text, without the #. Can be three hex characters or six.
			'product_title_color'                 => '000000',
			// Additional non-data-attribute params
			'no_script_url'                       => '', // https://<shop>.myshopify.com/cart/<cart-number>
			'no_script_text'                      => sprintf( __( 'Buy %s', 'shopify' ), '[product_name]' ), // Buy <product_name>
		) );

		$args = apply_filters( 'sbb_output_args', $args );

		if ( empty( $args['shop'] ) || empty( $args['product_handle'] ) ) {
			// no button if there is no product id or shop url
			return;
		}

		if ( 'collection' == $args['embed_type'] ) {
			$args['collection_handle'] = $args['product_handle'];
		}

		// Override for whether or not to display the product price. Can be true or false.	The current value of data-has_image
		$args['show_product_price'] = ! empty( $args['show_product_price'] ) ? $args['show_product_price'] : $args['has_image'];
		// Override for whether or not to display the product title. Can be true or false.	The current value of data-has_image
		$args['show_product_title'] = ! empty( $args['show_product_title'] ) ? $args['show_product_title'] : $args['has_image'];

		$no_script_text = str_replace( '[product_name]', $args['product_name'], wp_kses_post( $args['no_script_text'] ) );
		$no_script_url = $args['no_script_url'];
		unset( $args['no_script_url'], $args['no_script_text'] );

		$attributes = '';
		foreach ( $args as $key => $value ) {
			$attributes .= sprintf( ' data-%s="%s"', esc_html( $key ), esc_attr( $value ) );
		}

		ob_start();
		?>
		<div<?php echo $attributes; ?>></div>
		<noscript><a href="<?php echo esc_url( $no_script_url ); ?>" target="_blank"><?php echo $no_script_text; ?></a></noscript>
		<?php

		if ( ! $js_added ) {
			?><script type="text/javascript"> document.getElementById('ShopifyEmbedScript') || document.write('<script type="text/javascript" src="https://widgets.shopifyapps.com/assets/widgets/embed/client.js" id="ShopifyEmbedScript"><\/script>'); </script><?php
			$js_added = true;
		}

		return ob_get_clean();
	}

	public function button_endpoint() {
		if ( ! current_user_can( 'edit_posts' )
			|| empty( $_GET['product_handle'] )
			|| empty( $_GET['embed_type'] ) ) {
			return;
		}

		echo $this->get_button( array(
			'product_handle' => sanitize_text_field( $_GET['product_handle'] ),
			'shop' => isset( $_GET['shop'] ) ? sanitize_text_field( $_GET['shop'] ) : get_option( 'sbb-connected-site', false ),
			'embed_type' => sanitize_text_field( $_GET['embed_type'] ),
		) );

		die();
	}
}
