<?php

namespace Campus\A11y;

/**
 * @group core
 * @group model
 * @group constants
 * @group wp
 */
class Model_Constants_Wp_Test extends Test\UnitTestCase {

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\Model\\Constants\\Wp' )
		);
		$model = Model\Constants\Wp::get();
		$this->assertTrue(
			$model instanceof Singleton,
			'should be a singleton'
		);
		$this->assertTrue(
			$model instanceof Model\Constants,
			'should be a constants model instance'
		);
	}

	public function test_core_specific_prefix_define() {
		$model = Model\Constants\Wp::get();
		$this->expects(
			true,
			$model->exists( 'DEBUG' )
		);
		$this->expects(
			constant( $model->with_prefix( 'DEBUG' ) ),
			$model->get_value( 'DEBUG' )
		);
	}

	public function test_core_defines_sans_prefix() {
		$model = Model\Constants\Wp::get();
		$this->expects(
			true,
			$model->exists( 'ABSPATH' )
		);
		$this->expects(
			ABSPATH,
			$model->get_value( 'ABSPATH' )
		);
	}
}
