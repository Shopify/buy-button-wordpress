<?php

class SECP_Modal_Test extends WP_UnitTestCase {

	/**
	 * Confirm modal class is defined.
	 *
	 * @since 1.0.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'SECP_Modal' ) );
	}

	/**
	 * Confirm modal class is assigned as part of base class.
	 *
	 * @since 1.0.0
	 */
	function test_class_access() {
		$this->assertTrue( shopify_ecommerce_plugin()->modal instanceof SECP_Modal );
	}
}
