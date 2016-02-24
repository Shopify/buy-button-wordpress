<?php
/**
 * Shopify Buy Button Widget
 * @version 0.1.0
 * @package Shopify Buy Button
 */

class SBB_Widget extends WP_Widget {

	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected $widget_slug = 'shopify-buy-button-widget';


	/**
	 * Widget name displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected $widget_name = '';


	/**
	 * Default widget title displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected $default_widget_title = '';


	/**
	 * Shortcode name for this widget
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected static $shortcode = 'shopify-buy-button-widget';


	/**
	 * Construct widget class.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function __construct() {

		$this->widget_name          = esc_html__( 'Shopify Buy Button', 'shopify-buy-button' );
		$this->default_widget_title = esc_html__( 'Shopify Buy Button', 'shopify-buy-button' );

		parent::__construct(
			$this->widget_slug,
			$this->widget_name,
			array(
				'classname'   => $this->widget_slug,
				'description' => esc_html__( 'Embed a Shopify product and buy button.', 'shopify-buy-button' ),
			)
		);

		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		add_shortcode( self::$shortcode, array( __CLASS__, 'get_widget' ) );
	}


	/**
	 * Delete this widget's cache.
	 *
	 * Note: Could also delete any transients
	 * delete_transient( 'some-transient-generated-by-this-widget' );
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}


	/**
	 * Front-end display of widget.
	 *
	 * @since  0.1.0
	 * @param  array $args     The widget arguments set up when a sidebar is registered.
	 * @param  array $instance The widget settings as set by user.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		echo self::get_widget( array(
			'before_widget'  => $args['before_widget'],
			'after_widget'   => $args['after_widget'],
			'before_title'   => $args['before_title'],
			'after_title'    => $args['after_title'],
			'title'          => $instance['title'],
			'embed_type'     => $instance['embed_type'],
			'shop'           => $instance['shop'],
			'product_handle' => $instance['product_handle'],
		) );
	}


	/**
	 * Return the widget/shortcode output
	 *
	 * @since  0.1.0
	 * @param  array $atts Array of widget/shortcode attributes/args.
	 * @return string       Widget output
	 */
	public static function get_widget( $atts ) {
		$widget = '';

		// Set up default values for attributes.
		$atts = shortcode_atts(
			array(
				'before_widget'  => '',
				'after_widget'   => '',
				'before_title'   => '',
				'after_title'    => '',
				'title'          => '',
				'text'           => '',
				'embed_type'     => '',
				'shop'           => '',
				'product_handle' => '',
			),
			(array) $atts,
			self::$shortcode
		);

		// Before widget hook.
		$widget .= $atts['before_widget'];

		// Title.
		$widget .= ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '';

		$widget .= wpautop( wp_kses_post( $atts['text'] ) );

		$widget .= shopify_buy_button()->output->get_button( array(
			'embed_type'     => $atts[ 'embed_type' ],
			'shop'           => $atts[ 'shop' ],
			'product_handle' => $atts[ 'product_handle' ],
		) );

		// After widget hook.
		$widget .= $atts['after_widget'];

		return $widget;
	}


	/**
	 * Update form values as they are saved.
	 *
	 * @since  0.1.0
	 * @param  array $new_instance New settings for this instance as input by the user.
	 * @param  array $old_instance Old settings for this instance.
	 * @return array               Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		// Previously saved values.
		$instance = $old_instance;

		$instance['embed_type'] = sanitize_text_field( $new_instance['embed_type'] );
		$instance['shop'] = sanitize_text_field( $new_instance['shop'] );
		$instance['product_handle'] = sanitize_text_field( $new_instance['product_handle'] );

		// Sanitize title before saving to database.
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		// Flush cache.
		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * Enqueue admin widget scripts and styles.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	function enqueue() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'sbb-admin-shortcode', shopify_buy_button()->url( 'assets/js/admin-widget' . $min . '.js' ), array( 'jquery' ), '160223', true );
		wp_localize_script( 'sbb-admin-shortcode', 'sbbAdminModal', array(
			'modal' => shopify_buy_button()->modal->get_modal(),
		) );

		wp_enqueue_style( 'sbb-admin', shopify_buy_button()->url( 'assets/css/shopify-buy-button' . $min . '.css' ), array(), '160223' );
	}


	/**
	 * Back-end widget form with defaults.
	 *
	 * @since  0.1.0
	 * @param  array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance,
			array(
				'title'          => $this->default_widget_title,
				'embed_type'     => '',
				'shop'           => '',
				'product_handle' => '',
			)
		);

		$this->enqueue();

		// Title field
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', '<%= slug %>' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" placeholder="optional" />
		</p>
		<?php

		// Do product preview
		if ( $instance[ 'product_handle' ] ) {
			?>
			<p><?php esc_html_e( 'Preview: ', 'shopify_buy_button' ); ?>
			<iframe class="sbb-widget-preview" src="<?php echo esc_url( add_query_arg( array(
				'embed_type'     => $instance[ 'embed_type' ],
				'shop'           => $instance[ 'shop' ],
				'product_handle' => $instance[ 'product_handle' ],
			), site_url() ) ); ?>"></iframe></p>
			<?php
		} else {
			?><p><?php esc_html_e( 'No Product Set', 'shopify-buy-button' ); ?></p><?php
		}

		// Do button
		$button_text = __( 'Add Product', 'shopify-buy-button' );
		if ( $instance[ 'product_handle' ] ) {
			$button_text = __( 'Replace Product', 'shopify-buy-button' );
		}
		?>
		<p><button class="button" id="sbb-add-widget"><?php echo esc_html( $button_text ); ?></button></p>
		<?php

		// Do hidden fields for product
		foreach( array( 'embed_type', 'shop', 'product_handle' ) as $hidden_field ) {
			?><input class="sbb-hidden-<?php echo $hidden_field ?>" type="hidden" id="<?php echo esc_attr( $this->get_field_id( $hidden_field ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $hidden_field ) ); ?>" value="<?php echo esc_attr( $instance[ $hidden_field ]) ?>"><?php
		}
	}
}


/**
 * Register this widget with WordPress. Can also move this function to the parent plugin.
 *
 * @since  0.1.0
 * @return void
 */
function register_shopify_buy_button_widget() {
	register_widget( 'SBB_Widget' );
}
add_action( 'widgets_init', 'register_shopify_buy_button_widget' );
