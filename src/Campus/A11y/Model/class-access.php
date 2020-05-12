<?php
/**
 * User access model
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model;

/**
 * Houses all the role access checks
 */
class Access {

	/**
	 * Returns required capability level
	 *
	 * @return string WP role
	 */
	static public function get_capability() {
		return 'manage_options';
	}

	/**
	 * Whether or not the current user can upload files
	 *
	 * @return bool
	 */
	static public function can_upload() {
		return current_user_can( 'upload_files' );
	}
}
