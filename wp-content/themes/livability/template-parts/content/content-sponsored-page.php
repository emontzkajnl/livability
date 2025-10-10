<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



	<div class="entry-content">

		<?php
		the_content();


        // Filters: publish, digital/print, place, ?>

<div class="sponsor-filter-container">
<div>
<label for="autocomplete">Filter by Location: </label>
<input id="autocomplete" placeholder="Enter city or state">
<input type="hidden" name="hidden-autocomplete" id="hidden-autocomplete" value="" >
</div>
 



         <!-- <fieldset id="post-status"> -->
            <div id="post-status">
        <legend>Post Status</legend>
        <div class="radio-container">
            <input type="radio" name="post-status" id="allStatus" value="all" checked />
            <label for="allStatus">All</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="post-status" id="publish" value="publish"  />
            <label for="publish">Published</label>
        </div>
        <div class="radio-container">
            <input type="radio" name="post-status" id="draft" value="draft"  />
            <label for="draft">Draft</label>
        </div>
        </div>
        <!-- </fieldset> -->

        <div class="sponsor__orderby">
            <label for="order-sponsors">Order by:</label> <!-- published, expiration, sponsor, place -->
            <select name="order" id="order-sponsors">
            <option value="publish-asc">Publish date (ascending)</option>
            <option value="publish-desc">Publish date (descending)</option>
            <!-- <option value="expire-asc">Expiration date (ascending)</option> -->
            <!-- <option value="expire-desc">Expiration date (descending)</option> -->
            <option value="sponsor-asc">Sponsor Name (ascending)</option>
            <option value="sponsor-desc">Sponsor Name (descending)</option>
            <option value="place-asc">Place (ascending)</option>
            <option value="place-desc">Place (descending)</option>
            </select>
        </div>

        <ul class="sponsor__tab-nav">
            <li class="sponsor__grid-tab" data-tab="sponsor-tab-one">Grid View</li>
            <li class="sponsor__list-tab" data-tab="sponsor-tab-two">List View</li>
        </ul>

        </div> <!-- sponsor filter container -->
        <?php 
        $sponsor_args = array(
            'post_type'			=> 'post',
            'meta_key'			=> 'sponsored',
            'meta_value'		=> true,
            'posts_per_page'	=> -1,
            'post_status'		=> array('publish', 'draft')
        );
        $sponsor_query = new WP_Query($sponsor_args); ?>

      
    
        <?php if ($sponsor_query->have_posts()): ?>
            
            <div class="container">
                <div id="sponsor-tab-one" >
                
                <div class="sponsor-grid sponsor-grid-container" style="display: flex; justify-content: center; flex-wrap: wrap;">
            <?php while ($sponsor_query->have_posts()): $sponsor_query->the_post();
            
            get_template_part( 'template-parts/content/content-sponsored-grid' ); 
       
            endwhile; 
            wp_reset_query(); ?>
                </div> <!--sponsor grid -->
                </div><!--tab one --> 


                <div id="sponsor-tab-two" style="display: none;">
                    <table class="sponsor-table">
                        <thead>
                        <tr>
                            <th>Image</th>
                            <th>Place</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Sponsor</th>
                            <th>Published</th>
                            <th>Expiration</th>
                            
                        </tr>
                        </thead>
                        <tbody class="sponsor-list-container">
                        
                    
                <?php while ($sponsor_query->have_posts()): $sponsor_query->the_post(); ?>
                <?php get_template_part( 'template-parts/content/content-sponsored-list' ); ?>
                <?php endwhile; ?>
                </tbody>
                </table>
                </div> <!-- tab two -->
                </div>
            </div>
        <?php endif;
        wp_reset_query();
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer default-max-width">
			<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post. Only visible to screen readers. */
					esc_html__( 'Edit %s', 'twentytwentyone' ),
					'<span class="screen-reader-text">' . get_the_title() . '</span>'
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
