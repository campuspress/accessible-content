<?php
/**
 * Campus Accessibility main plugin file
 *
 * @package campus-a11y
 */

/**
 * Plugin Name: Accessible Content
 * Description: Accessibility content inspector and improvements companion
 * Version: 1.0.0-beta.4
 * Text Domain: campus-a11y
 * Author: Campus team
 */

namespace Campus\A11y;

define( 'CAMPUS_A11Y_VERSION', '1.0.0-beta.4' );

define( __NAMESPACE__ . '\PLUGIN_FILE', __FILE__ );
define( __NAMESPACE__ . '\PLUGIN_DIR', basename( dirname( __FILE__ ) ) );
define( __NAMESPACE__ . '\PLUGIN_FILENAME', basename( __FILE__ ) );

require_once dirname( __FILE__ ) . '/src/exceptions.php';
require_once dirname( __FILE__ ) . '/src/loader.php';

add_action( 'init', [ Main::get(), 'boot' ] );
