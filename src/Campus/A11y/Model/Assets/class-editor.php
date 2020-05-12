<?php
/**
 * Editor assets concrete implementation model
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model\Assets;

use Campus\A11y\Model\Assets;
use Campus\A11y\Main;

class Editor extends Assets {

	public function get_data() {
		return array_merge( parent::get_data(), [
			'post' => [
				'id' => get_post()->ID,
				'status' => get_post_status( get_post() ),
				'checked' => true,
			],
			'panel_title' => __( 'Accessibility Checklist', Main::DOMAIN ),
			'check_preview' => __( 'Make sure you previewed your post for any a11y issues', Main::DOMAIN ),
			'decorative_image' => __( 'This is a decorative image', Main::DOMAIN ),
		] );
	}

	public function get_dependencies() {
		return [ 'underscore', 'jquery' ];
	}
}
