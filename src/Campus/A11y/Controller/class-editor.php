<?php
/**
 * Hooks into post editor and hijacks the publish button
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Controller;

use Campus\A11y\Controller;
use Campus\A11y\Model\Assets\Editor as Assets;
use Campus\A11y\Main;

class Editor extends Controller {

	public function boot() {
		add_action( 'load-post.php', [ $this, 'dispatch' ] );
		add_action( 'load-post-new.php', [ $this, 'dispatch' ] );
	}

	public function dispatch() {
		add_action( 'admin_enqueue_scripts', [ $this, 'add_scripts' ] );
	}

	public function add_scripts() {
		if ( ! current_user_can( 'edit_post', get_post()->ID ) ) {
			return false;
		}
		$assets = new Assets;
		$assets->enqueue();
	}
}
