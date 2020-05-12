<?php
/**
 * WP Nonce handling model
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model;

use Campus\A11y\Main;

/**
 * Nonce handling class
 */
class Nonce {

	const ACTION_SETTINGS = 'settings';

	static public function create( $action ) {
		return wp_create_nonce( self::get_nonce( $action ) );
	}

	static public function is_valid( $nonce, $action ) {
		return false !== wp_verify_nonce(
			$nonce,
			self::get_nonce( $action )
		);
	}

	static public function get_nonce( $action ) {
		$action = ! empty( $action )
			? sanitize_key( $action )
			: self::ACTION_GENERIC;
		return sprintf(
			'%s-action-%s',
			Main::DOMAIN,
			$action
		);
	}
}
