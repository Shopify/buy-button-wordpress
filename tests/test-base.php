<?php

class BaseTest extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'Shopify_Buy_Button') );
	}
	
	function test_get_instance() {
		$this->assertTrue( shopify_buy_button() instanceof Shopify_Buy_Button );
	}
}
