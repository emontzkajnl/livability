<?php 
$bp23_array = array();
            foreach($bp_posts as $key => $bp){ 
                $arr = array();
                $arr['id'] = $bp->ID;
                $arr['rank'] = $key + 1;
                $city;
                $state;
                $places = get_field('place_relationship', $bp->ID);
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

            }
            $_SESSION['bp23_array'] = $bp23_array;
            $bp23_array = array_slice($bp23_array, 0, 20);
            $score_text = $meta_key == 'ls_livscore' ? '' : ' Score';
            foreach($bp23_array as $key => $bp){    

function load_more_bpm_posts() {
	if ($_SESSION['bp23_array']) {
		$bp23_array = $_SESSION['bp23_array'];
		$meta_key = $_SESSION['bp23_cat'];
		$page = $_POST['page'];
		$page_offset = $page * 20;
		// get next page of results
		$bp23_array = array_slice($bp23_array, $page_offset, 20);
		$score_text = $meta_key == 'ls_livscore' ? '' : ' Score';
		foreach($bp23_array as $key => $bp ) { ?>
			<div class="bp23-card">
			<?php echo '<a class="bp23-img" href="'.get_the_permalink( $bp['id'] ).'">'.get_the_post_thumbnail( $bp['id'], 'rel_article').'</a>'; ?>
			<div class="bp23-card-text">
			<h3  class="h4"><a href="<?php echo get_the_permalink( $bp['id'] ); ?>"><?php echo $bp['cityTitle']; ?></a></h3>
			<p class="meta-sort <?php echo strtolower(display_meta_key($meta_key)); ?>"><strong><?php echo ucfirst(display_meta_key($meta_key)).$score_text.': '.get_field($meta_key, $bp['id']); ?></strong></p>
			<p><?php echo 'Region: '.get_region($bp['stateId']); ?></p>
			<p>Population: <?php echo number_format($bp['cityPopulation']); ?></p>
			<p>Med. Home Value: $<?php echo number_format($bp['cityHomeValue']); ?></p>
		</div>
		<p class="read-more"><a href="<?php echo get_the_permalink( $bp['id'] ); ?>">Read More</a></p>
		</div>
		<?php }
		// ad goes here. 
		die();
		
	} else {
		error_log('bp23 posts not set');
	}
}

function load_bp_23(){
    $meta_key = $_POST['cat']; 
    setcookie('bp23_cat', $meta_key, time()+3600);
    $filters = $_POST['bp23filters'];
    $bp_args = array(
        'post_type'         => 'best_places',
        'post_status'       => 'any',
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
    // add filters to args here. 
    $bp_posts = get_posts($bp_args);
    $bp23_array = array();
    // print_r($bp_posts);
    foreach($bp_posts as $key => $bp) {
        $arr = array();
        $arr['id'] = $bp->ID;
        $places = get_field('place_relationship', $bp->ID);
        foreach($places as $p) {
            if (get_field('place_type', $p) == 'city') {
                $city = $p;
            } else {
                $state = $p;
            }
        }
        $cityPopulation = str_replace(',','', get_field('city_population', $city)); 
        $cityHomeValue = str_replace(',','',get_field('city_home_value', $city)); 

        $arr['cityPopulation'] = $cityPopulation;
        $arr['cityHomeValue'] = $cityHomeValue;
        $arr['cityTitle'] = get_the_title($city);
        $arr['stateId'] = $state;

        // filter by region
        if ($_POST['bp23filters']['region']) {
            if ( $_POST['bp23filters']['region'] != strtolower(get_region($state)) ) {
                // unset($bp_posts[$key]);
                $arr = null;
                // echo 'not a match <br>';

            }
        }
        // filter by population
        if ($_POST['bp23filters']['population']) {
            $pop_array = explode("-",$_POST['bp23filters']['population']);
            if ($cityPopulation < $pop_array[0] || $cityPopulation > $pop_array[1]) {
                $arr = null;
            }
        }

        // filter by home value
        if ($_POST['bp23filters']['home_value']) {
            $hv_array = explode("-", $_POST['bp23filters']['home_value']);
            if (intval($cityHomeValue) < intval($hv_array[0]) ||  intval($cityHomeValue) > intval($hv_array[1])) {
                $arr = null;
            }
        }
        if ($arr) {
            $bp23_array[] = $arr;
        }


    }
    
    // store posts in session variable 
    // $_SESSION['bp23_array'] = $bp23_array;
    // load the first 20 posts
    $bp23_array = array_slice($bp23_array, 0, 20);
    if (count($bp23_array) == 0 ) {
        echo '<p>No posts found. </p>';
    } else {
        $score_text = $meta_key == 'ls_livscore' ? '' : ' Score';
    foreach($bp23_array as $key => $bp){ ?>
        <div class="bp23-card">
            <?php echo '<a class="bp23-img" href="'.get_the_permalink( $bp['id'] ).'">'.get_the_post_thumbnail( $bp['id'], 'rel_article').'</a>'; ?>
            <div class="bp23-card-text">
            <h3  class="h4"><?php echo $bp['cityTitle']; ?></h3>
            <p class="meta-sort <?php echo strtolower(display_meta_key($meta_key)); ?>"><strong><?php echo ucfirst(display_meta_key($meta_key)).$score_text.': '.get_field($meta_key, $bp['id']); ?></strong></p>
            <p><?php echo 'Region: '.get_region($bp['stateId']); ?></p>
            <p>Population: <?php echo number_format($bp['cityPopulation']); ?></p>
            <p>Med. Home Value: $<?php echo number_format($bp['cityHomeValue']); ?></p>
        </div>
        <p class="read-more"><a href="<?php echo get_the_permalink( $bp['id'] ); ?>">Read More</a></p>
        </div>
    <?php }
    }
    // ad goes here. 
    die();
}
?>
<!-- Styles from customizer, 7/5/23 -->
<style>
:root {
	--global--font-primary: var(--font-headings, Jost, sans-serif);
	--global--font-secondary: var(--font-base, serif);
		--branding--logo--max-width: none;
	--branding--logo--max-height: 50px;
}

@media only screen and (min-width: 482px) {
	:root{
		--responsive--aligndefault-width: min(calc(100vw - 3 * var(--global--spacing-horizontal)), 1366px);
	}
	.fw-mag-img {
		padding-bottom:40px;
	}
	.fw-mag-img img {
		max-width:232px;
	}
}
@media only screen and (min-width: 822px) {
	:root{
		--responsive--aligndefault-width: min(calc(100vw - 6 * var(--global--spacing-horizontal)), 1366px);
	}
}
@media screen and (min-width: 782px) {
.wp-block-column:not(:first-child) {
    margin-left: 40px;
}
.page-id-102275 .wp-block-column li {
	margin-bottom:1em;
}
}
.entry-content p.schema-faq-answer {
    padding: 0.5rem 0 1.8rem;
    margin-top: 0;
}
.postid-119400 .quick-facts-block dl > div {
	padding:30px 10px;
}
.postid-119400 .bp-sponsor-container {
	padding-top:10px;
}
.two-mag-img img {
	max-width:250px;
	max-height:350px;
}
p.widget-link {
	display:none;
}
.site-content li {
	font-family: "Crimson Text", "Times New Roman", serif;
}
.body-mega-hero .search-form .search-submit {
	background-color:transparent !important;
}
.body-mega-hero .site-header {
	background: rgba(0,0,0,.3);
}
.cc-cta-container .cc-cta__left-section p {
	margin-top:.4em;
	padding-right:1em;
}
@media screen and (max-width: 480px) {
  .cc-cta-container {
	  flex-wrap:wrap;
  }
	.cc-cta__left-section {
		text-align:center;
		width:100%;
	}
	.cc-cta__right-section {
		text-align:center;
		width:100%;
		margin:.7em auto;
	}
}
.t-100-landing-23 {
	max-width: none !important;
  background-position: center -450px;
  background-size:  auto 1700px;
  height: 800px;
  padding-top: 0;
  margin-top: -50px !important;
}
.t-100-23-text-area {
  text-transform: uppercase;
  font-weight: 400;
  text-align: center;
  max-width: 940px;
  // background-color: pink;
  min-height: 300px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-right: auto;
  margin-left: auto;
}
.postid-159696 .hero-section,
.postid-159696 .newsletter {
	display: none;
}
.2023-top-100-column {
	max-width:970px;
}
.postid-159696 .entry-content #gform_wrapper_1 {
	max-width:415px;
	margin:-30px auto 0;
}
.postid-159696 #gform_wrapper_1 #gform_1 .gform_body {
	width:100% !important;
}
@media screen and (max-width: 480px) {
.best-places-drop {
	display:inline-block; 
	clear:both;
	}
.postid-159696 .entry-content #gform_wrapper_1 {
	margin:0px auto;
	}
.postid-159696 .fireworks {
		padding: 10px 0px 200px 0px !important;
    background-size: 200% !important;
	}
}
.postid-160892 .liv-columns>.wp-block-column:last-child,
.postid-160892 .wp-block-jci-blocks-ad-area-one,
.postid-160892 #crumbs,
.postid-160892 .livscore-table,
.postid-160892 h2.h2 {
	display:none;
}
.postid-160892 .liv-columns-2 #crumbs {
	display:block;
}
.postid-160892 .liv-columns-2 .wp-block-column:last-child {
	max-width:970px;
}
.postid-160892 .liv-columns-2 .wp-block-column:first-child  {
	flex-basis:360px;
}
.postid-160892 .liv-columns>.wp-block-column:first-child {
	max-width:1360px;
}
.postid-160892 .more-like-this-block {
	padding-left:20px;
}
.postid-160892 .bp-data-container,
.postid-160892 .bp-data-image {
	height:500px;
	width:100%;
}
/* 2023 Top 100 */
.newlist23 {
	background-color:#ffaa3b;
	color:#fff;
	font-family:'Jost';
}
.newlist23-text {
	padding:30px 30px 30px 60px; float:left; width:65%;
}
.newlist23-img {
	display:inline-block; width:30%;
}
.newlist23-text a {
	color:#fff;
}
@media (min-width: 421px) and (max-width: 820px) {
	.fireworks {
    min-height: 340px !important;
    padding: 40px 46px 0px 46px !important;
    background-size: 120% !important;
	}
	.background-position:{90% 100%;}
	.temp-h1 {font-size:36px  !important;}
	.temp-h3 {font-size:20px  !important;}
}
@media screen and (max-width: 480px) {
	.newlist23-text {width:100%;}
	.newlist23-img {width:100%;}
	.newlist23-img img {margin:0 auto;}
.bp23-thumb .livability-image-meta {
	top:220px;
	bottom:revert;
	}	
.livscore-block__cat-bkgrnd {
    width: 90px;
	}
	.weather-block>div {
		margin-bottom:10px;
	}
	.bp23qf__col1>div, .bp23qf__col2>div {
		flex-basis:100%;
	}
	.bp23qf__text {
		text-align:center;
	}
.temp-h1 {
		font-size: 26px !important;
    color: #fff;
    text-align: center;
    padding: 1rem 1rem 0 1rem !important;
}
.temp-h3 {
    font-size: 16px !important;
    color: #fff;
    text-align: center;
    padding: 1rem;
	margin-top:10px !important;
}
.temp-button {
		text-align:center;
	  width:320px;
	margin:0 auto;
	display:block !important;
	margin-top:10px !important;
	margin-bottom: 30px !important;
}
	.livscore-block__bigtext {
		bottom:70px !important;
  }
	.single-best_places .more-like-this-block li {
	margin-left:20px;
}
}
/* 2023 TOP 100 END MOBILE */
.23newlist-text a {
	color:#fff !important;
}
.postid-159696 .entry-header {
	display:none;
}
.livscore-block__top-cat p {
	  max-width: 375px;
    text-align: center;
    margin: 2rem auto;
    color: gray;
}

.livscore-block svg {
	max-width:380px !important;
}
.bp23-title-section {
    padding: 30px 52px;
}
.bp23-title-section .bp23-excerpt {
    font-weight:normal !important;
	}
h2 {
    font-size: 1.6rem;
}
.home-promo .post-1-title {
	display:none;
}
.home-promo .cp-container {background-image:none;}
.bp23qf__text {text-align:center;}
@media screen and (min-width: 822px) {
.liv-columns-3>.wp-block-column:first-child {
    flex-basis: 356px;
	}
}
@media (min-width: 1230px) and (max-width: 1461px) {
	.bp23qf__col1>div, .bp23qf__col2>div {
 		flex-basis:100%;
	}
}
.wp-block-jci-blocks-ad-area-three {
	overflow:hidden;
}

</style>

function create_listing_object() {
	$pagetype = $currentcat = $oldcategory = $place = $state = $placeid = $year = $articleid =  "";
	$slug = get_the_permalink();
	$global = "false";
	$ID = get_the_ID();
	$post_type = get_post_type();
	$cat = get_the_category();
	$all_cats = get_categories();
	$topic_page_array = array();
	
	foreach($all_cats as $c) {
		$p = get_field('category_page', 'category_'.$c->term_id);
		if ($p) {
			$topic_page_array[] = $p->ID;

		}
	}
	$t = '';
	// Places
	if ($post_type == 'liv_place') {
		$t = get_field('place_type');
		$sc = get_field('state_code');
		$onid = get_field('old_node_id');
		if ($sc) {
			$state = $sc;	
		}
		if ($onid) {
			$placeid = json_encode(array($onid));	
		}
		if ($t == 'city') {
			$pagetype = $t;
		} elseif ($t == 'state') {
			$pagetype = $t;
		} else {
			$pagetype = 'page';
		}
		$place = array(get_the_title( ));

	}

	//Magazines
	if ($post_type ==  'liv_magazine') {
		$pagetype = 'digital_magazine';
		$mag_place_type = get_field('mag_place_type');
		// $placeid = get_field('mag_old_node_id');
		$mag_place_array = get_field('place_relationship');
		if ($mag_place_array) {
		$mag_places = array();
		$mag_ids = array();
		foreach ($mag_place_array as $mpa) {
			$mpa_parent = wp_get_post_parent_id($mpa);
			if ($mag_place_type == 'State' && $mpa_parent < 1) {
				$mag_places[] = get_the_title($mpa);
				$mag_ids[] = get_field('old_node_id', $mpa);
			} elseif ($mag_place_type != 'State' && $mpa_parent > 0) {
				$mag_places[] = get_the_title($mpa);
				$mag_ids[] = get_field('old_node_id', $mpa);
			}
		}
		$place = $mag_places;
		$placeid = json_encode($mag_ids);
		// $placeid = get_field('mag_old_node_id') ? get_field('mag_old_node_id') : get_the_ID( );

	}
	}
	// Best Places 
	if ($post_type == 'best_places') {
		$pagetype = 'best_places';
		if (has_post_parent( )) {
			$parent = get_post_parent(  );
			// print_r($parent);
			$currentcat = $parent->post_title;
		} else {
			$currentcat = get_the_title(  );
			$global = "true";

		}
		$year_tax = get_the_terms($ID, 'best_places_years' );

		if ($year_tax) {
			$year = $year_tax[0]->name;
		}

		$bp_place = get_field('place_relationship');
		// $bp_places = array();
		// print_r($bp_place);
		if ($bp_place) {
			$bpp_Id = $bp_place[0];
			$place = get_the_title($bpp_Id);
			// $placeid = get_field('old_node_id',$bpp_Id);
			// $placeid = json_encode($bp_place);
			$placeid = $bp_place[0];
			$t = get_field('place_type', $bpp_Id);
			if ($t == 'state') { 
				$state = get_field('state_code',$bp_place);
			} elseif ($t == 'city') {
				$p = get_post_parent($bpp_Id);
				$state = get_field('state_code',$p->ID);
			}
		}
	}

	// Pages
	if ($post_type == 'page') {
		if ( in_array($ID, $topic_page_array)) {
			$pagetype = 'topic';
		} elseif (is_front_page()) {
			$pagetype = 'home';
			$global = "true";
		} else {
			$pagetype = 'page';
		}
	}
	// Articles
	if ($post_type == 'post') {
		$articleid = $ID;
		$pagetype = 'article';
		$currentcat = $cat[0]->name;
		$article_place_rel = get_field('place_relationship');
		$post_places = array();
		$place_ids = array();
		$state_names = array();
		// $placeid = get_field('article_node_id');
		if ($article_place_rel) {
			foreach($article_place_rel as $apr) {
				// $post_places[] = "'".get_the_title($apr)."'";
				$apr_parent = wp_get_post_parent_id($apr);
				if ($apr_parent && $apr_parent > 0) {
					$state_names[] = get_field('state_code', $apr_parent);
				} else {
					$state_names[] = get_field('state_code', $apr);
				}

				$post_places[] = get_the_title($apr);
				$place_ids[] = strval(get_field('old_node_id',$apr));
			}
			if (!empty($state_names)) {
				$state = $state_names[0];
			}
			// print_r($post_places);
			// $place = implode(",", $post_places);
			$place = $post_places;
			// $placeid = implode(",",$place_ids);
			$placeid = json_encode($place_ids);
			// $placeid = get_field('old_node_id', $article_place_rel[0]);
		} else  {
			$global = 'true';
			$placeid = "";
		}
		if (get_field('sponsored')) {
			$global = 'sponsored';
		}
		switch ($cat[0]->term_id) {
			case 11: //exp. and adventures
				$oldcategory = 'things-to-do';
				break;
			case 32: // affordable places
				$oldcategory = 'affordable-places-to-live';
				break;
			case 12: // Ed, careers and op
				$oldcategory = 'business';
				break;
			case 16: // food scenes
				$oldcategory = 'foodscenes';
				break;	
			case 13: // healthy places
				$oldcategory = 'health';
				break;	
			case 14: // love where you live
				$oldcategory = 'community';
				break;	
			case 47: // where to live now
				$oldcategory = 'real-estate';
				break;	
			default: 
				$oldcategory = '';

	}
		
	}
	$currentcat = str_replace('&amp;',' ', $currentcat);
	?>
<script>
		if (!window.ListingObj) {
	var ListingObj = {
		"pageslug" : window.location.pathname,
		"pagetype" : "<?php echo $pagetype; ?>",
		"currentcat": "<?php echo $currentcat; ?>",
		"place"		: <?php echo json_encode($place); ?>,
		"placeid"	: "",
		"state"		: "<?php echo $state; ?>",
		"oldcategory"	: "<?php echo $oldcategory; ?>",
		"global"	: "<?php echo strval($global); ?>",
		"year"	: "<?php echo $year; ?>",
		"articleid":	"<?php echo $articleid; ?>"
	}
	<?php if (strlen($placeid) > 0) { ?>
	ListingObj['placeid'] = <?php echo $placeid; ?>;	
	<?php } ?>
	}
	
</script>
<?php }
 //echo strlen($placeid) > 0 ? $placeid : ''; 
add_action('wp_head','create_listing_object');



// STAGING TEMPLATE_1 ?>

<?php global $post;
$ID = get_the_ID();?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry-content">
<?php if (get_field('number_of_columns')) { ?>
	
        <style>
            div#ez-toc-container nav ul {
                columns: <?php echo get_field('number_of_columns'); ?>; 
            }
        </style>
		<?php } 
 
if (get_field('enable_mega_hero', $ID)):
    require(get_stylesheet_directory(  ).'/assets/lib/state_abbv.php');
        $override = get_field('override_featured_image'); 
        $megahero_height = get_field('megahero_height');
        $megahero_vertical = get_field('vertical_position') ? get_field('vertical_position') : 'center';
        if ( is_mobile() ) {
            $size = 'portrait';
        } else {
            $size = 'full';
        }
        $article_thumb_url = $override ? wp_get_attachment_image_url( $override['id'], $size ) :  get_the_post_thumbnail_url( $ID, $size);
        $article_thumb_id = $override ? $override['id'] : get_post_thumbnail_id();
        $mega_title = get_field('custom_title_override') ? get_field('custom_title_override') : get_the_title(); 
        $megacat = get_the_category( ); 
        $uriSegments = explode("/", parse_url(get_the_permalink( ), PHP_URL_PATH)); 
        $uriSegments = array_filter($uriSegments);
        $numSegments = count($uriSegments);
        $state_seg = strtoupper($uriSegments[1]);
        $subtitlePlace = '';
        if (array_key_exists($state_seg, $us_state_abbrevs_names)) {
            $subtitlePlace =  $us_state_abbrevs_names[$state_seg].' '; 
        } else {
            $subtitlePlace = $uriSegments[2].' ';
        }
        $hero_img_byline = get_field('img_byline', $article_thumb_id);
        $hero_img_place_name = get_field('img_place_name', $article_thumb_id);
?>
            <div class="mega-hero alignfull" style="background-image: url('<?php echo $article_thumb_url; ?>'); height: <?php echo $megahero_height; ?>vh; background-position-y: <?php echo $megahero_vertical; ?>;">
            <div class="mega-hero-text-area">
                <p class="mega-hero__subheader"><?php // echo $subtitlePlace.$megacat[0]->name;  ?><?php echo $megacat[0]->name; ?></p>
                <?php echo '<p class="mega-hero__header">'.$mega_title.'</p>'; ?>
            </div>
         
        
        <?php if ($hero_img_byline || $hero_img_place_name) {
             echo '<div class="absolute-container"><div class="livability-image-meta">';
             echo $hero_img_place_name ? $hero_img_place_name : '' ;
             echo $hero_img_place_name && $hero_img_byline ? ' / ' : '';
             echo $hero_img_byline ?  strip_tags($hero_img_byline, "<a>") : '' ;
             echo '</div></div>';
        } ?>
        </div>
	<?php endif; ?>
         <div class="wp-block-columns full-width-off-white">
            <div class="wp-block-column">
                <div class="wp-block-jci-ad-area-one" style="display: flex; justify-content: center;" >
                <?php echo the_ad_group(698); ?>
                </div>
            </div>
        </div>
    	<div class="wp-block-columns liv-columns">
        	<div class="wp-block-column"><!-- main column-->
            	<div class="wp-block-columns liv-columns-2">
                	<div class="wp-block-column"></div>
                	<div class="wp-block-column">
                     <?php if (function_exists('return_breadcrumbs')) {
                                echo return_breadcrumbs(); 
                            } ?>
                    <?php if (get_field('article_announcement', 'option')){ ?>
                        <!-- wp:paragraph {"style":{"typography":{"lineHeight":"2"},"spacing":{"padding":{"right":"35px","bottom":"15px","left":"35px","top":"15px"}}},"backgroundColor":"green"} -->
                        <div class="bp23-announcement has-green-background-color has-background" style="display: none; padding-top:15px;padding-right:35px;padding-bottom:15px;padding-left:35px;line-height:2"><?php echo get_field('article_announcement', 'option'); ?></div>
                        <!-- /wp:paragraph -->
                        <?php } ?>
                    <h1 class="h2"><?php echo the_title(); ?></h1>
                     <?php $cat = get_the_category(  );
                        if ($cat[0]->slug == 'connected-communities') {
                            get_template_part( 'template-parts/blocks/cta-block' );
                        }
                          ?>
                		
              </div> <!-- breadcrumb title column-->
            </div> <!--liv columns 2-->
            <div class="wp-block-columns liv-columns-2">
             <div class="wp-block-column">
                        <?php //echo do_shortcode( '[addtoany]' ); ?>
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
                        <?php get_template_part( 'template-parts/blocks/internally-promoted-articles' ); ?>
                    </div>
                                <div class="wp-block-column">
                     
                        <?php if (has_excerpt(  )): ?>
                            <p class="article-excerpt"><?php echo wp_strip_all_tags(get_the_excerpt()); ?></p>
                        <?php endif; ?>
                      
                        <p class="author">By <?php echo esc_html__( get_the_author(), 'livibility' ).' on '.esc_html( get_the_date() ); ?></p>
                        <?php if (get_field('sponsored')): 
                            $sponsor_text = get_field('sponsor_text') ? get_field('sponsor_text') : 'Sponsored by';
                            $name = get_field('sponsor_name');
                            $url = get_field('sponsor_url'); ?>
                        <div class="sponsored-by">
                            <p>Sponsored by: <a href="<?php echo esc_url( $url ); ?>"><?php _e($name, 'livability'); ?></a></p>
                        </div>
                        <?php endif; ?>
                        <?php if (has_post_thumbnail() && !get_field('hide_featured_image')): ?>
                        <figure class="wp-block-image size-full">
                        <div class="img-container">
                            <?php
                            $caption = get_the_post_thumbnail_caption();
                            $f_post_image_id = get_post_thumbnail_id();
                            $f_img_byline = get_field('img_byline', $f_post_image_id);
                            $f_img_place_name = get_field('img_place_name', $f_post_image_id);
                            if ($f_img_byline || $f_img_place_name) {
                                echo get_the_post_thumbnail($ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                                echo '<div class="livability-image-meta">';
                                echo $f_img_place_name ? $f_img_place_name : '' ;
                                echo $f_img_place_name && $f_img_byline ? ' / ' : '';
                                echo $f_img_byline ?  strip_tags($f_img_byline, "<a>") : '' ;
                                echo '</div>';
                            } else {
                                echo get_the_post_thumbnail($ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                            }
                             ?>
                            
                        </div>
                        <?php if ($caption) {
                            echo '<figcaption>'.$caption.'</figcaption>';
                        } ?>
                        </figure>
                        <?php endif; ?>
                        <?php the_content(); ?>
                        <div class="cm-text-links-<?php echo $ID; ?>"></div>
                    
                    </div>
            </div> <!-- second lc2 --> 
            
                </div> <!-- lc first column-->
            	<div class="wp-block-column"><!-- sidebar column-->
             	<div class="wp-block-jci-ad-area-two">
                    <?php echo the_ad_group(699); ?>
                </div>
        	</div><!-- sidebar column-->
            </div> <!-- liv-columns -->
</div> <!-- entry-content --> 
<?php 
     if (get_field('make_horizontal')) {
        get_template_part( 'template-parts/content/content-horizontal-scroll' );
    }
    ?>
  <div class="wp-block-columns">
            <div class="wp-block-column">
            <?php if ($cat[0]->slug == 'connected-communities') {
                get_template_part( 'template-parts/blocks/cc-global-carousel' ); 
            }
            // get_template_part( 'template-parts/blocks/local-sponsored', null, array('city' => $args['city'], 'global' => $args['global'], 'state' => $args['state'])); ?>
            </div>
        
</div><!-- entry content -->
<?php 
     if (get_field('make_horizontal')) {
        get_template_part( 'template-parts/content/content-horizontal-scroll' );
    }
    ?>
    <div class="entry-content">
   <div class="wp-block-columns">
            <div class="wp-block-column">
            <?php if ($cat[0]->slug == 'connected-communities') {
                get_template_part( 'template-parts/blocks/cc-global-carousel' ); 
            }
            // get_template_part( 'template-parts/blocks/local-sponsored', null, array('city' => $args['city'], 'global' => $args['global'], 'state' => $args['state'])); ?>
            </div>
        </div> 
    </div><!-- .entry-content -->

</article>

<?php // PRODUCTION TEMPLATE_1 ?>

<?php global $post;
$ID = get_the_ID();?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry-content">
<?php if (get_field('number_of_columns')) { ?>
	
        <style>
            div#ez-toc-container nav ul {
                columns: <?php echo get_field('number_of_columns'); ?>; 
            }
        </style>
		<?php } 
 
if (get_field('enable_mega_hero', $ID)):
    require(get_stylesheet_directory(  ).'/assets/lib/state_abbv.php');
        $override = get_field('override_featured_image'); 
        $megahero_height = get_field('megahero_height');
        $megahero_vertical = get_field('vertical_position') ? get_field('vertical_position') : 'center';
        if ( is_mobile() ) {
            $size = 'portrait';
        } else {
            $size = 'full';
        }
        $article_thumb_url = $override ? wp_get_attachment_image_url( $override['id'], $size ) :  get_the_post_thumbnail_url( $ID, $size);
        $article_thumb_id = $override ? $override['id'] : get_post_thumbnail_id();
        $mega_title = get_field('custom_title_override') ? get_field('custom_title_override') : get_the_title(); 
        $megacat = get_the_category( ); 
        $uriSegments = explode("/", parse_url(get_the_permalink( ), PHP_URL_PATH)); 
        $uriSegments = array_filter($uriSegments);
        $numSegments = count($uriSegments);
        $state_seg = strtoupper($uriSegments[1]);
        $subtitlePlace = '';
        if (array_key_exists($state_seg, $us_state_abbrevs_names)) {
            $subtitlePlace =  $us_state_abbrevs_names[$state_seg].' '; 
        } else {
            $subtitlePlace = $uriSegments[2].' ';
        }
        $hero_img_byline = get_field('img_byline', $article_thumb_id);
        $hero_img_place_name = get_field('img_place_name', $article_thumb_id);
?>
            <div class="mega-hero alignfull" style="background-image: url('<?php echo $article_thumb_url; ?>'); height: <?php echo $megahero_height; ?>vh; background-position-y: <?php echo $megahero_vertical; ?>;">
            <div class="mega-hero-text-area">
                <p class="mega-hero__subheader"><?php // echo $subtitlePlace.$megacat[0]->name;  ?><?php echo $megacat[0]->name; ?></p>
                <?php echo '<p class="mega-hero__header">'.$mega_title.'</p>'; ?>
            </div>
         
        
        <?php if ($hero_img_byline || $hero_img_place_name) {
             echo '<div class="absolute-container"><div class="livability-image-meta">';
             echo $hero_img_place_name ? $hero_img_place_name : '' ;
             echo $hero_img_place_name && $hero_img_byline ? ' / ' : '';
             echo $hero_img_byline ?  strip_tags($hero_img_byline, "<a>") : '' ;
             echo '</div></div>';
        } ?>
        </div>
	<?php endif; ?>
         <div class="wp-block-columns full-width-off-white">
            <div class="wp-block-column">
                <div class="wp-block-jci-ad-area-one" style="display: flex; justify-content: center;" >
                <?php get_template_part( 'template-parts/blocks/ad-one' ); ?>
                </div>
            </div>
        </div>
    	<div class="wp-block-columns liv-columns">
        	<div class="wp-block-column"><!-- main column-->
            	<div class="wp-block-columns liv-columns-2">
                	<div class="wp-block-column"></div>
                	<div class="wp-block-column">
                     <?php if (function_exists('return_breadcrumbs')) {
                                echo return_breadcrumbs(); 
                            } ?>
                    <?php if (get_field('article_announcement', 'option')){ ?>
                        <!-- wp:paragraph {"style":{"typography":{"lineHeight":"2"},"spacing":{"padding":{"right":"35px","bottom":"15px","left":"35px","top":"15px"}}},"backgroundColor":"green"} -->
                        <div class="bp23-announcement has-green-background-color has-background" style="display: none; padding-top:15px;padding-right:35px;padding-bottom:15px;padding-left:35px;line-height:2"><?php echo get_field('article_announcement', 'option'); ?></div>
                        <!-- /wp:paragraph -->
                        <?php } ?>
                    <h1 class="h2"><?php echo the_title(); ?></h1>
                     <?php $cat = get_the_category(  );
                        if ($cat[0]->slug == 'connected-communities') {
                            get_template_part( 'template-parts/blocks/cta-block' );
                        }
                          ?>
                		
              </div> <!-- breadcrumb title column-->
            </div> <!--liv columns 2-->
            <div class="wp-block-columns liv-columns-2">
             <div class="wp-block-column">
                        <?php //echo do_shortcode( '[addtoany]' ); ?>
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
                        <?php get_template_part( 'template-parts/blocks/internally-promoted-articles' ); ?>
                    </div>
                                <div class="wp-block-column">
                     
                        <?php if (has_excerpt(  )): ?>
                            <p class="article-excerpt"><?php echo wp_strip_all_tags(get_the_excerpt()); ?></p>
                        <?php endif; ?>
                      
                        <p class="author">By <?php echo esc_html__( get_the_author(), 'livibility' ).' on '.esc_html( get_the_date() ); ?></p>
                        <?php if (get_field('sponsored')): 
                            $sponsor_text = get_field('sponsor_text') ? get_field('sponsor_text') : 'Sponsored by';
                            $name = get_field('sponsor_name');
                            $url = get_field('sponsor_url'); ?>
                        <div class="sponsored-by">
                            <p>Sponsored by: <a href="<?php echo esc_url( $url ); ?>"><?php _e($name, 'livability'); ?></a></p>
                        </div>
                        <?php endif; ?>
                        <?php if (has_post_thumbnail() && !get_field('hide_featured_image')): ?>
                        <figure class="wp-block-image size-full">
                        <div class="img-container">
                            <?php
                            $caption = get_the_post_thumbnail_caption();
                            $f_post_image_id = get_post_thumbnail_id();
                            $f_img_byline = get_field('img_byline', $f_post_image_id);
                            $f_img_place_name = get_field('img_place_name', $f_post_image_id);
                            if ($f_img_byline || $f_img_place_name) {
                                echo get_the_post_thumbnail($ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                                echo '<div class="livability-image-meta">';
                                echo $f_img_place_name ? $f_img_place_name : '' ;
                                echo $f_img_place_name && $f_img_byline ? ' / ' : '';
                                echo $f_img_byline ?  strip_tags($f_img_byline, "<a>") : '' ;
                                echo '</div>';
                            } else {
                                echo get_the_post_thumbnail($ID, 'medium_large', array('style'=> 'height: auto; max-width: none;') );
                            }
                             ?>
                            
                        </div>
                        <?php if ($caption) {
                            echo '<figcaption>'.$caption.'</figcaption>';
                        } ?>
                        </figure>
                        <?php endif; ?>
                        <?php the_content(); ?>
                        <div class="cm-text-links-<?php echo $ID; ?>"></div>
                    
                    </div>
            </div> <!-- second lc2 --> 
            
                </div> <!-- lc first column-->
            	<div class="wp-block-column"><!-- sidebar column-->
             	<div class="wp-block-jci-ad-area-two">
                    <?php echo the_ad_group(699); ?>
                </div>
        	</div><!-- sidebar column-->
            </div> <!-- liv-columns -->
</div> <!-- entry-content --> 
<?php 
     if (get_field('make_horizontal')) {
        get_template_part( 'template-parts/content/content-horizontal-scroll' );
    }
    ?>
  <div class="wp-block-columns">
            <div class="wp-block-column">
            <?php if ($cat[0]->slug == 'connected-communities') {
                get_template_part( 'template-parts/blocks/cc-global-carousel' ); 
            }
            // get_template_part( 'template-parts/blocks/local-sponsored', null, array('city' => $args['city'], 'global' => $args['global'], 'state' => $args['state'])); ?>
            </div>
        
</div><!-- entry content -->
<?php 
     if (get_field('make_horizontal')) {
        get_template_part( 'template-parts/content/content-horizontal-scroll' );
    }
    ?>
    <div class="entry-content">
   <div class="wp-block-columns">
            <div class="wp-block-column">
            <?php if ($cat[0]->slug == 'connected-communities') {
                get_template_part( 'template-parts/blocks/cc-global-carousel' ); 
            }
            // get_template_part( 'template-parts/blocks/local-sponsored', null, array('city' => $args['city'], 'global' => $args['global'], 'state' => $args['state'])); ?>
            </div>
        </div> 
    </div><!-- .entry-content -->

</article>



<?php
$test_args = array(
	'post_type'		=> 'post',
	'posts_per_page'	=> -1,
	// 'meta_query'		=> array(
	// 	array(
	// 		'key'		=> 'place_relationship',
	// 		'value'		=> array(54161,54182,54186,54234,54254,54257),
	// 		'compare'	=> 'IN'
	// 	)
	// ),
	'meta_key'			=> 'place_relationship',
	'meta_value_num'		=> 54161,54182,54186,54234,54254,54257
	// 'meta_compare'		=> 'IN'
);
$test_query = new WP_Query($test_args);
echo '<div style="margin-top: 100px;">start test query</div>';
if ($test_query->have_posts()):
	while ($test_query->have_posts()): $test_query->the_post();
	echo 'title: '.get_the_title().'<br />link: '.get_the_permalink().get_the_ID().'<br />';
	
	// $postmetas = get_post_meta(get_the_ID());

	// foreach($postmetas as $meta_key=>$meta_value) {
	// 	echo $meta_key . ' : ' . $meta_value[0] . '<br/>';
	// }
	$test_pr = get_post_meta(get_the_ID(), 'place_relationship');
	print_r($test_pr[0]);
	echo '<br /><br />';
endwhile;
else: 
	echo 'no posts here';
endif;
wp_reset_query(  );