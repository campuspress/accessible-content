<?php

namespace Campus\A11y;

/**
 * @group core
 * @group controller
 * @group ajax
 */
class Controller_Ajax_Test extends Test\Admin\UnitTestCase {

	public function setUp() {
		$this->mock_ajax();
	}

	public function tearDown() {
		$this->unmock_post();
		$this->unmock_ajax();
	}

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\Controller\\Ajax' )
		);
		$ctrl = Controller\Ajax::get();
		$this->assertTrue(
			$ctrl instanceof Controller,
			'should be a controller'
		);
		$this->assertTrue(
			$ctrl instanceof Singleton,
			'should be a singleton'
		);
	}

	public function test_update_ignores_rejects_with_missing_post_id() {
		$ctrl = Controller\Ajax::get();
		$this->should_be_failure( [ $ctrl, 'update_ignores' ] );
	}

	public function test_update_ignores_rejects_with_invalid_post_id() {
		$ctrl = Controller\Ajax::get();
		$this->mock_post( 'post_id', 'whatever' );
		$this->should_be_failure( [ $ctrl, 'update_ignores' ] );
	}

	public function test_update_ignores_rejects_with_valid_post_id_noauth() {
		$ctrl = Controller\Ajax::get();
		$this->mock_post( 'post_id', 1312 );
		$this->should_be_failure( [ $ctrl, 'update_ignores' ] );
	}

	public function test_update_ignores_reject_with_invalid_ignores() {
		$ctrl = Controller\Ajax::get();
		$post = $this->factory->post->create_and_get([
			"post_title" => "Test Post"
		]);
		$this->mock_post( 'post_id', $post->ID );
		$this->mock_post( 'ignore', 'whatever' );
		$user_id = $this->mock_current_user( 'administrator' );
		$this->should_be_failure( [ $ctrl, 'update_ignores' ] );
		$this->reset_user();
	}

	public function test_update_ignores_succeeds_with_valid_post_id_auth() {
		$ctrl = Controller\Ajax::get();
		$post = $this->factory->post->create_and_get([
			"post_title" => "Test Post"
		]);
		$test = [ 'node_id' => [ 'test1', 'test2' ] ];

		$this->mock_post( 'post_id', $post->ID );
		$this->mock_post( 'ignore', $test );

		$user_id = $this->mock_current_user( 'administrator' );
		$data = $this->should_be_success( [ $ctrl, 'update_ignores' ] );
		$this->reset_user();

		$this->assertEquals(
			$data['data'], $test,
			'response has the proper data set: ' . json_encode( $data )
		);
	}

	public function test_update_alt_rejects_with_missing_post_id() {
		$ctrl = Controller\Ajax::get();
		$this->should_be_failure( [ $ctrl, 'update_alt' ] );
	}

	public function test_update_alt_rejects_with_invalid_post_id() {
		$ctrl = Controller\Ajax::get();
		$this->mock_post( 'post_id', 'whatever' );
		$this->should_be_failure( [ $ctrl, 'update_alt' ] );
	}

	public function test_update_alt_rejects_with_valid_post_id_noauth() {
		$ctrl = Controller\Ajax::get();
		$this->mock_post( 'post_id', 1312 );
		$this->should_be_failure( [ $ctrl, 'update_alt' ] );
	}

	public function test_update_alt_succeeds_with_valid_post_id_auth() {
		$ctrl = Controller\Ajax::get();
		$post = $this->factory->post->create_and_get([
			'post_title' => 'Test Post',
			'post_type' => 'attachment',
		]);
		$test = 'this is my test alt';

		$this->mock_post( 'post_id', $post->ID );
		$this->mock_post( 'alt', $test );

		$user_id = $this->mock_current_user( 'administrator' );
		$data = $this->should_be_success( [ $ctrl, 'update_alt' ] );
		$this->reset_user();

		$attachment = new Model\Attachment( $post->ID );
		$this->assertEquals(
			$attachment->get_alt(), $test,
			'attachment alt should be properly set'
		);
	}

	public function test_update_decorative_rejects_with_missing_post_id() {
		$ctrl = Controller\Ajax::get();
		$this->should_be_failure( [ $ctrl, 'update_decorative' ] );
	}

	public function test_update_decorative_rejects_with_invalid_post_id() {
		$ctrl = Controller\Ajax::get();
		$this->mock_post( 'post_id', 'whatever' );
		$this->should_be_failure( [ $ctrl, 'update_decorative' ] );
	}

	public function test_update_decorative_rejects_with_valid_post_id_noauth() {
		$ctrl = Controller\Ajax::get();
		$this->mock_post( 'post_id', 1312 );
		$this->should_be_failure( [ $ctrl, 'update_decorative' ] );
	}

	public function test_update_decorative_succeeds_with_valid_post_id_auth() {
		$ctrl = Controller\Ajax::get();
		$post = $this->factory->post->create_and_get([
			'post_title' => 'Test Post',
			'post_type' => 'attachment',
		]);

		$attachment = new Model\Attachment( $post->ID );
		$this->assertFalse(
			$attachment->is_decorative(),
			'attachment should not be decorative by default'
		);

		$this->mock_post( 'post_id', $post->ID );
		$this->mock_post( 'is_decorative', true );

		$user_id = $this->mock_current_user( 'administrator' );
		$data = $this->should_be_success( [ $ctrl, 'update_decorative' ] );
		$this->reset_user();

		$attachment = new Model\Attachment( $post->ID );
		$this->assertTrue(
			$attachment->is_decorative(),
			'attachment should now be decorative'
		);

		$this->mock_post( 'post_id', $post->ID );
		$this->mock_post( 'is_decorative', false );

		$user_id = $this->mock_current_user( 'administrator' );
		$data = $this->should_be_success( [ $ctrl, 'update_decorative' ] );
		$this->reset_user();

		$attachment = new Model\Attachment( $post->ID );
		$this->assertFalse(
			$attachment->is_decorative(),
			'attachment should not be decorative anymore'
		);
	}


	public function get_response( $callable ) {
		ob_clean();
		$callable();
		return ob_get_contents();
	}

	public function should_be_valid_response( $callable ) {
		$response = $this->get_response( $callable );
		$this->assertTrue( is_string( $response ), 'response should be string' );
		$this->assertFalse( empty( $response ), 'response should not be empty' );

		$data = json_decode( $response, true );
		$this->assertTrue(
			is_array( $data ),
			'response should decode to JSON array'
		);
		$this->assertTrue(
			isset( $data['success'] ),
			'response should have success key'
		);
		return $data;
	}

	public function should_be_success( $callable ) {
		$data = $this->should_be_valid_response( $callable );
		$this->assertTrue(
			$data['success'],
			'success response should be successful'
		);
		return $data;
	}

	public function should_be_failure( $callable ) {
		$data = $this->should_be_valid_response( $callable );
		$this->assertFalse(
			$data['success'],
			'failure response should not be successful, duh'
		);
		return $data;
	}

	public function mock_ajax() {
		ob_start();
		$this->_is_buffered = true;
		add_filter( 'wp_die_ajax_handler', array( $this, 'ajax_exit_callback' ) );
		add_filter( 'wp_doing_ajax', '__return_true' );
	}

	public function unmock_ajax() {
		remove_filter( 'wp_doing_ajax', '__return_true' );
		remove_filter( 'wp_die_ajax_handler', array( $this, 'ajax_exit_callback' ) );
		if ( $this->_is_buffered ) {
			ob_end_clean();
			$this->_is_buffered = false;
		}
	}

	public function ajax_exit_callback() {
		return '__return_false';
	}

	public function mock_post( $key, $value ) {
		$_POST[ $key ] = $value;
	}

	public function unmock_post( $key = false ) {
		if ( empty( $key ) ) {
			foreach ( $_POST as $key => $value ) {
				unset( $_POST[ $key ] );
			}
			return true;
		}
		unset( $_POST[ $key ] );
	}
}
