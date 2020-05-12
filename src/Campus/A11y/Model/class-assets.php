<?php
/**
 * Assets handling model
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model;

use Campus\A11y\Main;

abstract class Assets {

	public function enqueue() {
		$insights = Main::get()->get_hook( 'insights' );
		$infix = empty( $this->is_in_debug_mode() )
			? '.min' : '';
		wp_enqueue_script(
			$insights,
			Main::get()->plugins_url() . "assets/js/campus-a11y{$infix}.js",
			$this->get_dependencies(),
			Main::get()->get_version()
		);
		wp_localize_script( $insights, $insights, $this->get_data() );
		wp_enqueue_style(
			Main::DOMAIN,
			Main::get()->plugins_url() . "assets/css/campus-a11y{$infix}.css",
			Main::get()->get_version()
		);
	}

	public function get_dependencies() {
		return [ 'jquery' ];
	}

	public function get_data() {
		return [
			'ajax' => esc_url( admin_url( 'admin-ajax.php' ) ),
			'post' => [
				'id' => get_post()->ID,
			],
		];
	}

	public function is_in_debug_mode() {
		return (bool) Constants::get()->get_value( 'SCRIPT_DEBUG' );
	}
}
