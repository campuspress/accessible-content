<?php
/**
 * Hooks into preview mode and adds a11y insights there
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Controller;

use Campus\A11y\Controller;
use Campus\A11y\Model\Assets\Preview as Assets;
use Campus\A11y\Model\Attachment;
use Campus\A11y\Main;

class Preview extends Controller {

	public function boot() {
		add_action( 'wp', [ $this, 'dispatch' ] );
		add_filter(
			'image_send_to_editor',
			[ $this, 'add_editor_decorative_role' ],
			PHP_INT_MAX,
			2
		);
		add_filter(
			'render_block',
			[ $this, 'add_block_decorative_role' ],
			PHP_INT_MAX,
			2
		);
	}

	public function dispatch() {
		if ( ! is_singular() ) {
			return false;
		}
		if ( ! current_user_can( 'edit_post', get_post()->ID ) ) {
			return false;
		}
		// Add preview button
		add_action(
			'admin_bar_menu',
			[ $this, 'add_menu_preview' ],
			PHP_INT_MAX
	   );

		if ( ! is_preview() ) {
			return false;
		}
		add_filter( 'the_content', [ $this, 'emphasize_content_area' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'add_scripts' ] );
	}

	public function add_menu_preview( $bar ) {
		if ( is_preview() ) {
			// Already a preview, no sense adding this here.
			return false;
		}
		if ( ! current_user_can( 'edit_post', get_post()->ID ) ) {
			return false;
		}

		$preview_link = $this->get_preview_link();

		$bar->add_menu( [
			'id' => Main::DOMAIN,
			'parent' => null,
			'group' => null,
			'title' => __( 'Accessibility Review', Main::DOMAIN ),
			'href' => esc_url( $preview_link ),
		] );
	}

	/**
	 * Returns post preview link for the current post
	 *
	 * @return string
	 */
	public function get_preview_link() {
		$post = get_post();
		$args = [];
		if ( is_front_page() ) {
			$args = [
				'preview_id' => $post->ID,
				'preview_nonce' => wp_create_nonce( 'post_preview_' . $post->ID ),
			];
		}
		return get_preview_post_link( $post->ID, $args );
	}

	public function add_editor_decorative_role( $html, $attachment_id ) {
		return $this->maybe_make_decorative( $html, $attachment_id );
	}

	public function add_block_decorative_role( $html, $block ) {
		if ( ! is_array( $block ) || 'core/image' !== $block['blockName'] ) {
			return $html;
		}

		// Hack through gutenberg attribute insanity.
		if ( ! empty( $block['attrs']['role'] ) ) {
			return $this->make_decorative(
				preg_replace( '/role="presentation"/', '', $html )
			);
		}

		$attachment_id = ! empty( $block['attrs']['id'] )
			? (int) $block['attrs']['id']
			: false;
		if ( empty( $attachment_id ) ) {
			return $html;
		}

		return $this->maybe_make_decorative( $html, $attachment_id );
	}

	public function maybe_make_decorative( $html, $attachment_id ) {
		// Is it already set as decorative?
		if ( preg_match( '/role=[\'|"]presentation[\'"]/', $html ) ) {
			// Already set as decorative image, carry on.
			return $html;
		}

		// Should it be?
		$attachment = new Attachment( $attachment_id );
		if ( ! $attachment->is_decorative() ) {
			// It shouldn't.
			return $html;
		}

		return $this->make_decorative( $html );
	}

	public function make_decorative( $html ) {
		$html = preg_replace( '/<img /', '<img role="presentation" ', $html );

		return $html;
	}

	public function emphasize_content_area( $content ) {
		return '<div class="campus-a11y-content">' . $content . '</div>';
	}

	public function add_scripts() {
		( new Assets )->enqueue();
	}
}
