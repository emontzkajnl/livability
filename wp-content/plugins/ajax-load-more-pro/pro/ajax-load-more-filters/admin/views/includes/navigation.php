<?php
/**
 * The template for displaying the filters navigation.
 *
 * @package ALMFilters
 */

$nav_items = [
	[
		'url'    => ALM_FILTERS_BASE_URL,
		'icon'   => 'fa-dashboard',
		'text'   => __( 'Dashboard', 'ajax-load-more-filters' ),
		'active' => $section === 'dashboard',
	],
	[
		'url'    => ALM_FILTERS_BASE_URL . '&action=new',
		'icon'   => 'fa-pencil',
		'text'   => __( 'Add New', 'ajax-load-more-filters' ),
		'active' => $section === 'new',
	],
	[
		'url'    => ALM_FILTERS_BASE_URL . '&action=tools',
		'icon'   => 'fa-wrench',
		'text'   => __( 'Tools', 'ajax-load-more-filters' ),
		'active' => $section === 'tools',
	],
];
?>
<ul class="alm-toggle-switch">
	<?php
	foreach ( $nav_items as $item ) {
		$active_class = $item['active'] ? ' class="active"' : '';
		?>
		<li>
			<a href="<?php echo esc_url( $item['url'] ); ?>"<?php echo $active_class; ?>>
				<i class="fa <?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i> <?php echo esc_html( $item['text'] ); ?>
			</a>
		</li>
		<?php
	}
	?>
</ul>
