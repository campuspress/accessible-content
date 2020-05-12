<?php
/**
 * Admin assets concrete implementation model
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model\Assets;

use Campus\A11y\Model\Assets;
use Campus\A11y\Main;

class Admin extends Assets {

	public function enqueue() {
		$insights = Main::get()->get_hook( 'insights-admin' );
		$infix = empty( $this->is_in_debug_mode() )
			? '.min' : '';
		wp_enqueue_script(
			$insights,
			Main::get()->plugins_url() . "assets/js/admin{$infix}.js",
			$this->get_dependencies(),
			Main::get()->get_version()
		);
		wp_enqueue_style(
			Main::DOMAIN,
			Main::get()->plugins_url() . "assets/css/admin{$infix}.css",
			Main::get()->get_version()
		);
	}
}
