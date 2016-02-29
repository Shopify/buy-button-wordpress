<?php

class SBB_Settings_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'SBB_Settings') );
	}

	function test_class_access() {
		$this->assertTrue( shopify_buy_button()->settings instanceof SBB_Settings );
	}
}
