<?php
/**
 * Messaging abstraction class
 *
 * @package campus-a11y
 */

namespace Campus\A11y\View;

use Campus\A11y\Main;

/**
 * Messaging abstraction
 */
class Messages {

	const CODE_GENERIC = 1;
	const CODE_MISSING_NONCE = 50;
	const CODE_INVALID_NONCE = 60;

	public function get_message( $code ) {
		$msgs = $this->get_messages();
		if ( ! isset( $msgs[ $code ] ) ) {
			return '';
		}

		return $msgs[ $code ];
	}

	public function get_messages() {
		return [
			self::CODE_GENERIC => __( 'Something went wrong.', Main::DOMAIN ),
			self::CODE_MISSING_NONCE => __( 'Required argument missing.', Main::DOMAIN ),
			self::CODE_INVALID_NONCE => __( 'You are not allowed to do this.', Main::DOMAIN ),
		];
	}
}

