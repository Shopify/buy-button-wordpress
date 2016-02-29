<?php

class SBB_Appearance_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'SBB_Appearance') );
	}

	function test_class_access() {
		$this->assertTrue( shopify_buy_button()->appearance instanceof SBB_Appearance );
	}
}
