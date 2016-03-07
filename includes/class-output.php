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
	 * Has the shopify js been added?
	 *
	 * @var boolean
	 * @since 0.1.0
	 */
	private static $js_added = false;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  0.1.0
	 */
	public function hooks() {
		add_action( 'cmb2_init', array( $this, 'button_endpoint' ), 30 );
		add_action( 'wp_footer', array( $this, 'embed_cart' ), 30 );
	}

	/**
	 * Convert array of attributes to string of html data attributes.
	 *
	 * @since 0.1.0
	 * @param  array $args Array of attributes to convert to data attributes.
	 * @return string      HTML attributes.
	 */
	public function array_to_data_attributes( $args ) {
		$attributes = '';
		foreach ( $args as $key => $value ) {
			if ( !empty( $value ) ) {
				$attributes .= sprintf( ' data-%s="%s"', esc_html( $key ), esc_attr( $value ) );
			}
		}

		return $attributes;
	}

	/**
	 * Get shopify embed markup.
	 *
	 * @since 0.1.0
	 * @param  array $args data arguments.
	 * @return string      HTML markup.
	 */
	public function get_embed( $args ) {
		$no_script_text = str_replace( '[product_name]', $args['product_name'], wp_kses_post( $args['no_script_text'] ) );
		$no_script_url = $args['no_script_url'];
		unset( $args['no_script_url'], $args['no_script_text'] );

		ob_start();
		?>
		<div<?php echo $this->array_to_data_attributes( $args ); ?>></div>
		<noscript><a href="<?php echo esc_url( $no_script_url ); ?>" target="_blank"><?php echo $no_script_text; ?></a></noscript>
		<?php

		if ( ! self::$js_added ) {
			?><script type="text/javascript"> document.getElementById('ShopifyEmbedScript') || document.write('<script type="text/javascript" src="https://widgets.shopifyapps.com/assets/widgets/embed/client.js" id="ShopifyEmbedScript"><\/script>'); </script><?php
			self::$js_added = true;
		}

		return ob_get_clean();
	}

	/**
	 * Get markup for frontend buy button iframe.
	 *
	 * @since 0.1.0
	 * @param  array $args Arguments for buy button
	 * @return string      HTML markup
	 */
	public function get_button( $args ) {
		if ( isset( $args['text_color'] ) ) {
			$args['product_title_color'] = $args['text_color'];
		}

		if ( empty( $args['background'] ) ) {
			unset( $args['background_color'] );
		}

		if ( ! empty( $args['show'] ) ) {
			if ( $args['show'] == 'button-only' ) {
				$args['has_image'] = 'false';
			}
		}

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
			'shop'                                => '',
			// * Provided by iframe -- The product_handle of the featured product, which is based on the product's title. Each of your products has a unique handle in Shopify.
			'product_handle'                      => '',
			'product_name'                        => '',
			'display_size'                        => 'compact',
			'has_image'                           => 'true',
			'redirect_to'                         => cmb2_get_option( 'shopify_buy_button_appearance', 'redirect_to' ),
			'buy_button_text'                     => cmb2_get_option( 'shopify_buy_button_appearance', 'buy_button_text' ),
			'button_background_color'             => substr( cmb2_get_option( 'shopify_buy_button_appearance', 'button_background_color' ), 1 ),
			'button_text_color'                   => substr( cmb2_get_option( 'shopify_buy_button_appearance', 'button_text_color' ), 1 ),
			'background_color'                    => ! empty( $args['background'] ) || cmb2_get_option( 'shopify_buy_button_appearance', 'background' ) ? substr( cmb2_get_option( 'shopify_buy_button_appearance', 'background_color' ), 1 ) : 'transparent',
			'buy_button_out_of_stock_text'        => __( 'Out of Stock', 'shopify' ),
			'buy_button_product_unavailable_text' => __( 'Unavailable', 'shopify' ),
			'product_title_color'                 => substr( cmb2_get_option( 'shopify_buy_button_appearance', 'text_color' ), 1 ),
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

		return $this->get_embed( $args );
	}

	/**
	 * Get the cart embed
	 *
	 * @since 0.1.0
	 * @param  array $args Cart arguments
	 * @return string      HTML embed markup
	 */
	public function get_cart( $args ) {
		$args = wp_parse_args( $args, array(
			'shop' => '',
			'checkout_button_text' => cmb2_get_option( 'shopify_buy_button_appearance', 'checkout_button_text' ),
			'button_text_color' => substr( cmb2_get_option( 'shopify_buy_button_appearance', 'button_text_color' ), 1 ),
			'button_background_color' => substr( cmb2_get_option( 'shopify_buy_button_appearance', 'button_background_color' ), 1 ),
			'background_color' => substr( cmb2_get_option( 'shopify_buy_button_appearance', 'background_color' ), 1 ),
			'text_color' => substr( cmb2_get_option( 'shopify_buy_button_appearance', 'text_color' ), 1 ),
			'accent_color' => substr( cmb2_get_option( 'shopify_buy_button_appearance', 'accent_color' ), 1 ),
			'cart_title' => cmb2_get_option( 'shopify_buy_button_appearance', 'cart_title' ),
			'cart_total_text' => '',
			'discount_notice_text' => '',
			'sticky' => '',
			'empty_cart_text' => '',
			'next_page_button_text' => '',
		) );

		$args['embed_type'] = 'cart';
		$args['sticky'] = 'true';

		return $this->get_embed( $args );
	}

	/**
	 * Handle endpoint for preview elements
	 *
	 * @since 0.1.0
	 */
	public function button_endpoint() {
		if ( ! current_user_can( 'edit_posts' )
			|| empty( $_GET['product_handle'] ) ) {
			return;
		}

		$args = array(
			'product_handle' => esc_attr( $_GET['product_handle'] ),
		);

		$other_args = array(
			'shop',
			'embed_type',
			'buy_button_text',
			'button_background_color',
			'button_text_color',
			'background',
			'background_color',
			'text_color',
			'cart_title',
			'checkout_button_text',
			'redirect_to',
		);

		foreach( $other_args as $arg ) {
			if ( isset( $_GET[ $arg ] ) ) {
				$args[ $arg ] = esc_attr( $_GET[ $arg ] );
			}
		}

		?>
		<style type="text/css">
		body {
			text-align: center;
		}
		</style>
		<?php

		if ( ! empty( $_GET['show_cart'] ) ) {
			echo $this->get_cart( $args );
		}

		echo $this->get_button( $args );

		die();
	}

	/**
	 * Embed the cart in the footer
	 *
	 * @since 0.1.0
	 */
	function embed_cart() {
		// Only output cart if redirect is set to cart.
		if ( 'cart' !== cmb2_get_option( 'shopify_buy_button_appearance', 'redirect_to' ) ) {
			return;
		}

		echo $this->get_cart( array(
			'shop' => 'wds-test-store.myshopify.com',
		) );
	}
}
