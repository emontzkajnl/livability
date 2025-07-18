<?php
$options        = Advanced_Ads_Pro::get_instance()->get_options();
$module_enabled = isset( $options['lazy-load']['enabled'] ) && $options['lazy-load']['enabled'];
$offset         = ! empty( $options['lazy-load']['offset'] ) ? Advanced_Ads_Pro_Utils::absint( $options['lazy-load']['offset'], 0, 99999 ) : 0;
?>
<input name="<?php echo esc_attr( Advanced_Ads_Pro::OPTION_KEY ); ?>[lazy-load][enabled]" id="advanced-ads-pro-lazy-load-enabled" type="checkbox" value="1" <?php checked( $module_enabled ); ?> class="advads-has-sub-settings" />
<label for="advanced-ads-pro-lazy-load-enabled" class="description">
	<?php esc_html_e( 'Activate module.', 'advanced-ads-pro' ); ?>
</label>
<a href="<?php echo 'https://wpadvancedads.com/lazy-load-ads/?utm_source=advanced-ads&utm_medium=link&utm_campaign=pro-ll-manual'; ?>" target="_blank" class="advads-manual-link"><?php esc_html_e( 'Manual', 'advanced-ads-pro' ); ?></a>
<div class="advads-sub-settings">
	<p class="description">
		<?php
		$dashicon_class = 'dashicons-no advads-color-red';
		if ( ! empty( $options['cache-busting']['enabled'] ) ) {
			$dashicon_class = 'dashicons-yes advads-color-green';
		}

		echo wp_kses_post(
			sprintf(
				/* translators: %s: dashicon class */
				__( 'This module requires: <br> <span class="dashicons %s"></span> Cache Busting', 'advanced-ads-pro' ),
				$dashicon_class
			)
		);
		?>
	</p>
	<br />
	<label>
		<?php
		$field = '<input name="' . Advanced_Ads_Pro::OPTION_KEY . '[lazy-load][offset]" type="number" min="0" max="99999" value="' . $offset . '" />';
		printf(
			/* translators: %s: input field */
			__( 'Start loading the ads %s pixels before they are visible on the screen.', 'advanced-ads-pro' ), // phpcs:ignore
			$field // phpcs:ignore
		);
		?>
	</label>
</div>
