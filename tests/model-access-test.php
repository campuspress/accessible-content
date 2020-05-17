<?php

namespace Campus\A11y;

/**
 * @group core
 * @group model
 * @group access
 */
class Model_Access_Test extends Test\UnitTestCase {


	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\Model\\Access' )
		);
	}

	public function test_capability_returns_site_admin_on_single_site() {
		$this->expects(
			'manage_options',
			Model\Access::get_capability()
		);
	}

	public function test_capability_returns_site_admin_on_multisite() {
		$consts = Model\Constants\Plugin::get();
		$consts->add_override( $consts->with_namespace( 'MULTISITE' ), true );
		$this->expects(
			'manage_options',
			Model\Access::get_capability()
		);
		$consts->clear_overrides();
	}
}
