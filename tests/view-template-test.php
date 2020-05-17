<?php

namespace Campus\A11y;

/**
 * @group core
 * @group view
 * @group template
 */
class View_Template_Test extends Test\UnitTestCase {

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\View\\Template' )
		);
	}

	public function est_get_template_path_returns_false_on_missing_path() {
		$tpl = new View\Template;
		$this->expects(
			false,
			$tpl->get_template_path( 'whatever' )
		);
	}

	public function test_get_template_path_returns_false_on_parent_path() {
		$tpl = new View\Template;
		$this->expects(
			false,
			$tpl->get_template_path( '../src/loader' )
		);
	}

	public function test_get_returns_empty_string_on_invalid_template() {
		$tpl = new View\Template;
		$this->expects(
			'',
			$tpl->get( 'whatever' )
		);
	}

	public function test_get_attr_returns_prefixed_attribute() {
		$tpl = new View\Template;
		$this->expects(
			'campus-a11y',
			$tpl->get_attr()
		);
		$this->expects(
			'campus-a11y',
			$tpl->get_attr()
		);
		$this->expects(
			'campus-a11y-test',
			$tpl->get_attr( 'test' )
		);
	}

	public function test_get_attr_returns_escaped_attribute() {
		$tpl = new View\Template;
		$this->expects(
			'campus-a11y-testscript',
			$tpl->get_attr( 'test<script>' )
		);
		$this->expects(
			'campus-a11y-test',
			$tpl->get_attr( 'test"' )
		);
		$this->expects(
			'campus-a11y-test',
			$tpl->get_attr( "test'" )
		);
	}

	public function test_attr_output_equals_get_attr() {
		$tpl   = new View\Template;
		$tests = array(
			false,
			'test1',
			'test2',
		);
		foreach ( $tests as $test ) {
			ob_start();
			$tpl->attr( $test );
			$out = ob_get_clean();

			$this->expects( $tpl->get_attr( $test ), $out );
		}
	}

	public function test_attrs_echoes_empty_string_with_no_args() {
		$tpl = new View\Template;

		ob_start();
		$tpl->attrs();
		$out = ob_get_clean();

		$this->expects( '', $out );
	}

	public function test_attrs_echoes_space_separated_prefixed_strings() {
		$tpl = new View\Template;

		ob_start();
		$tpl->attrs( 'test1', 'test2' );
		$out = ob_get_clean();

		$this->expects(
			'campus-a11y-test1 campus-a11y-test2',
			$out
		);
	}

	public function test_render_echoes_string_with_existing_template() {
		$tpl = new View\Template;

		ob_start();
		$tpl->render( 'settings/page', [ 'test' => 'test' ] );
		$out = ob_get_clean();

		$this->assertTrue( is_string( $out ) );
		$this->assertFalse( empty( $out ) );
	}
}
