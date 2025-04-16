<?php
/**
 * CTA for determining a writable directory.
 *
 * @package ajax-load-more-cache
 */

// Test server for write capabilities.
$alm_cache_path = ALMCache::alm_get_cache_path();
if ( is_writable( $alm_cache_path ) ) {
	$writeable     = true;
	$status_text   = __( 'Read/write access is enabled within the Ajax Load More cache directory.', 'ajax-load-more-cache' );
	$status_text_2 = '';
	$status_icon   = function_exists( 'alm_status_icon' ) ? alm_status_icon( 'success', '', $status_text ) : '';
} else {
	$writeable     = false;
	$status_text   = __( 'You must enable read and write access for the Ajax Load More cache directory to save cache data.', 'ajax-load-more-cache' );
	$status_text_2 = __( 'Please contact your hosting provider or site administrator for more information.', 'ajax-load-more-cache' );
	$status_icon   = function_exists( 'alm_status_icon' ) ? alm_status_icon( 'success', '', $status_text ) : '';
	$status_icon   = function_exists( 'alm_status_icon' ) ? alm_status_icon( 'failed', '', $status_text ) : '';
}
?>
<div class="cta">
	<header class="cta--header">
		<h3><?php esc_attr_e( 'Cache Status', 'ajax-load-more' ); ?></h3>
		<?php echo wp_kses_post( $status_icon ); ?>
	</header>
	<div class="cta-inner">
		<?php
		if ( $writeable ) {
			echo '<p>' . __( 'The Ajax Load More cache contains the following totals:', 'ajax-load-more-cache' ) . '</p>';
		} else {
			echo '<p>' . wp_kses_post( $status_text ) . '</p>';
			if ( $status_text_2 ) {
				echo '<p>' . wp_kses_post( $status_text_2 ) . '</p>';
			}
		}
		?>
		<?php
		// Count cache files and directories.
		$dircount    = 0;
		$filecount   = 0;
		$directories = [];

		if ( ! is_dir( $path ) ) {
			wp_mkdir_p( $path ); // Create directory if it does not exist.
		}
		foreach ( new DirectoryIterator( $path ) as $file ) {
			if ( $file->isDot() ) {
				continue;
			}
			if ( $file->isDir() ) {
				$directories[] = $file->getFilename();
			}
		}
		foreach ( $directories as $directory ) {
			$val = count( glob( $path . $directory . '/*.json' ) );
			++$dircount;
			$filecount = $filecount + $val;

			// Sub Directories.
			$sub_dir  = [];
			$sub_path = $path . $directory;
			foreach ( new DirectoryIterator( $sub_path ) as $file ) {
				if ( $file->isDot() ) {
					continue;
				}
				if ( $file->isDir() ) {
					$sub_dir[] = $file->getFilename();
				}
			}
			if ( $sub_dir ) {
				foreach ( $sub_dir as $subdirectory ) {
					$val = count( glob( $path . $directory . '/' . $subdirectory . '/*.html' ) );
					++$dircount;
					$filecount = $filecount + $val;
				}
			}
		}
		if ( $writeable ) {
			?>
		<div class="alm-stats">
			<?php
			/* translators: %s is the number of directories */
			$title = sprintf( wp_kses_post( __( '%1$s page(s) found in the cache.', 'ajax-load-more' ) ), $dircount );
			?>
			<div title="<?php echo esc_attr( $title ); ?>">
				<div class="alm-stats--stat" id="dircount">
					<?php echo $dircount; ?>
				</div>
				<div>
					<?php echo ( $dircount > 1 || $dircount == 0 ) ? __( 'Pages', 'ajax-load-more-cache' ) : __( 'Page', 'ajax-load-more-cache' ); ?>
				</div>
			</div>
			<?php
			/* translators: %s is the number of files created */
			$title = sprintf( wp_kses_post( __( '%1$s file(s) found in the cache.', 'ajax-load-more' ) ), $filecount );
			?>
			<div>
				<div class="alm-stats--stat" id="filecount">
					<?php echo $filecount; ?>
				</div>
				<div>
					<?php echo ( $filecount > 1 || $filecount == 0 ) ? __( 'Files', 'ajax-load-more-cache' ) : __( 'File', 'ajax-load-more-cache' ); ?>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if ( ! $writeable ) { ?>
		<div class="alm-file-location">
			<input type="text" value="<?php echo esc_html( $alm_cache_path ); ?>" class="alm-file-location" readonly="readonly">
		</div>
		<?php } ?>
	</div>
	<div class="major-publishing-actions">
		<form id="delete-all-cache" name="delete-all-cache" action="admin.php" method="GET" data-path="<?php echo ALMCache::alm_get_cache_path(); ?>">
			<input type="hidden" value="ajax-load-more-cache" name="page">
			<button type="submit" class="button-primary" name="action" value="delete" <?php echo $writeable ? '' : 'disabled'; ?>>
				<?php esc_attr_e( 'Delete Cache', 'ajax-load-more-cache' ); ?>
			</button>
		</form>
	</div>
</div>
