<?php
/**
 * Wraps defined constants, for tests and overriding
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model;

use Campus\A11y\Singleton;

/**
 * Plugin constants class
 */
class Constants extends Singleton {

	/**
	 * Define overrides
	 *
	 * @var array
	 */
	private $_overrides = [];

	/**
	 * Implementation constants namespace
	 *
	 * @var string
	 */
	protected $_namespace;

	/**
	 * Implementation constants prefix
	 *
	 * @var string
	 */
	protected $_prefix;

	/**
	 * Returns constant in set namespace
	 *
	 * @param string $what Constant bare name.
	 *
	 * @return bool
	 */
	public function with_namespace( $what ) {
		if ( empty( $this->_namespace ) ) {
			return $what;
		}
		return "{$this->_namespace}\\{$what}";
	}

	/**
	 * Returns constant with set prefix
	 *
	 * @param string $what Constant bare name.
	 *
	 * @return bool
	 */
	public function with_prefix( $what ) {
		if ( empty( $this->_prefix ) ) {
			return $what;
		}
		return "{$this->_prefix}_{$what}";
	}

	/**
	 * Checks whether the constant is overridden
	 *
	 * @param string $what Constant bare name.
	 *
	 * @return bool
	 */
	public function is_overridden( $what ) {
		return isset( $this->_overrides[ $this->with_namespace( $what ) ] ) ||
			isset( $this->_overrides[ $this->with_prefix( $what ) ] );
	}

	/**
	 * Checks whether the constant is defined
	 *
	 * @param string $what Constant bare name.
	 *
	 * @return bool
	 */
	public function is_defined( $what ) {
		return defined( $this->with_namespace( $what ) ) ||
			defined( $this->with_prefix( $what ) );
	}

	/**
	 * Checks whether we know about the define in any way we can
	 *
	 * @param string $what Constant bare name.
	 *
	 * @return bool
	 */
	public function exists( $what ) {
		return $this->is_defined( $what ) || $this->is_overridden( $what );
	}

	/**
	 * Gets constant value
	 *
	 * Will return override value first, if there's any.
	 *
	 * @param string $what Constant bare name.
	 * @param mixed  $fallback Optional fallback, if the constant is not known.
	 *
	 * @return bool
	 */
	public function get_value( $what, $fallback = false ) {
		$ns = $this->with_namespace( $what );
		$px = $this->with_prefix( $what );

		if ( $this->is_overridden( $what ) ) {
			return isset( $this->_overrides[ $ns ] )
				? $this->_overrides[ $ns ]
				: $this->_overrides[ $px ];
		}
		if ( ! $this->is_defined( $what ) ) {
			return $fallback;
		}

		return defined( $ns )
			? constant( $ns )
			: constant( $px );
	}

	/**
	 * Adds an override for the constant
	 *
	 * @param string $constant Constant full name (WITH namespace/prefix).
	 * @param mixed  $value Constant value.
	 */
	public function add_override( $constant, $value ) {
		$this->_overrides[ $constant ] = $value;
	}

	/**
	 * Removes an override for the constant
	 *
	 * @param string $what Constant bare name.
	 */
	public function remove_override( $what ) {
		$ns      = $this->with_namespace( $what );
		$px      = $this->with_prefix( $what );
		$removed = false;

		if ( isset( $this->_overrides[ $ns ] ) ) {
			unset( $this->_overrides[ $ns ] );
			$removed = true;
		}
		if ( isset( $this->_overrides[ $px ] ) ) {
			unset( $this->_overrides[ $px ] );
			$removed = true;
		}

		return $removed;
	}

	/**
	 * Clears all overrides
	 */
	public function clear_overrides() {
		$this->_overrides = [];
	}
}
