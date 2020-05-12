<?php

namespace Campus\A11y;

/**
 * @group core
 * @group model
 * @group constants
 * @group plugin
 */
class Model_Constants_Plugin_Test extends Test\UnitTestCase {

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\Model\\Constants\\Plugin' )
		);
		$model = Model\Constants\Plugin::get();
		$this->assertTrue(
			$model instanceof Singleton,
			'should be a singleton'
		);
		$this->assertTrue(
			$model instanceof Model\Constants,
			'should be a constants model instance'
		);
	}

	public function test_plugin_specific_prefix_define() {
		$model = Model\Constants\Plugin::get();
		$this->expects(
			true,
			$model->exists( 'VERSION' )
		);
		$this->expects(
			constant( $model->with_prefix( 'VERSION' ) ),
			$model->get_value( 'VERSION' )
		);
	}

	public function test_plugin_specific_namespaced_defines() {
		$model   = Model\Constants\Plugin::get();
		$defines = array(
			'PLUGIN_FILE',
			'PLUGIN_DIR',
			'PLUGIN_FILENAME',
		);
		foreach ( $defines as $define ) {
			$this->expects( true, $model->exists( $define ) );
			$this->expects(
				constant( $model->with_namespace( $define ) ),
				$model->get_value( $define )
			);
		}
	}
}
