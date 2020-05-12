<?php
/**
 * Preview assets concrete implementation model
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model\Assets;

use Campus\A11y\Model\Assets;
use Campus\A11y\Model\Issues;
use Campus\A11y\View\Messages\Issues as Messages;

class Preview extends Assets {

	public function get_data() {
		$post_id = get_post()->ID;
		return [
			'ajax' => esc_url( admin_url( 'admin-ajax.php' ) ),
			'media' => admin_url( '/upload.php?page=campus-a11y&type=noalt' ),
			'ignores' => Issues::get_ignored_issues_for( $post_id ),
			'post' => [
				'id' => $post_id,
			],
			'ignorable' => Issues::get_ignorable_types(),
			'queryable' => Issues::get_queryable_types(),
			'toggleable' => Issues::get_toggleable_types(),
			'strings' => ( new Messages )->get_messages(),
		];
	}
}
