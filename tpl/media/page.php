<?php
/**
 * Packages page parent template
 *
 * @package campus-a11y
 */

namespace Campus\A11y;

$status = [];
?>
<div class="wrap campus-a11y-admin-media">
	<h2>
		<?php esc_html_e( 'Media', Main::DOMAIN ); ?>
	</h2>

<?php foreach( $status as $s ) { ?>
	<div class="error">
		<p><?php echo esc_html( $s ); ?></p>
	</div>
<?php } ?>

	<?php $this->render( 'media/list' ); ?>
</div>
