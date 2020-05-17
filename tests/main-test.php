<?php

namespace Campus\A11y;

/**
 * @group core
 * @group main
 */
class Main_Test extends Test\UnitTestCase {

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\Main' )
		);
	}

	public function test_is_singleton_controller() {
		$main = Main::get();
		$this->assertTrue(
			$main instanceof Singleton,
			'should be a singleton instance'
		);
		$this->assertTrue(
			$main instanceof Controller,
			'should be a controller instance'
		);
	}

	public function test_plugin_basename_should_return_value() {
		$main = Main::get();
		$this->expects(
			basename( plugin_basename( 'accessible-content.php' ) ),
			basename( $main->plugin_basename() )
		);
	}

	public function test_plugin_dir_path_should_return_path() {
		$this->expects(
			trailingslashit( dirname( dirname( __FILE__ ) ) ),
			Main::get()->plugin_dir_path()
		);
	}

	public function test_plugins_url_returns_url() {
		$url = Main::get()->plugins_url();
		$this->assertTrue(
			(bool) preg_match( '/^https?:\/\//', $url ),
			'should begin with protocol'
		);
		$this->assertTrue(
			(bool) preg_match( '/\/$/', $url ),
			'should end with slash'
		);
	}

	public function test_get_version_returns_proper_version() {
		$this->expects(
			CAMPUS_A11Y_VERSION,
			Main::get()->get_version()
		);
	}

	public function test_get_hook_returns_prefixed_hook_basename() {
		$this->expects(
			'campus_a11y_whatever_hook_name',
			Main::get()->get_hook( 'whatever-hook-name' )
		);
	}
}
