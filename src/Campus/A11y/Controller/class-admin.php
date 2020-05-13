<?php
/**
 * Admin pages parent controller class
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Controller;

use Campus\A11y\Controller;
use Campus\A11y\Model\Access;
use Campus\A11y\View\Template;
use Campus\A11y\Main;
use Campus\A11y\Model\Assets\Admin as Assets;

/**
 * Admin area controller.
 *
 * Establishes the root menu item and allows for overrides from concrete page
 * implementations
 */
class Admin extends Controller {

	/**
	 * Holds template model reference
	 *
	 * @var object View\Template instance
	 */
	private $_template;

	/**
	 * Hooks up to WP hooks API
	 */
	public function boot() {
		if ( ! is_admin() ) {
			return false;
		}
		if ( ! $this->can_user_access_page() ) {
			return false;
		}

		add_action(
			'admin_menu',
			[ $this, 'add_menu_page' ]
		);

		$main = Main::get();
		if ( ! has_filter( 'plugin_action_links_' . $main->plugin_basename() ) ) {
			add_filter(
				'plugin_action_links_' . $main->plugin_basename(),
				[ $this, 'add_setting_links' ]
			);
		}

		return true;
	}

	/**
	 * Adds menu entry
	 */
	public function add_menu_page() {}
	/**
	 * Sets up front-end dependencies
	 */
	public function add_dependencies() {
		if ( ! $this->can_user_access_page() ) {
			return false;
		}
		( new Assets )->enqueue();
	}

	/**
	 * Template object lazy loading resolution
	 *
	 * @return object Template object instance
	 */
	public function get_template() {
		if ( empty( $this->_template ) ) {
			$this->_template = new Template;
		}
		return $this->_template;
	}

	/**
	 * Returns root page capability
	 *
	 * @return string
	 */
	public function get_capability() {
		return Access::get_capability();
	}

	/**
	 * Augments plugin links with settings
	 *
	 * @param array $links Links for the plugin.
	 *
	 * @return array
	 */
	public function add_setting_links( $links ) {
		$settings = network_admin_url(
			'admin.php?page=' . Main::DOMAIN . '-settings'
		);
		$mylinks  = array(
			'<a href="' . esc_url( $settings ) . '">' .
				__( 'Settings', Main::DOMAIN ) . '</a>',
		);

		return array_merge( $links, $mylinks );
	}

	/**
	 * Whether user can access this page
	 *
	 * Can be overridden in page implementations.
	 *
	 * @uses current_user_can()
	 * @uses Admin::get_capability()
	 *
	 * @return bool
	 */
	public function can_user_access_page() {
		if ( ! current_user_can( $this->get_capability() ) ) {
			return false;
		}
		return true;
	}
}
