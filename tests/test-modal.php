<?php

class SBB_Modal_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'SBB_Modal') );
	}

	function test_class_access() {
		$this->assertTrue( shopify_buy_button()->modal instanceof SBB_Modal );
	}
}
