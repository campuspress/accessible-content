<?php

namespace Campus\A11y;

/**
 * @group core
 * @group model
 * @group constants
 */
class Model_Constants_Test extends Test\UnitTestCase {

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\Model\\Constants' )
		);
		$model = Model\Constants::get();
		$this->assertTrue(
			$model instanceof Singleton,
			'should be a singleton'
		);
	}

	public function test_with_prefix_returns_name_verbatim_with_no_prefix() {
		$model = Model\Constants::get();
		$this->expects(
			'test',
			$model->with_prefix( 'test' )
		);
	}

	public function test_with_namespace_returns_name_verbatim_with_no_namespace() {
		$model = Model\Constants::get();
		$this->expects(
			'test',
			$model->with_namespace( 'test' )
		);
	}

	public function test_is_defined_returns_true_when_constant_defined() {
		$model = Model\Constants::get();
		$this->expects(
			true,
			$model->is_defined( 'DB_NAME' )
		);
	}

	public function test_is_defined_returns_false_when_constant_is_not_defined() {
		$model = Model\Constants::get();
		$this->expects(
			false,
			$model->is_defined( 'Whatever, this is a test' )
		);
	}

	public function test_is_overridden_returns_true_when_constant_overridden() {
		$model = Model\Constants::get();
		$model->add_override( 'DB_NAME', 'test' );
		$this->expects(
			true,
			$model->is_overridden( 'DB_NAME' )
		);
		$model->clear_overrides();
	}

	public function test_is_overridden_returns_false_when_constant_is_not_overridden() {
		$model = Model\Constants::get();
		$model->clear_overrides();
		$this->expects(
			false,
			$model->is_overridden( 'DB_NAME' )
		);
	}

	public function test_exists_returns_true_for_defined_constant() {
		$model = Model\Constants::get();
		$this->expects(
			false,
			$model->is_overridden( 'DB_NAME' )
		);
		$this->expects(
			true,
			$model->exists( 'DB_NAME' )
		);
	}

	public function test_exists_returns_true_for_overridden_constant() {
		$model = Model\Constants::get();
		$model->clear_overrides();

		$this->expects(
			false,
			$model->exists( 'Whatever' )
		);
		$model->add_override( 'Whatever', 'test' );
		$this->expects(
			true,
			$model->exists( 'DB_NAME' )
		);
		$model->clear_overrides();
	}

	public function test_exists_returns_false_for_nonexistent_constant() {
		$model = Model\Constants::get();
		$model->clear_overrides();

		$this->expects(
			false,
			$model->exists( 'Whatever' )
		);
	}

	public function test_get_value_returns_define_value_with_no_overrides() {
		$model = Model\Constants::get();
		$model->clear_overrides();

		$this->expects(
			DB_NAME,
			$model->get_value( 'DB_NAME' )
		);
	}

	public function test_get_value_returns_override_value_with_overrides() {
		$model = Model\Constants::get();

		$model->add_override( 'DB_NAME', 'whatever' );
		$this->expects(
			'whatever',
			$model->get_value( 'DB_NAME' )
		);
		$model->clear_overrides();
	}

	public function test_remove_override_kills_single_override() {
		$model = Model\Constants::get();

		$model->add_override( 'test1', 'whatever' );
		$this->assertTrue( $model->exists( 'test1' ), 'first should exist' );

		$model->add_override( 'test2', 'whatever' );
		$this->assertTrue( $model->exists( 'test2' ), 'second should exist' );

		$model->remove_override( 'test1' );
		$this->assertFalse( $model->exists( 'test1' ), 'first should not exist now' );
		$this->assertTrue( $model->exists( 'test2' ), 'second should still exist' );

		$model->clear_overrides();
	}

}
