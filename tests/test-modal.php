<?php

class SBB_Modal_Test extends WP_UnitTestCase {

	/**
	 * Confirm modal class is defined.
	 *
	 * @since NEXT
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'SBB_Modal' ) );
	}

	/**
	 * Confirm modal class is assigned as part of base class.
	 *
	 * @since NEXT
	 */
	function test_class_access() {
		$this->assertTrue( shopify_buy_button()->modal instanceof SBB_Modal );
	}
}
