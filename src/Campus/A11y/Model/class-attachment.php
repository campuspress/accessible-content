<?php
/**
 * Attachment-specific storage stuff
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model;

use Campus\A11y\Main;

class Attachment {

	/**
	 * Post ID of the attachment
	 *
	 * @var int
	 */
	private $_attachment_id;

	/**
	 * Constructor
	 *
	 * @param int $post_id Post ID of the attachment.
	 */
	public function __construct( $post_id = false ) {
		if ( is_numeric( $post_id ) ) {
			$this->_attachment_id = (int) $post_id;
		}
	}

	/**
	 * Attachment from URL factory
	 *
	 * @param string $url Media URL for the attachment.
	 *
	 * @return object Attachment instance.
	 */
	static public function from_url( $url ) {
		$attachment_id = 0;
		$home          = home_url();

		if ( strpos( $url, $home ) === false ) {
			return new self;
		}

		$dir = wp_upload_dir();
		if ( false === strpos( $url, $dir['baseurl'] . '/' ) ) {
			return new self;
		}

		$file       = basename( $url );
		$query_args = array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'value'   => $file,
					'compare' => 'LIKE',
					'key'     => '_wp_attachment_metadata',
				),
			),
		);
		$query      = new \WP_Query( $query_args );
		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post_id ) {
				$meta                = wp_get_attachment_metadata( $post_id );
				$original_file       = basename( $meta['file'] );
				$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
				if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
					$attachment_id = $post_id;
					break;
				}
			}
		}
		return new self( $attachment_id );
	}

	/**
	 * Gets decorative image postmeta option name
	 *
	 * @return string
	 */
	static public function get_decorative_meta() {
		return '_' . Main::get()->get_hook( 'decorative_image' );
	}

	/**
	 * Gets the attachment ID
	 *
	 * @return int Zero if the attachment doesn't exist, or its post ID
	 */
	public function get_id() {
		return ! empty( $this->_attachment_id )
			? (int) $this->_attachment_id
			: 0;
	}

	/**
	 * Whether or not the attachment is purely decorative
	 *
	 * @return bool
	 */
	public function is_decorative() {
		$attachment_id = $this->get_id();
		if ( empty( $attachment_id ) ) {
			return false;
		}

		return (bool) \get_post_meta(
			$attachment_id,
			self::get_decorative_meta(),
			true
		);
	}

	/**
	 * Sets attachment decorative flag
	 *
	 * @param bool $is_decorative Whether the attachment is decorative or not.
	 *
	 * @return bool Whether the flag got changed.
	 */
	public function set_decorative( $is_decorative ) {
		if ( empty( $this->get_id() ) ) {
			return false;
		}
		return update_post_meta(
			$this->get_id(),
			self::get_decorative_meta(),
			(bool) $is_decorative
		);
	}

	/**
	 * Returns stored image alt
	 *
	 * @return string
	 */
	public function get_alt() {
		$attachment_id = $this->get_id();
		if ( empty( $attachment_id ) ) {
			return '';
		}
		return \get_post_meta(
			$attachment_id,
			'_wp_attachment_image_alt',
			true
		);
	}

	/**
	 * Sets attachment alt text
	 *
	 * @param string $alt Alt text to store.
	 *
	 * @return bool Whether the alt text got changed.
	 */
	public function set_alt( $alt ) {
		if ( empty( $this->get_id() ) ) {
			return false;
		}
		return update_post_meta(
			$this->get_id(),
			'_wp_attachment_image_alt',
			$alt
		);
	}
}
