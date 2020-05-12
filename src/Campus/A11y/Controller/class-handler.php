<?php
/**
 * Request handler controller abstraction
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Controller;

use Campus\A11y\Controller;
use Campus\A11y\Model\Access;
use Campus\A11y\Model\Nonce;
use Campus\A11y\View\Messages;

/**
 * Request handler parent controller
 */
abstract class Handler extends Controller {

	const STATUS_SUCCESS = 'success';
	const STATUS_ERROR   = 'error';

	abstract public function process();

	public function boot() {
		if ( ! $this->is_allowed() ) {
			wp_die( 'Nope.' );
		}
		$this->process();
	}

	/**
	 * Whether or not the current URL has error status
	 *
	 * @return bool
	 */
	static public function is_error_status() {
		$status = ! empty( $_GET['status'] )
			? sanitize_key( $_GET['status'] )
			: false;
		return self::STATUS_ERROR === $status;
	}

	/**
	 * Validates submitted nonce against the action
	 *
	 * Redirects with status on failure.
	 *
	 * @param array $args Request args ( $_GET|$_POST ).
	 * @param string $action Nonce action to check.
	 * @param string $nonce_name Optional nonce name.
	 *
	 * @return bool
	 */
	public function validate_nonce( $args, $action, $nonce_name = false ) {
		$name = ! empty( $nonce_name )
			? $nonce_name
			: '_wpnonce';
		if ( empty( $args[ $name ] ) ) {
			return $this->redirect_error( Messages::CODE_MISSING_NONCE );
		}
		if ( ! Nonce::is_valid( $args[ $name ], $action ) ) {
			return $this->redirect_error( Messages::CODE_INVALID_NONCE );
		}
		return true;
	}

	/**
	 * Redirects successful request
	 */
	public function redirect_success() {
		return $this->redirect_status( self::STATUS_SUCCESS );
	}

	/**
	 * Redirects with error status
	 *
	 * @param int $code Optional status code.
	 */
	public function redirect_error( $code = false ) {
		return $this->redirect_status( self::STATUS_ERROR, $code );
	}

	/**
	 * Redirects with status
	 *
	 * @param string $status Status to redirect with (success|error).
	 * @param int    $code Optional error code.
	 */
	public function redirect_status( $status, $code = false ) {
		$page = ! empty( $_GET['page'] )
			? sanitize_text_field( $_GET['page'] )
			: false;
		$url  = remove_query_arg( array_keys( $_GET ) );

		$args = [
			'page'   => $page,
			'status' => $status,
		];
		if ( self::STATUS_ERROR === $status ) {
			$code         = ! empty( $code )
				? (int) $code
				: Messages::CODE_GENERIC;
			$args['code'] = $code;
		}
		wp_safe_redirect(
			esc_url_raw(
				add_query_arg( $args, $url )
			)
		);
		return $this->drop_dead();
	}

	/**
	 * Basic permissions check
	 *
	 * @return bool
	 */
	public function is_allowed() {
		if ( ! is_admin() ) {
			return false;
		}
		if ( ! current_user_can( Access::get_capability() ) ) {
			return false;
		}
		return true;
	}

}

