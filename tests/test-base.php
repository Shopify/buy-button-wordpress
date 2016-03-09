<?php

class BaseTest extends WP_UnitTestCase {

	/**
	 * Confirm the plugin base class exists.
	 *
	 * @since NEXT
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'Shopify_Buy_Button' ) );
	}

	/**
	 * Confirm instance template tag grabs the correct class.
	 *
	 * @since NEXT
	 */
	function test_get_instance() {
		$this->assertTrue( shopify_buy_button() instanceof Shopify_Buy_Button );
	}
}
