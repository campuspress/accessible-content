<?php
/**
 * Plugin templating
 *
 * @package campus-a11y
 */

namespace Campus\A11y\View;

use Campus\A11y\Main;

/**
 * Houses all the templating
 */
class Template {

	/**
	 * Resolves relative template path to an actual absolute path
	 *
	 * @param string $relpath Relative template path.
	 *
	 * @return string
	 */
	public function get_template_path( $relpath ) {
		$root = wp_normalize_path( Main::get()->plugin_dir_path() . 'tpl/' );
		$path = wp_normalize_path( realpath( "{$root}{$relpath}.php" ) );

		return $path && preg_match( '/^' . preg_quote( $root, '/' ) . '/', $path )
			? $path
			: '';
	}

	/**
	 * Renders the template with supplied arguments
	 *
	 * @param string $relpath Relative template path.
	 * @param array  $args Optional arguments.
	 *
	 * @return bool
	 */
	public function render( $relpath, $args = array() ) {
		$template = $this->get_template_path( $relpath );
		if ( empty( $template ) ) {
			return false;
		}

		if ( ! empty( $args ) ) {
			// @codingStandardsIgnoreLine Using extract for templating
			extract( $args, EXTR_PREFIX_SAME, 'view_' );
		}
		include( $template );

		return true;
	}

	/**
	 * Gets rendered template with supplied arguments as a string
	 *
	 * @param string $relpath Relative template path.
	 * @param array  $args Optional arguments.
	 *
	 * @return string
	 */
	public function get( $relpath, $args = array() ) {
		ob_start();
		$this->render( $relpath, $args );
		return ob_get_clean();
	}

	/**
	 * Echoes prefixed HTML attribute
	 *
	 * @param string $att Optional attribute.
	 */
	public function attr( $att = '' ) {
		echo $this->get_attr( $att );
	}

	/**
	 * Gets escaped and prefixed HTML attribute
	 *
	 * @param string $att Optional attribute.
	 *
	 * @return string
	 */
	public function get_attr( $att = '' ) {
		if ( empty( $att ) ) {
			return esc_attr( Main::DOMAIN );
		}

		return esc_attr(
			sanitize_html_class(
				sprintf(
					'%s-%s',
					Main::DOMAIN,
					$att
				)
			)
		);
	}

	/**
	 * Echoes escaped and prefixed list of arguments
	 *
	 * @param mixed Attributes to echo.
	 */
	public function attrs() {
		$attrs = array_map(
			array( $this, 'get_attr' ),
			func_get_args()
		);
		echo join( ' ', $attrs );
	}
}
