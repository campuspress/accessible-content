<?php
/**
 * Wraps defined plugin-specific constants, for tests and overriding
 *
 * @package campus-a11y
 */

namespace Campus\A11y\Model\Constants;
use Campus\A11y\Model\Constants;

/**
 * Plugin constants class
 */
class Plugin extends Constants {

	/**
	 * Implementation constants namespace
	 *
	 * @var string
	 */
	protected $_namespace = 'Campus\A11y';

	/**
	 * Implementation constants prefix
	 *
	 * @var string
	 */
	protected $_prefix = 'CAMPUS_A11Y';

}
