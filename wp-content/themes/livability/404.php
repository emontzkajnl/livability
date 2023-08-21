<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
?>
<div id="content">
<div id="primary">
<div id="main">
<div id="article-wrapper">
<div class="entry-content">

	<header class="page-header alignwide">
		<h1 class="h2"><?php esc_html_e( 'Page Not Found', 'twentytwentyone' ); ?></h1>
	</header><!-- .page-header -->

	<div class="entry-content container  not-found default-max-width">
		<div class="page-content">
			<p><?php esc_html_e( 'Sorry, we cannot seem to find the page you were looking for. You can try searching here:', 'twentytwentyone' ); ?></p>
			<?php get_search_form(); ?>
			<div style="margin-top:50px;">
			<?php
				$url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
				// if (strpos($url,'sponsored-content') !== false) {
				$link_array = explode('/', $url);
				$entry = end($link_array);
				// $entry = str_replace(array(' ','-','_','&'),'+',$entry);
				$args = array(
					'posts_per_page'		=> 30, 
					'post_status'			=> 'publish',
					's'						=> $entry,
				);
				$search_query = new WP_Query($args);
				if ($search_query->have_posts()):
					echo '<p>Search results for '.str_replace('+',' ',$entry).':</p><ul class="onehundred-container">';
					while ($search_query->have_posts()): $search_query->the_post();
					$type = get_post_type();
					$heading = '';
					switch ($type) {
						case 'liv_place':
							$heading = get_field('place_type').' page';
							break;
						case 'post':
							$cat = get_the_category();
							$heading = $cat[0]->name;
							break;  
						case 'liv_magazine':
							$heading = 'Magazine';
							break;     
						case 'best_places':
							$parent = get_post_parent();
							if ($parent) {
								$heading = get_the_title($parent->ID);
							} else {
								$heading = 'Best Places';
							}
							break;   
						default:
							break;
					} ?>
					<li class="one-hundred-list-item">
						<div class="one-hundred-list-container">
						<a href="<?php echo get_the_permalink(); ?>" class="ohl-thumb"><?php echo get_the_post_thumbnail(get_the_ID(), 'three_hundred_wide'); ?></a>
						<div class="ohl-text">
						
						<a href="<?php  echo get_the_permalink( ); ?>">
						<h5 class="green-text uppercase"><?php echo $heading; ?></h5>
						<?php _e(the_title('<h3>','</h3>'), 'livability'); 
						echo '<p style="font-size: 1.25rem;">'.get_the_excerpt().'</p></a>';
						?>
						
						</div>
						</div>
					</li>
					<?php endwhile;
					echo '</ul>';
				else: 
					echo '<p>No Search Results Found For '.str_replace('+',' ',$entry).'</p>';
				endif;
				wp_reset_postdata();
				
				// echo do_shortcode('[ajax_load_more loading_style="infinite classic" repeater="template_4" post_type="any" posts_per_page="20" search="'.$entry.'" scroll_distance="-200"]');
			?>
			</div>
		</div><!-- .page-content -->
	</div><!-- .error-404 -->

</div>
</div>
</div>
</div>
</div>

<?php
get_footer();
