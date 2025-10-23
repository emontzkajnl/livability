<?php
// session_start();
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
            <option value="expire-asc">Expiration date (ascending)</option> 
            <option value="expire-desc">Expiration date (descending)</option>
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
        if ( false === ( $sponsor_array = get_transient( 'sponsor_array' ))) {
        $sponsor_args = array(
            'post_type'			=> 'post',
            'meta_key'			=> 'sponsored',
            'meta_value'		=> true,
            'posts_per_page'	=> -1,
            'post_status'		=> array('publish', 'draft')
        );
        $sponsor_query = new WP_Query($sponsor_args); 

        // $sponsor_posts = get_posts($sponsor_args);
        $sponsor_array = array();
        if ($sponsor_query->have_posts()):
            while ($sponsor_query->have_posts()):
                $sponsor_query->the_post();
     
            $ID = get_the_ID();
            $places = get_post_meta( $ID, 'place_relationship', true );
            $temp_array = array();
            $temp_array['title'] = get_the_title();
            $temp_array['thumb_url'] = get_the_post_thumbnail_url( $ID, 'rel_article');
            $temp_array['thumb'] = get_the_post_thumbnail( $ID, 'rel_article');
            $temp_array['permalink'] = get_the_permalink( );
            $temp_array['place'] = $places ? $places[0] : '';
            $temp_array['place_name'] = $places ? get_the_title($places[0]) : '';
            $temp_array['status'] = get_post_status();
            $temp_array['sponsor_name'] = get_post_meta( $ID, 'sponsor_name', true );
            $temp_array['sponsor_url'] = get_post_meta( $ID, 'sponsor_url', true );
            $temp_array['post_time'] = get_post_time('U', true, $ID);
            $temp_array['expire_time'] = do_shortcode( '[futureaction type=date dateformat="U"]');
            $sponsor_array[] = $temp_array;
            endwhile;
        endif;
            wp_reset_postdata(  );
        // }

        // $_SESSION['sponsored_posts'] = $sponsor_array;
        set_transient( 'sponsor_array', $sponsor_array, 60*60*12 );
        }
            // delete_transient( 'sponsor_array' );

            // echo 'transient test';
            // print_r(get_transient( 'sponsor_array' ));
         ?>

      
    
        <?php if ($sponsor_array): 
            // $test_array = array_filter($sponsor_array, function($s){
            //     return $s['status'] != 'publish';
            // });
            ?>
            
            <div class="container">
                <div id="sponsor-tab-one" >
                
                <div class="sponsor-grid sponsor-grid-container" style="display: flex; justify-content: center; flex-wrap: wrap;">
            <?php foreach ($sponsor_array as $sg) { ?>
                <div class="sponsor-grid__card" >
                <a href="<?php echo $sg['permalink']; ?>">
                    <div class="sp-img sponsor-grid__img" style="background-image: url(<?php echo $sg['thumb_url']; ?>); height: 200px; width: 100%;"></div>
                    <div class="sma-title sponsor-grid__text-container">
                        <?php echo '<h4 class="sponsor-grid__title"><a href="'.$sg['permalink'].'">'.$sg['title'].'</a></h4>';
                        echo $sg['place_name'] ? '<p>'.$sg['place_name'].'</p>' : '';

                        echo '<p>Status: '.$sg['status'].'</p>';
                        echo '<p>Published '.date('M j, Y', $sg['post_time']).'</p>';
                        if ($sg['expire_time']) {
                            echo '<p>Expires '.date('M j, Y', $sg['expire_time']).'</p>';
                        } 
                        if ($sg['sponsor_name']) {
                            echo '<p>Sponsor: <a href="'.$sg['sponsor_url'].'" target="_blank">'.$sg['sponsor_name'].'</a></p>';
                        }
            ?>
                    </div>
                </a>
            </div>
                
            <?php }
            
            // get_template_part( 'template-parts/content/content-sponsored-grid' ); 
       
            //endwhile; 
            //wp_reset_query(); ?>
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
                        
                    
                <?php foreach ($sponsor_array as $sl) { ?>
                    <tr>
                    <td style="max-width: 100px;"><?php echo $sl['thumb']; ?></td>
                    <td><?php echo $sl['place_name'] ? $sl['place_name'] : ''; ?></td>
                    <td style="max-width: 300px;"><a class="unstyle-link" href="<?php echo $sl['permalink']; ?>"><?php echo $sl['title']; ?></a></td>
                    <td><?php echo $sl['status']; ?></td>
                    <td><?php echo $sl['sponsor_name'] ? '<a class="unstyle-link" href="'.$sl['sponsor_url'].'">'.$sl['sponsor_name'].'</a>' : 'no sponsor name'; ?></td>
                    <td><?php echo date('M j, Y', $sl['post_time']); ?></td>
                    <td><?php //echo date('M j, Y', $sl['expire_time']); ?></td>
                </tr>
                <?php } ?>
                <?php //get_template_part( 'template-parts/content/content-sponsored-list' ); ?>
                <?php // endwhile; ?>
                </tbody>
                </table>
                </div> <!-- tab two -->
                </div>
            </div>
        <?php endif;
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
