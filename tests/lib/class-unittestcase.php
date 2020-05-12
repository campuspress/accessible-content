<?php

namespace Campus\A11y\Test;

class UnitTestCase extends \WP_UnitTestCase {

	public function pass() {
		return $this->assertTrue( true );
	}

	public function expects( $expected, $result ) {
		$type          = $this->_get_type( $expected );
		$type_callback = $this->_get_typecheck( $expected );
		$is_empty      = empty( $expected ) ? 'assertTrue' : 'assertFalse';

		$debug = json_encode( $result );

		$should = empty( $expected ) ? 'should' : 'should not';
		$this->$is_empty(
			empty( $result ),
			"{$debug} {$should} be empty: expected " . json_encode( $expected )
		);
		if ( function_exists( $type_callback ) ) {
			$this->assertTrue(
				$type_callback( $result ),
				"{$debug} should be {$type}"
			);
		}
		$this->assertEquals( $expected, $result );
	}

	public function expects_length( $length, $result ) {
		$is_empty = $length >= 1 ? 'assertFalse' : 'assertTrue';
		$debug    = json_encode( $result );

		$this->assertTrue( is_array( $result ), "{$debug} should be an array" );

		$should = empty( $expected ) ? 'should' : 'should not';
		$this->$is_empty(
			empty( $result ),
			"{$debug} {$should} be empty"
		);
		$this->assertEquals(
			$length,
			count( $result ),
			"{$debug} should have {$length} members"
		);
	}

	private function _get_typecheck( $what ) {
		$type = $this->_get_type( $what );
		return function_exists( "is_{$type}" )
			? "is_{$type}"
			: false;
	}

	private function _get_type( $what ) {
		if ( is_object( $what ) ) {
			return 'object';
		}
		if ( is_array( $what ) ) {
			return 'array';
		}
		if ( is_numeric( $what ) ) {
			return 'numeric';
		}
		if ( is_string( $what ) ) {
			return 'string';
		}
	}

	public function get_tmpfile( $name ) {
		return trailingslashit( CAMPUS_A11Y_TESTS_DATA_DIR ) . $name;
	}
}
