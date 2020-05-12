<?php
/**
 * Renders alt image content fallbacks
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Controller;

use Campus\A11y\Controller;
use Campus\A11y\Model\Options;
use Campus\A11y\Model\Attachment;
use Campus\A11y\Main;

class Content extends Controller {

	/**
	 * Holds cached resolved URLs
	 *
	 * @var array
	 */
	private $_url_attachment = [];

	public function boot() {
		if ( ! Options::get()->get_option( Options::KEY_ENABLE_REPLACEMENT ) ) {
			// Exit early.
			return false;
		}

		add_filter(
			'the_content',
			[ $this, 'process_content_images' ],
			PHP_INT_MAX
		);
	}

	public function process_content_images( $content ) {
		preg_match_all( '/<img (.*?)\/>/', $content, $images );
		if ( ! is_null( $images ) ) {
			$i = 0;
			foreach ( $images[0] as $index => $value ) {
				// First up, let's check if it already has alt!
				$altpos  = strpos( $images[0][ $i ], 'alt=' );
				$nextc =  substr( ltrim( substr( $images[0][ $i ], $altpos + 3 ) ), 0, 1 );
				if ( '=' === $nextc ) {
					$pastalt = substr( $images[0][ $i ], $altpos + 5, 1 );
					if ( ! in_array( $pastalt, [ '"', "'" ], true ) ) {
						// We have alt - move on, let's not do a DB roundtrip.
						$i++;
						continue;
					}
				}

				// No alt - is it already set as decorative?
				if ( preg_match( '/role=[\'|"]decorative[\'"]/', $images[1][ $i ] ) ) {
					// Already set as decorative image, carry on.
					$i++;
					continue;
				}

				preg_match( '@src="([^"]+)"@', $images[1][ $i ], $match );
				$href = str_replace( 'src=', '', str_replace( '"', '', $match[1] ) );

				$decorative = $this->get_attachment_decorative( $href );
				if ( ! empty( $decorative ) ) {
					// Image should be marked decorative
					$content = str_replace(
						$images[0][ $index ],
						'<img ' . $images[1][ $i ] . ' ' . $decorative . '/>',
						$content
					);
					// Move on.
					$i++;
					continue;
				}

				$imid = $this->get_attachment_alt( $href );
				if ( empty( $imid ) ) {
					// We weren't able to resolve default alt, move on.
					$i++;
					continue;
				}

				if ( strpos( $images[0][ $i ], 'alt=""' ) !== false ) {
					// Has alt, but it's empty.
					$imalt   = str_replace(
						'alt=""',
						'alt="' . esc_attr( $imid ) . '"',
						$images[1][ $i ]
					);
					$content = str_replace(
						$images[0][ $index ],
						'<img ' . $imalt . ' />',
						$content
					);
				} elseif ( strpos( $images[0][ $i ], 'alt=' ) === false ) {
					// Has no alt attribute.
					$imalt   = str_replace(
						'src=',
						'alt="' . esc_attr( $imid ) . '" src=',
						$images[1][ $i ]
					);
					$content = str_replace(
						$images[0][ $index ],
						'<img ' . $imalt . ' />',
						$content
					);
				}

				$i++;
			}
		}
		return $content;
	}

	public function get_attachment_decorative( $href ) {
		$attachment = $this->get_attachment( $href );
		return ! empty( $attachment->is_decorative() )
			? 'role="presentation"'
			: '';
	}

	public function get_attachment_alt( $href ) {
		$attachment = $this->get_attachment( $href );
		return $attachment->get_alt();
	}

	public function get_attachment( $url ) {
		if ( isset( $this->_url_attachment[ $url ] ) ) {
			return $this->_url_attachment[ $url ];
		}
		$attachment = Attachment::from_url( $url );
		$this->_url_attachment[ $url ] = $attachment;

		return $attachment;
	}
}
