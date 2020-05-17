<?php

namespace Campus\A11y;

/**
 * @group core
 * @group controller
 * @group admin
 */
class Controller_Admin_Test extends Test\Admin\UnitTestCase {

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\Controller\\Admin' )
		);
	}

	public function test_is_singleton_controller() {
		$admin = Controller\Admin::get();
		$this->assertTrue(
			$admin instanceof Singleton,
			'should be a singleton instance'
		);
		$this->assertTrue(
			$admin instanceof Controller,
			'should be a controller instance'
		);
	}

	public function test_should_return_false_outside_admin() {
		$admin = Controller\Admin::get();
		$this->assertFalse( $admin->boot() );
	}

	public function test_should_return_false_inside_admin_for_random_people() {
		$admin = Controller\Admin::get();
		$this->mock_is_admin();
		$this->assertFalse( $admin->boot() );
		$this->unmock_is_admin();
	}

	public function test_should_return_true_inside_admin() {
		$admin = Controller\Admin::get();
		$this->mock_is_admin();
		$user_id = $this->mock_current_user( 'administrator' );
		$this->assertTrue( $admin->boot() );
		$this->reset_user();
		$this->unmock_is_admin();
	}

	public function test_root_capability_should_be_admin() {
		$admin = Controller\Admin::get();
		$this->expects(
			'manage_options',
			$admin->get_capability()
		);
	}

	public function test_can_user_access_page_should_return_false_for_non_admin() {
		$admin = Controller\Admin::get();
		foreach ( $this->get_nonadmin_roles() as $role ) {
			$this->mock_current_user( $role );
			$this->assertFalse(
				$admin->can_user_access_page(),
				"{$role} should not be able to access page"
			);
			$this->reset_user();
		}
	}

	public function test_can_user_access_page_should_return_true_for_admin() {
		$admin   = Controller\Admin::get();
		$user_id = $this->mock_current_user( 'administrator' );
		$this->assertTrue(
			$admin->can_user_access_page(),
			'admin should be able to access page on single site'
		);
		$this->reset_user();
	}

	public function test_setting_links_should_add_link() {
		$admin = Controller\Admin::get();
		$this->expects_length( 1, $admin->add_setting_links( [] ) );
	}

	public function test_get_template_returns_template_object() {
		$admin = Controller\Admin::get();

		$t1 = $admin->get_template();
		$this->assertTrue(
			$t1 instanceof View\Template,
			'should be a template instance'
		);

		$t2 = $admin->get_template();
		$this->assertTrue(
			$t2 instanceof View\Template,
			'should also be a template instance'
		);

		$this->assertSame( $t1, $t2, 'instances should be same' );
	}
}
