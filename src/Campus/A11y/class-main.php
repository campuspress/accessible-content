<?php
/**
 * Main plugin entry point
 *
 * @package campus-a11y
 */

namespace Campus\A11y;

/**
 * Main plugin entry point
 *
 * Bootstraps the plugin by initializing the controllers.
 */
class Main extends Controller {

	const DOMAIN = 'campus-a11y';


	public function boot() {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		$this->load_translations();

		Controller\Preview::get()->boot();

		if ( is_admin() ) {
			Controller\Editor::get()->boot();
			Controller\Ajax::get()->boot();

			Controller\Admin::get()->boot();
			Controller\Admin\Media::get()->boot();
			Controller\Admin\Settings::get()->boot();
		} else {
			Controller\Content::get()->boot();
		}
	}

	/**
	 * Loads the plugin translations
	 */
	public function load_translations() {
		$dirname  = Model\Constants\Plugin::get()->get_value( 'PLUGIN_DIR' );
		load_plugin_textdomain(
			'campus-a11y',
			false,
			trailingslashit( $dirname ) . 'languages'
		);
	}

	/**
	 * Returns the WP output of `plugin_basename` for this plugin.
	 *
	 * @uses plugin_basename()
	 *
	 * @return string
	 */
	public function plugin_basename() {
		$dirname  = Model\Constants\Plugin::get()->get_value( 'PLUGIN_DIR' );
		$filename = Model\Constants\Plugin::get()->get_value( 'PLUGIN_FILENAME' );
		return plugin_basename( "{$dirname}/{$filename}" );
	}

	/**
	 * Returns the WP output of `plugin_dir_path` for this plugin.
	 *
	 * @uses plugin_dir_path()
	 *
	 * @return string
	 */
	public function plugin_dir_path() {
		return plugin_dir_path(
			Model\Constants\Plugin::get()->get_value( 'PLUGIN_FILE' )
		);
	}

	/**
	 * Returns the WP output of `plugins_url` for this plugin.
	 *
	 * @uses plugins_url()
	 *
	 * @return string
	 */
	public function plugins_url() {
		return plugins_url(
			'/',
			Model\Constants\Plugin::get()->get_value( 'PLUGIN_FILE' )
		);
	}

	/**
	 * Returns plugin version
	 *
	 * @return string
	 */
	public function get_version() {
		return Model\Constants\Plugin::get()->get_value( 'VERSION' );
	}

	/**
	 * Returns prefixed WP hook name
	 *
	 * @param string $basename Base hook name.
	 *
	 * @return string Prefixed hook name
	 */
	public function get_hook( $basename ) {
		return str_replace( '-', '_', self::DOMAIN . '_' . $basename );
	}
}
