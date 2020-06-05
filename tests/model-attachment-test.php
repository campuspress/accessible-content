<?php

namespace Campus\A11y;

/**
 * @group core
 * @group model
 * @group attachment
 */
class Model_Attachment_Test extends Test\UnitTestCase {

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\Model\\Attachment' )
		);
	}

	public function test_get_id_is_zero_by_default() {
		$img = new Model\Attachment;
		$this->assertEquals( 0, $img->get_id(), 'attachment ID should be zero by default' );
	}

	public function test_is_decorative_returns_false_for_fake_attachment() {
		$img = new Model\Attachment;
		$this->assertFalse(
			$img->is_decorative(),
			'images should not decorative by default'
		);
	}

	public function test_get_decorative_meta_returns_valid_meta_name() {
		$img = new Model\Attachment;
		$this->expects(
			'_campus_a11y_decorative_image',
			$img->get_decorative_meta()
		);
	}

	public function test_get_clean_url_returns_protocolless_url() {
		$home = Model\Attachment::get_clean_url( home_url() );
		$this->assertFalse(
			(bool) preg_match( '/^https?/', $home ),
			'url should be protocolless'
		);

		$imgurl = home_url( '/files/2020/05/5-Ways-To-Share-And-Market-Your-Teacher-Blog-1024x683.jpeg' );
		$clean_url = Model\Attachment::get_clean_url( $imgurl );
		$this->assertNotFalse(
			strpos( $clean_url, $home ),
			'clean url should contain home'
		);

		$img = Model\Attachment::from_url( $imgurl );
	}

}
