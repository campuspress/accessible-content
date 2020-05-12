<?php
/**
 * Describes shared controller actions.
 *
 * @package campus-a11y
 */

namespace Campus\A11y;

/**
 * Controller abstraction
 */
abstract class Controller extends Singleton {

	/**
	 * Boots the controller
	 */
	abstract public function boot();

	/**
	 * Wraps the die callback
	 *
	 * Used in tests
	 */
	public function drop_dead() {
		$actual_die = apply_filters(
			Main::get()->get_hook( 'die-callback' ),
			[ $this, 'actual_die' ]
		);
		$actual_die();
	}

	/**
	 * Default die callback
	 */
	public function actual_die() {
		die;
	}
}

