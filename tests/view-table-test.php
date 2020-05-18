<?php

namespace Campus\A11y;

/**
 * @group core
 * @group view
 * @group table
 */
class View_Table_Test extends Test\Admin\UnitTestCase {

	public function setUp() {
		parent::setUp();
		$this->mock_is_admin();
	}

	public function tearDown() {
		parent::tearDown();
		$this->unmock_is_admin();
	}

	public function test_exists() {
		$this->assertTrue(
			class_exists( __NAMESPACE__ . '\\View\\Table' )
		);
		$table = new View\Table();
		$this->assertTrue(
			$table instanceof \WP_List_Table,
			'should be a list table instance'
		);
	}

	public function test_get_columns_returns_array() {
		$table = new View\Table();
		$cols = $table->get_columns();
		$this->assertTrue( is_array( $cols ) );
		$this->assertEquals( 3, count( $cols ), 'should have 3 columns' );
	}

	public function test_get_types_returns_array() {
		$table = new View\Table();
		$types = $table->get_types();
		$this->assertTrue( is_array( $types ) );
		$this->assertEquals( 5, count( $types ), 'should have 5 types' );
	}

	public function test_get_view_type_should_default_to_noalt() {
		$table = new View\Table();
		$this->expects(
			'noalt',
			$table->get_view_type()
		);
	}

	public function test_get_view_type_should_default_to_noalt_with_invalid_type() {
		$table = new View\Table();
		$_GET['type'] = 1312;
		$this->expects(
			'noalt',
			$table->get_view_type()
		);
		unset( $_GET['type'] );
	}

	public function test_get_view_type_should_return_valid_type() {
		$table = new View\Table();
		$_GET['type'] = 'withalt';
		$this->expects(
			'withalt',
			$table->get_view_type()
		);
		unset( $_GET['type'] );
	}

	public function test_get_view_type_query_returns_queries_for_all_types() {
		$table = new View\Table();
		foreach( $table->get_types() as $type ) {
			$query = $table->get_view_type_query( $type );
			$this->assertTrue( is_array( $query ), "query should be an array for $type" );
			$this->assertFalse( empty( $query ), "query should not be empty for $type" );
		}
	}

	public function test_get_view_query_returns_proper_view_query() {
		$table = new View\Table();
		$default = $table->get_view_type_query();

		$_GET['type'] = 'withalt';
		$withalt = $table->get_view_type_query();

		$this->assertNotEquals( $default, $withalt, "two queries should be different" );
		unset( $_GET['type'] );
	}

	public function test_column_image_returns_image_tag() {
		$table = new View\Table();
		add_filter( 'wp_get_attachment_image_src', [ $this, 'get_random_url' ] );
		$field = $table->column_image( $this->get_fake_image() );
		remove_filter( 'wp_get_attachment_image_src', [ $this, 'get_random_url' ] );

		$this->assertTrue(
			(bool) preg_match( '/<img /', $field ),
			'field has image tag'
		);
	}

	public function test_column_post_returns_link_tag() {
		$table = new View\Table();
		$field = $table->column_post( $this->get_fake_image() );

		$this->assertTrue(
			(bool) preg_match( '/<a /', $field ),
			'field has link tag'
		);
	}

	public function get_fake_image() {
		$post_parent = $this->factory->post->create_and_get([
			'post_title' => 'Test Post',
		]);
		$post = $this->factory->post->create_and_get([
			'post_title' => 'Test Attachment',
			'post_type' => 'attachment',
			'post_parent' => $post_parent->ID,
		]);
		return $post;
	}

	public function get_random_url() {
		return '//example.com/image.jpg';
	}
}
