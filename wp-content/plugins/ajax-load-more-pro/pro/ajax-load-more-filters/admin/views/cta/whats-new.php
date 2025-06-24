<?php
/**
 * CTA to display what's new text.
 *
 * @package ALMFilters
 */

?>
<div class="cta">
	<h3><?php esc_html_e( 'What\'s New', 'ajax-load-more-filters' ); ?></h3>
	<div class="cta-inner">
		<ul>
			<li>Filters <span class="alm-pre">3.0</span> introduces background processing functionality for sites with a large number of posts and content. Background processing must be enabled via the Ajax Load More &rarr; <a href="admin.php?page=ajax-load-more#filters_settings">Filters Settings</a>.
			</li>
		</ul>
	</div>
	<div class="major-publishing-actions">
		<a href="https://connekthq.com/plugins/ajax-load-more/add-ons/filters/#examples" class="button" target="_blank">
			<?php esc_html_e( 'View Examples', 'ajax-load-more-filters' ); ?>
		</a>
	</div>
</div>
