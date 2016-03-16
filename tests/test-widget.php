<?php

class SECP_Widget_Test extends WP_UnitTestCase {

	/**
	 * Confirm widget class is defined.
	 *
	 * @since 1.0.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'SECP_Widget' ) );
	}
}
