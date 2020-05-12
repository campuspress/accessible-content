<?php
/**
 * Settings saving controller
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Controller\Handler;

use Campus\A11y\Controller;
use Campus\A11y\Main;
use Campus\A11y\Model\Nonce;
use Campus\A11y\Model\Options;
use Campus\A11y\View\Template;

/**
 * Settings saving handler controller.
 */
class Settings extends Controller\Handler {

	/**
	 * Handles settings saving
	 */
	public function process() {
		if ( empty( $_POST ) ) {
			return false;
		}

		$this->validate_nonce(
			$_POST,
			Nonce::ACTION_SETTINGS
		);

		$tpl = new Template;
		$opts = Options::get();

		$opts->set_option(
			Options::KEY_ENABLE_REPLACEMENT,
			! empty( $_POST[ $tpl->get_attr( Options::KEY_ENABLE_REPLACEMENT ) ] )
		);

		return $this->redirect_success();
	}

}

