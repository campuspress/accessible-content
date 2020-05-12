<?php
/**
 * WP plugin options
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model;

use Campus\A11y\Singleton;
use Campus\A11y\Main;

/**
 * Options singleton class
 */
class Options extends Singleton {

	const KEY_OPTIONS = 'options';

	const KEY_ENABLE_REPLACEMENT = 'enable_replacement';

	/**
	 * Constructor
	 *
	 * Used to expose the option to WP
	 */
	protected function __construct() {
		parent::__construct();
		$this->initialize();
	}

	/**
	 * Initialize the options
	 */
	public function initialize() {
		add_option(
			Main::get()->get_hook( self::KEY_OPTIONS ),
			$this->get_defaults()
		);
	}

	/**
	 * Returns the initial set of options
	 *
	 * @return array
	 */
	public function get_defaults() {
		return [
			self::KEY_ENABLE_REPLACEMENT => false,
		];
	}

	/**
	 * Gets individual option value
	 *
	 * @param string $name Option name to fetch.
	 * @param mixed  $fallback Optional fallback value, defaults to false.
	 *
	 * @return mixed Option value, or fallback if not set
	 */
	public function get_option( $name, $fallback = false ) {
		$options = $this->get_options();
		return isset( $options[ $name ] )
			? $options[ $name ]
			: $fallback;
	}

	/**
	 * Gets all options as an array
	 *
	 * @return array
	 */
	public function get_options() {
		$options = get_option(
			Main::get()->get_hook( self::KEY_OPTIONS ),
			[]
		);
		return is_array( $options )
			? $options
			: [];
	}

	/**
	 * Sets an individual option value
	 *
	 * @param string $name Option name to set.
	 * @param mixed  $value Value to set.
	 */
	public function set_option( $name, $value ) {
		$options          = $this->get_options();
		$options[ $name ] = $value;
		return $this->set_options( $options );
	}

	/**
	 * Sets all options in one go
	 *
	 * Passing in empty options deletes the option altogether.
	 *
	 * @param array $opts Options to set, can be empty.
	 */
	public function set_options( $opts ) {
		if ( empty( $opts ) ) {
			$res = delete_option(
				Main::get()->get_hook( self::KEY_OPTIONS )
			);
			$this->initialize();
			return $res;
		}
		return update_option(
			Main::get()->get_hook( self::KEY_OPTIONS ),
			$opts
		);
	}
}
