<?php

namespace Campus\A11y;

/**
 * @group core
 * @group controller
 * @group preview
 */
class Controller_Preview_Test extends Test\UnitTestCase {

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\Controller\\Preview' )
		);
		$ctrl = Controller\Preview::get();
		$this->assertTrue(
			$ctrl instanceof Controller,
			'should be a controller'
		);
		$this->assertTrue(
			$ctrl instanceof Singleton,
			'should be a singleton'
		);
	}

	public function test_emphasize_content_area_wraps_content() {
		$ctrl = Controller\Preview::get();
		foreach ( $this->get_test_content_array() as $test ) {
			$this->expects(
				'<div class="campus-a11y-content">' . $test . '</div>',
				$ctrl->emphasize_content_area( $test )
			);
		}
	}

	public function test_make_decorative_ignores_nonimage_content() {
		$ctrl = Controller\Preview::get();
		foreach ( $this->get_test_content_array() as $test ) {
			$this->expects(
				$test,
				$ctrl->make_decorative( $test )
			);
		}
	}

	public function test_maybe_make_decorative_ignores_nonimage_content() {
		$ctrl = Controller\Preview::get();
		foreach ( $this->get_test_content_array() as $test ) {
			$this->expects(
				$test,
				$ctrl->maybe_make_decorative( $test, 1312 )
			);
		}
	}

	public function get_test_content_array() {
		return array_merge(
			$this->get_html_content_array(),
			$this->get_text_content_array(),
		);
	}

	public function get_html_content_array() {
		return [
			'<p>This is a test paragraph.</p>',
			'<p>This is 2</p><p>test paragraphs.</p>',
			"<p>This is 3</p>\n<p>test</p>\n<p>paragraphs.</p>",
		];
	}

	public function get_text_content_array() {
		return [
			'This is a plaintext string',
			"This is\na plaintext string\nwith newlines",
		];
	}


}
