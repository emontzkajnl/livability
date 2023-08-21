<?php
// include_once( get_stylesheet_directory() .'/assets/lib/bp_2023_cache_obj.php');
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

 $places = get_field('place_relationship');
 foreach($places as $place) {
	 if (get_field('place_type', $place) == 'city') {
		 $city_title = get_the_title($place);
	 }
 }
 $city_title = $city_title ? $city_title : '';
 $parent_id = wp_get_post_parent_id();
 $byline =  get_field('img_byline',get_post_thumbnail_id());
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header alignwide">
        <?php //get_template_part( 'template-parts/hero-section' ); ?>
		<?php //the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php //twenty_twenty_one_post_thumbnail(); ?>
	</header>



	<div class="entry-content ">
        <div class="wp-block-columns">
			
            <div class="wp-column alignfull" style="width: 100%;">
                <div class="bp23-heading-section">
                    <div class="bp23-thumb" style="background-image: url('<?php //echo get_the_post_thumbnail_url($cityId, 'rel_article'  ) ?>');">
 					<?php echo get_the_post_thumbnail( get_the_ID(), 'medium-large' );  
					  echo $byline ? '<div class="livability-image-meta">'.$byline.'</div>' : ''; ?>
                    </div>
					<div class="bp23-title-section">
						<!-- logo here -->
						<img class="bp23-badge-single" src="<?php echo get_stylesheet_directory_uri(  ); ?>/assets/images/2023-Livability-Top-100-best-places-badge.svg"/>
						<?php echo '<h1 class="bp23-title">'.$city_title.'<span><a href="'.get_the_permalink( $parent_id ).'">'.get_the_title($parent_id).'</a></span></h1>'; ?>
						
						<?php echo has_excerpt( ) ? '<p class="bp23-excerpt">'.get_the_excerpt().'</p>' : ''; 
						// echo 'parent: '.wp_get_post_parent_id(  ).' '.get_the_title(150065 ); ?>
					</div>
                </div>
            </div>
        </div> <!--wp-block-columns-->
		<div class="wp-block-columns liv-columns-3">
			<div class="wp-block-column">
				<div class="a2a_kit a2a_kit_size_32 a2a_default_style">
					<a class="a2a_button_copy_link"></a>
					<a class="a2a_button_linkedin"></a>
					<a class="a2a_button_facebook"></a>
					<a class="a2a_button_twitter"></a>
					<a class="a2a_dd" href="https://www.addtoany.com/share"></a>
				</div>
				<script>
					a2a_config.linkurl = '<?php echo get_the_permalink(); ?>';
						a2a.init('page');
				</script>
            <?php get_template_part( 'template-parts/blocks/more-like-this'); ?>
			</div>
			<div class="wp-block-column">
				<div id="crumbs">
					<?php if (function_exists('return_breadcrumbs')) {
						echo return_breadcrumbs(); 
					} ?>
					</div>
					<h1 class="h2"><?php //echo the_title(); ?></h1>
						<?php
							the_content();
							// print_r($_SESSION['cached_bp_posts']);
							get_template_part( 'template-parts/blocks/city-and-state-links', null, array( 'city-state' => $places) );
						?>
				</div>
		</div>
	    <?php
		// print_r($_SESSION['bp23_array']);
	// if (!isset($_SESSION['bp23_array'])): 
	
	$bp_args = array(
		'post_type'         => 'best_places',
		'post_status'       => 'publish',
		'tax_query'			=> array(
			array(
				'taxonomy'	=> 'best_places_years',
				'field'		=> 'slug',
				'terms'		=> '2023'
			)
		),
		'orderby'           => 'meta_value_num',
		'meta_key'          => $meta_key,
		'order'             => 'DESC',
		'posts_per_page'    => 100
	);
	$bp_posts = get_posts($bp_args);
	$bp23_array = array();
	foreach($bp_posts as $key => $bp){ 
		$arr = array();
		$arr['id'] = $bp->ID;
		$arr['rank'] = $key + 1;
		$city;
		$state;
		$places = get_field('place_relationship', $bp->ID);
		// if (count($places) > 2 || !$places) {
		// 	echo $city.' has no places!';
		// }
		if ($places):
			foreach($places as $p) {
				// echo $key.' p type is '.get_field('place_type', $p).'<br >';
				if (get_field('place_type', $p) == 'state') {
					// echo 'this is city type';
					$state = $p;
				} else {
					$city = $p;
				}
			}
			$cityPopulation = intval(str_replace(',','', get_field('city_population', $city))); 
			$cityHomeValue = str_replace(array(',','$'),'',get_field('city_home_value', $city)); 
			$arr['cityPopulation'] = $cityPopulation;
			$arr['cityHomeValue'] = $cityHomeValue;
			$arr['cityTitle'] = get_the_title($city);
			$arr['stateId'] = $state;
			$bp23_array[] = $arr;
		endif;
	}
	// $_SESSION['bp23_array'] = $bp23_array;
	// endif;
	// $newbp23_array = $_SESSION['bp23_array']; 

	$current_pos;
	$ID = get_the_ID();
	foreach ($bp23_array as $key=>$value) {
		if ($value['id'] == $ID) {
			$current_pos = $key;
		}
	}
	$row2 = array_splice($bp23_array, $current_pos);
	$row1 = array_splice($bp23_array, 0, $current_pos );
	$bp23_array = array_merge($row2, $row1);

	
	
	// print_r($bp23_array);?>

	<h4 class="aligncenter" style="margin-bottom: -70px; color: #111111;">Continue Reading Best Places To Live in the US in 2023</h4>
	<div class="list-carousel-container custom-block">
	<ul class="wp-block-jci_blocks-blocks list-carousel">
	<?php foreach($bp23_array as $key=>$value) {
		$thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url($value['id'], 'rel_article') : '';
		$title = $value['cityTitle'];
		$rank = $value['rank']; ?>
		<li class="lc-slide">
		<a href="<?php echo get_the_permalink($value['id']); ?>">
		<div class="lc-slide-inner">
			<div class="lc-slide-content">
				<div class="lc-slide-img" style="background-image: url(<?php echo $thumb_url; ?>);">
					<!-- <p class="slide-count"><?php //echo $rank; ?></p> -->
				</div>
				<div><h4 class="city-state"><?php echo $title; ?></h4></div>
			</div>
		</div>
		</a>
		</li>
	<?php } ?>
	</ul>
	<button class="list-carousel-button"><a href="<?php echo get_the_permalink( $parent_id); ?>">Go Back To List</a></button>
	</div>

	<?php // loop through query
	// add key (+1?) as meta number
	// if current id matches, set to current_pos
	// split array by current_pos

	?>
	</div><!-- .entry-content -->

	<footer class="entry-footer default-max-width">
		<?php twenty_twenty_one_entry_meta_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php //get_template_part( 'template-parts/post/author-bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
