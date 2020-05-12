<?php
/**
 * List packages subpage template
 *
 * @package campus-a11y
 */

namespace Campus\A11y;

//use Campus\A11y\Model\Nonce;
use Campus\A11y\View;
?>
<?php
	$tbl = new View\Table;
	$tbl->prepare_items();
	$tbl->display();
