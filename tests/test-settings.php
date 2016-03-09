<?php

class SBB_Settings_Test extends WP_UnitTestCase {

	/**
	 * Confirm settings class is defined.
	 *
	 * @since NEXT
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'SBB_Settings' ) );
	}

	/**
	 * Confirm settings class is assigned as part of base class.
	 *
	 * @since NEXT
	 */
	function test_class_access() {
		$this->assertTrue( shopify_buy_button()->settings instanceof SBB_Settings );
	}
}
