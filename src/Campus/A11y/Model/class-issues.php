<?php
/**
 * Issues model abstraction
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model;

use Campus\A11y\Main;

/**
 * Issues model class
 */
class Issues {

	const UNIQUE_IDS = 'unique_ids';
	const PDF_FILE = 'pdf_file';
	const DIRECT_DESCENDENT = 'direct_descendent';
	const PARENT_LIST = 'parent_list';
	const LINK_TOO_SHORT = 'link_too_short';
	const LINK_NO_TEXT = 'link_no_text';
	const LINK_EXTERNAL = 'link_external';
	const VIDEO_NO_TITLES = 'video_no_titles';
	const TABLE_NO_DESC = 'table_no_desc';
	const TABLE_BAD_HEADERS = 'table_bad_headers';
	const FRAME_NO_TITLE = 'frame_no_title';
	const NO_LABEL = 'no_label';
	const DANGLING_LABEL = 'dangling_label';
	const EMPTY_LABEL = 'empty_label';
	const ALT_TOO_LONG = 'alt_too_long';
	const ALT_TOO_SHORT = 'alt_too_short';
	const NO_ALT = 'no_alt';
	const CONTRAST_WARN = 'contrast_warn';
	const CONTRAST_ERR = 'contrast_err';
	const HEADINGS_SKIP = 'headings_skip';
	const HEADINGS_MULTIPLE = 'headings_multiple';
	const HEADINGS_DOUBLE = 'headings_double';

	/**
	 * Gets issue types that are allowed to be ignored
	 *
	 * @return array
	 */
	static public function get_ignorable_types() {
		return [
			self::LINK_TOO_SHORT,
			self::NO_ALT,
			self::HEADINGS_MULTIPLE,
		];
	}

	/**
	 * Gets issue types that can be shown in media page
	 *
	 * @return array
	 */
	static public function get_queryable_types() {
		return [
			self::NO_ALT,
			self::ALT_TOO_LONG,
			self::ALT_TOO_SHORT,
		];
	}

	/**
	 * Gets issue types that can be directly actioned
	 *
	 * @return array
	 */
	static public function get_toggleable_types() {
		return [
			self::NO_ALT,
		];
	}

	/**
	 * Gets a map of ignored issues for a post
	 *
	 * @param int $post_id ID of the post.
	 *
	 * @return bool|array False, or a map of the ignores.
	 */
	static public function get_ignored_issues_for( $post_id ) {
		$post_id = (int) $post_id;
		if ( empty( $post_id ) ) {
			return false;
		}
		return get_post_meta( $post_id, Main::get()->get_hook( 'ignores' ), true );
	}
}
