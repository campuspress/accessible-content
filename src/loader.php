<?php
/**
 * Autoloader
 *
 * @package campus-a11y
 */

namespace Campus\A11y;

/**
 * Class name to file mapping procedure
 *
 * @param string $class Class name.
 */
function autoload( $class ) {
	if ( ! preg_match( '/^Campus\\\A11y/i', $class ) ) {
		return false;
	}

	$class = str_replace( '\\', DIRECTORY_SEPARATOR, $class );
	$raw   = explode( DIRECTORY_SEPARATOR, $class );
	$file  = 'class-' . strtolower( array_pop( $raw ) ) . '.php';
	$path  = trailingslashit( dirname( __FILE__ ) ) .
		join( DIRECTORY_SEPARATOR, $raw ) . "/{$file}";

	if ( is_readable( $path ) ) {
		require_once( $path );
	}
}
spl_autoload_register( __NAMESPACE__ . '\\autoload' );

