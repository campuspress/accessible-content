<?php
/**
 * Mock-forcing user roles
 */

namespace Campus\A11y\Test\Mock;

trait RoleMocker {

	private $_has_died;

	public function return_die_handler() {
		return array( $this, 'record_die' );
	}

	public function setUp() {
		parent::setUp();
		add_filter( 'wp_die_handler', array( $this, 'return_die_handler' ) );
		$this->reset_died();
	}

	public function tearDown() {
		parent::setUp();
		$this->reset_died();
		remove_filter( 'wp_die_handler', array( $this, 'return_die_handler' ) );
	}

	public function record_die() {
		$this->_has_died = true;
	}

	public function has_died() {
		return (bool) $this->_has_died;
	}

	public function reset_died() {
		$this->_has_died = false;
	}

	public function get_nonadmin_roles() {
		return array(
			null,
			'subscriber',
			'author',
			'editor',
		);
	}

	public function mock_role( $user_id, $role ) {
		wp_update_user(
			array(
				'ID'   => $user_id,
				'role' => $role,
			)
		);
	}

	public function mock_current_user( $role = false ) {
		$user_id = 0;
		if ( ! empty( $role ) ) {
			$user_id = $this->factory->user->create();
			$this->mock_role( $user_id, $role );
		}
		wp_set_current_user( $user_id );
		return $user_id;
	}

	public function reset_user() {
		wp_set_current_user( 0 );
	}

	public function dies_for_role( $dies, $role = false, $callback, $args = array() ) {
		$this->mock_current_user( $role );

		$this->reset_died();
		$this->assertFalse(
			$this->has_died(),
			"Not died by default for {$role} in " . json_encode( $callback )
		);
		call_user_func_array( $callback, $args );
		$this->assertEquals(
			(bool) $dies,
			$this->has_died(),
			"Unexpected result for {$role} in " . json_encode( $callback )
		);

		$this->reset_user();
	}
}
