<?php
/**
 * Mock-forcing admin area
 */

namespace Campus\A11y\Test\Mock;


trait AdminMocker {

	private $_screen;

	public function mock_is_admin() {
		$this->_screen             = @$GLOBALS['current_screen'];
		$GLOBALS['current_screen'] = new Screen\Admin;
		$GLOBALS['hook_suffix'] = 'whatever';
	}

	public function unmock_is_admin() {
		$GLOBALS['current_screen'] = $this->_screen;
		unset( $GLOBALS['hook_suffix'] );
	}
}

