<?php

class SECP_Customize_Test extends WP_UnitTestCase {

	/**
	 * Confirm customize class is defined.
	 *
	 * @since NEXT
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'SECP_Customize' ) );
	}

	/**
	 * Confirm customize class is assigned as part of base class.
	 *
	 * @since NEXT
	 */
	function test_class_access() {
		$this->assertTrue( shopify_ecommerce_plugin()->customize instanceof SECP_Customize );
	}
}
