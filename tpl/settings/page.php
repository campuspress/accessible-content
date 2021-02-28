<?php
/**
 * Packages page parent template
 *
 * @package campus-a11y
 */

namespace Campus\A11y;

use Campus\A11y\Model\Options;
use Campus\A11y\Model\Nonce;

$status = ! empty( $status ) && is_array( $status )
	? $status
	: [];
$opts = Options::get();
?>
<div class="wrap campus-a11y-admin-settings">
	<h2><?php echo esc_html_e( 'Accessibility Settings', Main::DOMAIN ); ?></h2>

<?php foreach( $status as $s ) { ?>
	<div class="error">
		<p><?php echo esc_html( $s ); ?></p>
	</div>
<?php } ?>

	<form method="POST" action="">
		<input type="hidden"
			name="_wpnonce"
			value="<?php echo esc_attr( Nonce::create( Nonce::ACTION_SETTINGS ) ); ?>"
		/>

		<div class="<?php $this->attr( 'options' ); ?>">

			<label>
				<input type="checkbox"
					name="<?php $this->attr( Options::KEY_ENABLE_REPLACEMENT ); ?>"
					value="1"
					<?php checked( true, $opts->get_option( Options::KEY_ENABLE_REPLACEMENT ) ); ?>
				/>
				<span><?php esc_html_e( 'Allow frontend replacement?', Main::DOMAIN ); ?></span>
				<div class="<?php $this->attr( 'description' ); ?>">
					<?php esc_html_e( 'Enabling this will add fallback alt tags for all the images that have them.', Main::DOMAIN ); ?>
					<br />
					<small><?php esc_html_e( 'Process the images inserted into content before the plugin was active, and/or prior to making a change to an image in Media > Alt text page and retroactively apply Alt tags to already existing post markup', Main::DOMAIN ); ?></small>
				</div>
			</label>


		<button class="button button-primary">
			<?php esc_html_e( 'Save', Main::DOMAIN ); ?>
		</button>
	</div>
	</form>
</div>
