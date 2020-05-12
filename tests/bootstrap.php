<?php

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

define( 'CAMPUS_A11Y_IS_TEST_ENV', true );

require_once $_tests_dir . '/includes/functions.php';

function _manually_load_plugin() {
	if ( ! defined( 'CAMPUS_A11Y_TESTS_DATA_DIR' ) ) {
		define(
			'CAMPUS_A11Y_TESTS_DATA_DIR',
			trailingslashit( dirname( __FILE__ ) ) . 'data'
		);
	}

	require_once dirname( dirname( __FILE__ ) ) . '/campus-a11y.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require_once $_tests_dir . '/includes/bootstrap.php';
require_once( dirname( __FILE__ ) . '/lib/class-unittestcase.php' );
/* require_once( dirname( __FILE__ ) . '/lib/mock/class-admin-screen.php' ); */
/* require_once( dirname( __FILE__ ) . '/lib/mock/trait-role-mocker.php' ); */
/* require_once( dirname( __FILE__ ) . '/lib/mock/trait-admin-mocker.php' ); */
/* require_once( dirname( __FILE__ ) . '/lib/class-admin-unittestcase.php' ); */

if ( ! function_exists( 'xd' ) ) {
	function xd() {
		die( var_export( func_get_args() ) );
	}
}
