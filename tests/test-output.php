<?php

class SECP_Output_Test extends WP_UnitTestCase {

	/**
	 * Confirm output class is defined.
	 *
	 * @since 1.0.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'SECP_Output' ) );
	}

	/**
	 * Confirm output class is assigned as part of base class.
	 *
	 * @since 1.0.0
	 */
	function test_class_access() {
		$this->assertTrue( shopify_ecommerce_plugin()->output instanceof SECP_Output );
	}
}
