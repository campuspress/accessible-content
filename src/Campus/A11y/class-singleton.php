<?php
/**
 * Singleton helper parent class
 *
 * @package campus-a11y
 */

namespace Campus\A11y;

/**
 * Singleton abstraction class
 */
abstract class Singleton {

	/**
	 * Holds instance objects
	 *
	 * @var array
	 */
	private static $_instances = [];

	/**
	 * Limited constructor access
	 */
	protected function __construct() {}

	/**
	 * No singleton cloning
	 */
	private function __clone() {}

	/**
	 * Gets individual instance objects
	 *
	 * Instantiates them if needed.
	 *
	 * @return object Singleton instance.
	 */
	final public static function get() {
		if ( empty( self::$_instances[ static::class ] ) ) {
			self::$_instances[ static::class ] = new static();
		}
		return self::$_instances[ static::class ];
	}
}
