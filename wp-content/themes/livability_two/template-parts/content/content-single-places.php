<?php
/**
 * Template part for displaying places
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
		get_template_part( 'template-parts/blocks/city-map' ); 
		if (get_field('place_type') == 'state') {
			$topic_args = array(
				'posts_per_page'    => -1,
				'post_status'       => 'publish',
				'post_type'         => 'post',
				'meta_query'        => array(
					array( 
						'key'       => 'place_relationship',
						'value'     => '"' . get_the_ID() . '"',
						'compare'   => 'LIKE'
					),
					array(
						'key'       => 'sponsored',
						'value'     => 0
					),
					'relation'      => 'AND'
				)
			   );
			   $topics = new WP_Query( $topic_args );
			   $topics_array = array();
			   foreach($topics->posts as $topic) {
					$ID = $topic->ID;
					
				   $cat = get_the_category( $ID );
				   $slug = $cat[0]->slug;
				   
					if (! array_key_exists($slug, $topics_array)) {
					$topics_array[$slug] = array($ID); 
				   } else {
					   array_push($topics_array[$slug], $ID );
				   }
				
			   }
			   $state_name = get_the_title();

			   if (array_key_exists('experiences-adventures', $topics_array)) {
                echo '<h2 id="experiences-adventures">Experiences & Adventures in '.$state_name.'</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['experiences-adventures'], 'cat' => 'experiences-adventures'));
            }

            if (array_key_exists('food-scenes', $topics_array)) {
                echo '<h2 id="food-scenes">Food Scenes in '.$state_name.'</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['food-scenes'], 'cat' => 'food-scenes'));
            }  
            
            if (array_key_exists('healthy-places', $topics_array)) {
                echo '<h2 id="healthy-places">Healthy Places in '.$state_name.'</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['healthy-places'], 'cat' => 'healthy-places' ));
            }   
            
            if (array_key_exists('make-your-move', $topics_array)) {
                echo '<h2 id="make-your-move">Make Your Move to '.$state_name.'</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['make-your-move'], 'cat' => 'make-your-move'));
            }    
            
            if (array_key_exists('where-to-live-now', $topics_array)) {
                echo '<h2 id="where-to-live-now">'.$state_name.': Where to Live Now</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['where-to-live-now'], 'cat' => 'where-to-live-now'));
            }               

            if (array_key_exists('education-careers-opportunity', $topics_array)) {
                echo '<h2 id="education-careers-and-opportunity">Education, Careers and Opportunity in '.$state_name.'</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['education-careers-opportunity'], 'cat' => 'education-careers-opportunity'));
            }

            if (array_key_exists('love-where-you-live', $topics_array)) {
                echo '<h2 id="love-where-you-live">'.$state_name.': Love Where You Live</h2>';
                
                get_template_part( 'template-parts/blocks/place-topics-2', null, array('posts' => $topics_array['love-where-you-live'], 'cat' => 'love-where-you-live'));
            }    
		}

		?>
		<!-- add map here if not state -->

	</div><!-- .entry-content -->

	<footer class="entry-footer default-max-width">
		<?php //twenty_twenty_one_entry_meta_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php //get_template_part( 'template-parts/post/author-bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
