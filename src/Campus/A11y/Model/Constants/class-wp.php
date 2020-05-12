<?php
/**
 * Wraps defined wp-specific constants, for tests and overriding
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model\Constants;
use Campus\A11y\Model\Constants;

/**
 * Plugin constants class
 */
class Wp extends Constants {

	/**
	 * Implementation constants namespace
	 *
	 * @var string
	 */
	protected $_namespace = '';

	/**
	 * Implementation constants prefix
	 *
	 * @var string
	 */
	protected $_prefix = 'WP';

}
