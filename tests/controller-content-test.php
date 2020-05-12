<?php

namespace Campus\A11y;

/**
 * @group core
 * @group controller
 * @group content
 */
class Controller_Content_Test extends Test\UnitTestCase {

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\Controller\\Content' )
		);
		$ctrl = Controller\Content::get();
		$this->assertTrue(
			$ctrl instanceof Controller,
			'should be a singleton'
		);
		$this->assertTrue(
			$ctrl instanceof Singleton,
			'should be a singleton'
		);
	}

	public function test_process_content_images_returns_string_verbatim_with_no_images() {
		$ctrl = Controller\Content::get();
		$test = 'Hello there <p>some markup whatever</p>';
		$this->expects(
			$test,
			$ctrl->process_content_images( $test )
		);
	}

	public function test_process_content_images_returns_string_verbatim_with_alt_images() {
		$tests = [
			'<img src="whatever" alt="so this is my beautiful image" />',
			'<img src="whatever1" alt="alt1" /><img src="whatever2" alt="alt2" />',
		];
		$ctrl = Controller\Content::get();
		foreach( $tests as $test ) {
			$this->expects(
				$test,
				$ctrl->process_content_images( $test )
			);
		}
	}

	public function test_process_content_images_returns_alt_images() {
		$tests = [
			'<img src="whatever" alt="" />',
			'<img src="whatever1" alt />',
			'<img src="whatever2" />',
			'<img src="whatever1" alt /><img src="whatever2" />',
		];
		$ctrl = Mock_Controller_Content_FixedAlt::get();
		$rx = '/' . preg_quote( Mock_Controller_Content_FixedAlt::ALT, '/' ) . '/';
		foreach( $tests as $test ) {
			$result = $ctrl->process_content_images( $test );
			$this->assertTrue(
				(bool) preg_match( $rx, $result ),
				"{$result} should contain: " . Mock_Controller_Content_FixedAlt::ALT
			);
		}
	}
}

if ( ! class_exists( 'Mock_Controller_Content_FixedAlt' ) ) {
	class Mock_Controller_Content_FixedAlt extends Controller\Content {
		const ALT = 'unique alt text';

		public function get_attachment_alt( $href ) {
			return self::ALT;
		}
	}
}
