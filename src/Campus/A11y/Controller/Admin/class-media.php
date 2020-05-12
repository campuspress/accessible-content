<?php
/**
 * Admin media page controller
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Controller\Admin;

use Campus\A11y\Controller;
use Campus\A11y\Main;

/**
 * Admin area media page controller.
 */
class Media extends Controller\Admin {

	/**
	 * Adds menu entry
	 */
	public function add_menu_page() {
		$capability = $this->get_capability();
		if ( ! $this->can_user_access_page() ) {
			return false;
		}
		$page = add_media_page(
			_x( 'Alt Text', 'page label', Main::DOMAIN ),
			_x( 'Alt Text', 'menu label', Main::DOMAIN ),
			$capability,
			Main::DOMAIN,
			[ $this, 'render' ],
			$this->get_page_order()
		);
		add_action( "load-{$page}", [ $this, 'add_dependencies' ] );
	}

	/**
	 * Returns main page order
	 *
	 * @return int
	 */
	public function get_page_order() {
		return 1;
	}

	/**
	 * Renders the page
	 */
	public function render() {
		if ( ! $this->can_user_access_page() ) {
			return wp_die( 'nope' );
		}
		$this->get_template()->render(
			'media/page'
		);
	}

}

