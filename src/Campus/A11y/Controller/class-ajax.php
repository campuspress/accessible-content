<?php
/**
 * AJAX actions handler
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Controller;

use Campus\A11y\Controller;
use Campus\A11y\Model\Attachment;
use Campus\A11y\Main;

class Ajax extends Controller {

	public function boot() {
		add_action( 'wp_ajax_campus_a11y_ignore', [ $this, 'update_ignores' ] );

		add_action(
			'wp_ajax_campus_a11y_update_alt',
			[ $this, 'update_alt' ]
		);
		add_action(
			'wp_ajax_campus_a11y_update_decorative',
			[ $this, 'update_decorative' ]
		);
		add_action(
			'wp_ajax_campus_a11y_update_decorative_url',
			[ $this, 'update_decorative_url' ]
		);
	}

	public function update_ignores() {
		$data    = stripslashes_deep( $_POST );
		$post_id = ! empty( $data['post_id'] )
			? (int) $data['post_id']
			: false;
		if ( ! $post_id ) {
			return wp_send_json_error();
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return wp_send_json_error();
		}

		$raw = ! empty( $data['ignore'] )
			? $data['ignore']
			: [];
		if ( ! is_array( $raw ) ) {
			return wp_send_json_error();
		}

		// validate ignores list
		$ignores = [];
		foreach ( $raw as $node => $messages ) {
			if ( ! is_array( $messages ) ) {
				continue;
			}
			$ignores[ sanitize_key( $node ) ] = array_unique(
				array_map(
					'sanitize_key',
					$messages
				)
			);
		}

		update_post_meta( $post_id, Main::get()->get_hook( 'ignores' ), $ignores );
		wp_send_json_success( $ignores );
	}

	public function update_alt() {
		$data    = stripslashes_deep( $_POST );
		$post_id = ! empty( $data['post_id'] )
			? (int) $data['post_id']
			: false;
		if ( ! $post_id ) {
			return wp_send_json_error();
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return wp_send_json_error();
		}

		$alt = ! empty( $data['alt'] )
			? sanitize_text_field( $data['alt'] )
			: '';

		( new Attachment( $post_id ) )
			->set_alt( $alt );

		return wp_send_json_success();
	}

	public function update_decorative() {
		$data    = stripslashes_deep( $_POST );
		$post_id = ! empty( $data['post_id'] )
			? (int) $data['post_id']
			: false;
		if ( ! $post_id ) {
			return wp_send_json_error();
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return wp_send_json_error();
		}

		$is_decorative = ! empty( $data['is_decorative'] );
		( new Attachment( $post_id ) )
			->set_decorative( $is_decorative );

		return wp_send_json_success();
	}

	public function update_decorative_url() {
		$data    = stripslashes_deep( $_POST );
		$post_id = ! empty( $data['post_id'] )
			? (int) $data['post_id']
			: false;
		if ( ! $post_id ) {
			return wp_send_json_error();
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return wp_send_json_error();
		}

		$image_url = ! empty( $data['image_url'] )
			? esc_url( sanitize_text_field( $data['image_url'] ) )
			: false;
		if ( empty( $image_url ) ) {
			return wp_send_json_error();
		}
		$attachment = Attachment::from_url( $image_url );
		if ( empty( $attachment->get_id() ) ) {
			return wp_send_json_error();
		}
		if ( ! current_user_can( 'edit_post', $attachment->get_id() ) ) {
			return wp_send_json_error();
		}

		$attachment->set_decorative( true );
		return wp_send_json_success();
	}

}
