<?php
/**
 * Admin settings page controller
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Controller\Admin;

use Campus\A11y\Controller;
use Campus\A11y\Main;

/**
 * Admin area settings page controller.
 */
class Settings extends Controller\Admin {

	/**
	 * Adds menu entry
	 */
	public function add_menu_page() {
		$capability = $this->get_capability();
		if ( ! $this->can_user_access_page() ) {
			return false;
		}

		$page = add_options_page(
			_x( 'Accessibility', 'page label', Main::DOMAIN ),
			_x( 'Accessibility', 'menu label', Main::DOMAIN ),
			$capability,
			Main::DOMAIN . '-settings',
			[ $this, 'render' ],
			$this->get_page_order()
		);
		add_action( "load-{$page}", [ $this, 'add_dependencies' ] );
		add_action( "load-{$page}", [ $this, 'save_settings' ] );
	}

	/**
	 * Returns main page order
	 *
	 * @return int
	 */
	public function get_page_order() {
		return 11;
	}

	/**
	 * Renders the page
	 */
	public function render() {
		if ( ! $this->can_user_access_page() ) {
			return wp_die( 'nope' );
		}
		$this->get_template()->render(
			'settings/page'
		);
	}

	/**
	 * Handles settings saving by proxying settings handler controller
	 */
	public function save_settings() {
		Controller\Handler\Settings::get()
			->boot();
	}

}

