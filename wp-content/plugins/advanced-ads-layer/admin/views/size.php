<?php // phpcs:ignoreFile ?>
<p>
	<label><?php esc_html_e( 'width', 'advanced-ads-layer' ); ?>
	<input type="number" value="<?php echo $width; ?>" name="advads[placements][options][placement_width]">px</label>&nbsp;

	<label><?php esc_html_e( 'height', 'advanced-ads-layer' ); ?>
	<input type="number" value="<?php echo $height; ?>" name="advads[placements][options][placement_height]">px</label>
</p>
<p class="description"><?php esc_html_e( 'Needed sometimes to center the ad correctly', 'advanced-ads-layer' ); ?></p>
