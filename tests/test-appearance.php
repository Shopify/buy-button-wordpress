<?php

class SBB_Appearance_Test extends WP_UnitTestCase {

	/**
	 * Confirm appearance class is defined.
	 *
	 * @since NEXT
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'SBB_Appearance' ) );
	}

	/**
	 * Confirm appearance class is assigned as part of base class.
	 *
	 * @since NEXT
	 */
	function test_class_access() {
		$this->assertTrue( shopify_buy_button()->appearance instanceof SBB_Appearance );
	}
}
