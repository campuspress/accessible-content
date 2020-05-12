<?php
/**
 * Media list table
 *
 * @package campus-a11y
 */

namespace Campus\A11y\View;

use Campus\A11y\Main;
use Campus\A11y\Model\Attachment;

class Table extends \WP_List_Table {

	protected $_per_page = 20;

	public function __construct() {
		parent::__construct();
		$this->set_pagination();
	}

	public function get_total() {
		$counts = wp_count_posts( 'attachment' );
		$total  = 0;
		if ( ! is_object( $counts ) ) {
			return $total;
		}
		foreach ( (array) $counts as $cnt ) {
			$total += (int) $cnt;
		}
		return $total;
	}

	public function set_pagination() {
		$total                                 = $this->get_total();
		$this->_pagination_args['total_items'] = $total;
		$this->_pagination_args['total_pages'] = floor( $total / $this->_per_page ) + 1;
		$this->_pagination_args['per_page']    = $this->_per_page;
	}

	public function get_page_url() {
		$page = ! empty( $_GET['page'] )
			? sanitize_text_field( $_GET['page'] )
			: false;
		$url  = remove_query_arg( array_keys( $_GET ) );
		return add_query_arg( 'page', $page, $url );
	}

	public function get_columns() {
		return [
			'image' => __( 'Image', Main::DOMAIN ),
			'post' => __( 'Attached to', Main::DOMAIN ),
			'alt' => __( 'Alt', Main::DOMAIN ),
		];
	}

	public function get_types() {
		return [
			'noalt' => __( 'Images with no alt text', Main::DOMAIN ),
			'withalt' => __( 'Images with alt text', Main::DOMAIN ),
			'stopwords' => __( 'Images with non descriptive alts', Main::DOMAIN ),
			'decorative' => __( 'Decorative images only', Main::DOMAIN ),
			'all' => __( 'All Images', Main::DOMAIN ),
		];
	}

	public function get_view_type() {
		$type = ! empty( $_GET['type'] )
			? sanitize_key( $_GET['type'] )
			: false;
		$types = array_keys( $this->get_types() );
		return in_array( $type, $types, true )
			? $type
			: reset( $types );
	}

	public function extra_tablenav( $which ) {
		if ( 'top' !== $which ) {
			return true;
		}
		$current_type = $this->get_view_type();
		$url = $this->get_page_url();
?>
<select name="media-filter">
<?php foreach( $this->get_types() as $type => $type_title ) { ?>
	<option value="<?php echo esc_url( add_query_arg( 'type', $type, $url ) ); ?>"
		<?php selected( $type, $current_type ); ?>
	><?php echo esc_html( $type_title ); ?></option>
<?php } ?>
</select>
<?php
	}

	public function get_view_type_query() {
		$type = $this->get_view_type();
		$queries = [
			'noalt' => [
				'relation' => 'AND',
				[
					'relation' => 'OR',
					[
						[
							'key' => '_wp_attachment_image_alt',
							'value' => '',
							'compare' => '='
						],
						[
							'key' => '_wp_attachment_image_alt',
							'compare' => 'NOT EXISTS'
						],
					],
					[
					'relation' => 'OR',
						[
							'key' => Attachment::get_decorative_meta(),
							'value' => '',
							'compare' => '='
						],
						[
							'key' => Attachment::get_decorative_meta(),
							'compare' => 'NOT EXISTS'
						],
					]
				]
			],
			'withalt' => [
				[
					'key' => '_wp_attachment_image_alt',
					'value' => '',
					'compare' => '!='
				],
			],
			'decorative' => [
				[
					'key' => Attachment::get_decorative_meta(),
					'value' => '',
					'compare' => '!='
				],
			],
			'stopwords' => [
				[
					'key' => '_wp_attachment_image_alt',
					'value' => [
						'image', 'Image',
						'photo', 'Photo',
						'picture', 'Picture',
						'pic', 'Pic', 'img', 'Img',
					],
					'compare' => 'IN',
				],
			],
			'all' => [],
		];
		return $queries[ $type ];
	}

	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = [];
		$sortable              = [];
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$media_query = $this->get_view_type_query();
/*
		$q = new \WP_Query([
			'post_type' => 'attachment',
			'paged'          => $this->get_pagenum(),
			'posts_per_page' => $this->get_pagination_arg( 'per_page' ),
			'post_mime_type' => [
				'image/jpeg', 'image/png', 'image/gif',
				'image/bpm', 'image/tiff',
			],
			'meta_query' => $media_query,
		]);
		xd($q);
*/

		$this->items                 = get_posts( [
			'post_type' => 'attachment',
			'paged'          => $this->get_pagenum(),
			'posts_per_page' => $this->get_pagination_arg( 'per_page' ),
			'post_mime_type' => [
				'image/jpeg', 'image/png', 'image/gif',
				'image/bpm', 'image/tiff',
			],
			'meta_query' => $media_query,
		] );
	}

	public function column_image( $item ) {
		return wp_get_attachment_image( $item->ID ) .
			'<br />' .
			$this->column_uploaded( $item );
	}

	public function column_post( $item ) {
		$post_id = $item->post_parent;
		if ( empty( $post_id ) ) {
			return '';
		}
		return '<a href="' . esc_url( get_permalink( $post_id ) ) . '" ' .
			'target="_blank">' . esc_html( get_the_title( $post_id ) ) . '</a>';
	}

	public function column_uploaded( $item ) {
		$time = get_post_timestamp( $item );
		$timestamp = date( 'Y-m-d\TH:i:s', $time );
		$time_diff = time() - $time;

		$string = $time && $time_diff > 0 && $time_diff < DAY_IN_SECONDS
			? sprintf( __( '%s ago' ), human_time_diff( $time ) )
			: date_i18n( __( 'Y/m/d' ), $time );

		$timebit = '<time ' .
			'datetime="' . esc_attr( $timestamp ) . '"' .
			'title="' . esc_html( date_i18n( __( 'Y/m/d g:i:s a' ), $time ) ) . '"' .
		'>' . esc_html( $string ) . '</time>';

		$user = new \WP_User( $item->post_author );
		$userbit = $user->display_name;

		return sprintf(
			__( '%s, by %s', Main::DOMAIN ),
			$timebit,
			$userbit
		);
	}

	public function column_alt( $item ) {
		return '' .
			$this->image_decorative_field( $item ) .
			$this->image_alt_field( $item ) .
		'';
	}

	public function image_alt_field( $item ) {
		$attachment = new Attachment( $item->ID );
		$alt = $attachment->get_alt();
		return '<label class="campus-a11y-input">' .
			'<input type="text" value="' . esc_attr( $alt ) . '" ' .
				'name="image-alt[]" data-image-id="' . (int) $item->ID . '" />' .
			'<div class="campus-a11y-alt-length">' .  sprintf(
				__( '<span>%d</span> character(s)', Main::DOMAIN ),
				(int) strlen( $alt )
			) .  '</div>' .
		'</label>';
	}

	public function image_decorative_field( $item ) {
		$attachment = new Attachment( $item->ID );
		return '<label class="campus-a11y-toggle">' .
			'<input type="checkbox" value="' . esc_attr( $item->ID ) . '" ' .
			checked( $attachment->is_decorative(), true, false ) . ' /> ' .
			'<span>' . __( 'This is a decorative image', Main::DOMAIN ) . '</span>' .
		'</label>';
	}

}
