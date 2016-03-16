<?php

class SECP_Settings_Test extends WP_UnitTestCase {

	/**
	 * Confirm settings class is defined.
	 *
	 * @since 1.0.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'SECP_Settings' ) );
	}

	/**
	 * Confirm settings class is assigned as part of base class.
	 *
	 * @since 1.0.0
	 */
	function test_class_access() {
		$this->assertTrue( shopify_ecommerce_plugin()->settings instanceof SECP_Settings );
	}
}
