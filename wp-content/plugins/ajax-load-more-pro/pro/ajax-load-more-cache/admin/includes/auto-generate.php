<?php
/**
 * Auto-generate file listing view.
 *
 * @package ajax-load-more-cache
 */

$cache_array = ALMCache::alm_get_cache_array();
if ( ! empty( $cache_array ) ) {
	?>
<div class="alm-generate-cache">
	<p class="alm-cache-status">
		<span id="alm-cache-processing-txt">
			<?php esc_attr_e( 'Building cache, this could take a little while.', 'ajax-load-more-cache' ); ?>
		</span>
		<span id="alm-cache-paused-txt">
			<?php esc_attr_e( 'Cache paused.', 'ajax-load-more-cache' ); ?>
		</span>
		<span id="alm-cache-complete-txt">
			<?php esc_attr_e( 'Cache generation complete.', 'ajax-load-more-cache' ); ?>
		</span>
	</p>
	<div class="iframe-target"></div>
	<script>
		var alm_cache_array = <?php echo wp_json_encode( $cache_array ); ?>;
	</script>
	<ul class="alm-generate-cache--list">
		<?php
		foreach ( $cache_array as $cache ) {
			echo '<li data-id="' . esc_attr( $cache['id'] ) . '" data-url="' . esc_url( $cache['url'] ) . '">' . esc_html( $cache['id'] ) . ' <small><a href="' . esc_url( $cache['url'] ) . '" target="_blank">' . esc_html( $cache['url'] ) . '</a></small></li>';
		}
		?>
	</ul>
	<div class="alm-generate-cache--controls">
		<div>
			<button class="button cache-pause">
				<?php esc_html_e( 'Pause', 'ajax-load-more-cache' ); ?>
			</button>
			<button style="display: none;" class="button cache-resume">
				<?php esc_html_e( 'Resume', 'ajax-load-more-cache' ); ?>
			</button>
			<button style="display: none;" class="button button-primary cache-rebuild">
				<?php esc_html_e( 'Rebuild Cache', 'ajax-load-more-cache' ); ?>
			</button>
		</div>
		<p id="alm-elapsed-time"><?php esc_html_e( 'Elapsed time:', 'ajax-load-more-cache' ); ?> <span></span></p>
	</div>
	<p><small><?php echo esc_html__( 'Note: Stay on this page until the build process is complete.', 'ajax-load-more-cache' ); ?></small></p>
</div>
<?php } else { ?>
<p><?php esc_html_e( 'No Ajax Load More Cache instances found.', 'ajax-load-more-cache' ); ?></p>
<?php } ?>
