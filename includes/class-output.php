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
	}

	/**
	 * Get markup for frontend buy button iframe.
	 *
	 * @since 0.1.0
	 * @param  array $args Arguments for buy button
	 * @return string      HTML markup
	 */
	public function get_iframe( $args ) {
		$args = wp_parse_args( $args, array(
			'setting' => true,
		) );

		ob_start();
		?>
		<iframe src=""></iframe>
		<?php
		return ob_get_clean();
	}
}
