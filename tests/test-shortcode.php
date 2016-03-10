<?php

class SECP_Shortcode_Test extends WP_UnitTestCase {

	/**
	 * Confirm shortcode class is defined.
	 *
	 * @since NEXT
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'SECP_Shortcode' ) );
	}

	/**
	 * Confirm shortcode class is assigned as part of base class.
	 *
	 * @since NEXT
	 */
	function test_class_access() {
		$this->assertTrue( shopify_ecommerce_plugin()->shortcode instanceof SECP_Shortcode );
	}
}
