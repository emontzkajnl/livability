<?php
/**
 * Upgrade notification display.
 *
 * @package ajax-load-more-cache
 */

if ( ALM_VERSION && version_compare( ALM_VERSION, '7.7.0', '<' ) ) {
	?>
	<div class="ajax-load-more-inner-wrapper" style="padding-top: 0; padding-bottom: 0;">
		<div class="notice error instant-images-err-notice inline" style="margin-bottom: 0;">
			<p><?php echo wp_kses_post( __( 'Incompatible <strong>Ajax Load More</strong> version: Update to version 7.7.0 or greater to continue using the Cache add-on', 'ajax-load-more-cache' ) ); ?> &nbsp;<a class="button button-primary" href="plugins.php"><?php esc_attr_e( 'Update Plugin', 'ajax-load-more-cache' ); ?></a></p>
		</div>
	</div>
	<?php
}
