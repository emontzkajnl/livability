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