<?php

namespace Campus\A11y;

/**
 * @group core
 * @group controller
 * @group handler
 */
class Controller_Handler_Test extends Test\Admin\UnitTestCase {

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\Controller\\Handler' )
		);
	}

	public function test_is_error_status_returns_false_when_get_empty() {
		$_GET = [];
		$this->assertFalse(
			Controller\Handler::is_error_status(),
			'error status should be false with empty GET'
		);
	}

	public function test_is_error_status_returns_false_when_no_status_in_get() {
		$_GET = [ 1312 => 161 ];
		$this->assertFalse(
			Controller\Handler::is_error_status(),
			'error status should be false with no status in GET'
		);
		$_GET = [];
	}

	public function test_is_error_status_returns_false_when_status_is_success() {
		$_GET = [ 'status' => Controller\Handler::STATUS_SUCCESS ];
		$this->assertFalse(
			Controller\Handler::is_error_status(),
			'error status should be false with success status in GET'
		);
		$_GET = [];
	}

	public function test_is_error_status_returns_true_with_error_get_status() {
		$_GET = [ 'status' => Controller\Handler::STATUS_ERROR ];
		$this->assertTrue(
			Controller\Handler::is_error_status(),
			'error status should be false with no status in GET'
		);
		$_GET = [];
	}

	public function test_is_allowed_returns_false_outside_admin() {
		$ctrl = Controller\Handler\Settings::get();
		$this->assertFalse( is_admin() );
		$this->assertFalse(
			$ctrl->is_allowed(),
			'handlers should not be allowed outside admin'
		);
	}

	public function test_is_allowed_returns_false_outside_admin_for_admin() {
		$ctrl = Controller\Handler\Settings::get();
		$this->mock_current_user( 'administrator' );
		$this->assertFalse( is_admin() );
		$this->assertFalse(
			$ctrl->is_allowed(),
			'handlers should not be allowed outside admin for admin'
		);
		$this->reset_user();
	}

	public function test_is_allowed_returns_false_in_admin_for_non_admin() {
		$ctrl = Controller\Handler\Settings::get();
		$this->mock_current_user( 'contributor' );
		$this->mock_is_admin();
		$this->assertTrue( is_admin() );
		$this->assertFalse(
			$ctrl->is_allowed(),
			'handlers should be allowed inside admin for admin'
		);
		$this->reset_user();
		$this->unmock_is_admin();
	}

	public function test_is_allowed_returns_true_in_admin_for_admin() {
		$ctrl = Controller\Handler\Settings::get();
		$this->mock_current_user( 'administrator' );
		$this->mock_is_admin();
		$this->assertTrue( is_admin() );
		$this->assertTrue(
			$ctrl->is_allowed(),
			'handlers should be allowed inside admin for admin'
		);
		$this->reset_user();
		$this->unmock_is_admin();
	}

	public function test_redirect_success_sets_success_status_param() {
		$ctrl = Controller\Handler\Settings::get();
		add_filter(
			Main::get()->get_hook( 'die-callback' ), [ $this, 'return_return_false' ] );
		add_filter( 'wp_redirect', [ $this, 'check_url_status_success' ] );

		$ctrl->redirect_success();

		remove_filter(
			Main::get()->get_hook( 'die-callback' ), [ $this, 'return_return_false' ] );
		remove_filter( 'wp_redirect', [ $this, 'check_url_status_success' ] );
	}

	public function test_redirect_error_sets_error_status_param() {
		$ctrl = Controller\Handler\Settings::get();
		add_filter(
			Main::get()->get_hook( 'die-callback' ), [ $this, 'return_return_false' ] );
		add_filter( 'wp_redirect', [ $this, 'check_url_status_error' ] );

		$ctrl->redirect_error();

		remove_filter(
			Main::get()->get_hook( 'die-callback' ), [ $this, 'return_return_false' ] );
		remove_filter( 'wp_redirect', [ $this, 'check_url_status_error' ] );
	}

	public function check_url_status_success( $url ) {
		$query = preg_quote( 'status=' . Controller\Handler::STATUS_SUCCESS, '/' );
		return $this->assertTrue(
			(bool) preg_match( "/{$query}/", $url ),
			"url should have success status set: {$url}"
		);
		return false;
	}

	public function check_url_status_error( $url ) {
		$query = preg_quote( 'status=' . Controller\Handler::STATUS_ERROR, '/' );
		return $this->assertTrue(
			(bool) preg_match( "/{$query}/", $url ),
			"url should have error status set: {$url}"
		);
		return false;
	}

	public function return_return_false() {
		return '__return_false';
	}
}
