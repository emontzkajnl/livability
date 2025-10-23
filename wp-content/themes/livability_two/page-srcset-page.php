<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */
include_once( get_stylesheet_directory() .'/assets/lib/simple_html_dom.php');
get_header();

while ( have_posts() ) :
	the_post();
	echo '<div style="padding-top: 150px;"></div>';

endwhile; // End of the loop.
// dynamic_sidebar( 'sidebar-2' );
// post range: 14500 - 80000, jan 2014 - april 2021
$args = array(
	'post_type'		=> 'post',
	'posts_per_page'=> -1,
	'date_query'	=> array(
		array(
			'before'	=> array(
				'year'	=> 2021,
				'month'	=> 4
			), 
			'after'	=> array(
				'year'	=> 2021,
				'month'	=> 3
			), 
			'inclusive'	=> true
		),
	),
	// 'post__in'		=> range(14500, 20000),
); 
$img_posts = new WP_Query($args);
if ($img_posts->have_posts()):
	echo 'post count: '.count($img_posts->posts).'<br />';
while ($img_posts->have_posts()):
	$img_posts->the_post();
	// echo get_the_ID(  ).'<br />';
	$post_changed = false;
	$this_post = get_post();
	$post_to_update = get_the_ID();
	$content = $this_post->post_content;
	$html = str_get_html($content);
	if (gettype($html) != 'boolean') {
		$elArray = $html->childNodes();
		foreach( $elArray as $key=>$element) {
			$text = $element->outertext;
			// echo 'text is '.substr($text, 0, 40).'...<br />';
			if (preg_match("/<img/i", $text ) && !preg_match("/alignleft/i", $text) && !preg_match("/alignright/i", $text) ){
				// echo 'matches the text<br />';
				// echo $text;
				$srcpos = strpos($text, 'src=');
				$figurepos = strpos($text, '<figure'); // if figure is present, this doesn't need to be fixed
				if ($srcpos > 0 ) {
					// echo 'found the src<br />';
					$closesrc = strpos($text,'"', $srcpos + 6);
					$src = substr($text, $srcpos + 5, ($closesrc - $srcpos - 5));
					// echo 'src: '.substr($text, $srcpos + 5);
					// $src = str_replace('dev-liv10.pantheonsite.io', 'liv10.lndo.site', $src );
					// $src = str_replace('livability.com', 'liv10.lndo.site', $src );
					$imgId = attachment_url_to_postid( $src );
					if ($imgId) {
						echo 'got the atachment id<br />';
						$post_changed = true;
						$alt = get_post_meta($imgId, '_wp_attachment_image_alt', true);
						$alt = $alt ? $alt : '';
						$openingComment = '<!-- wp:image {"id":'.$imgId.',"sizeSlug":"full","linkDestination":"none"} -->';
						$closingComment = '<!-- /wp:image -->';
						$figureElement = '<figure class="wp-block-image size-full"><img src="'.$src.'" alt="'.$alt.'" class="wp-image-'.$imgId.'"/></figure>';
						// check to see if img is inside paragraph commments
						if ($key == 0 || strpos($elArray[$key - 1]->outertext, '<!-- wp:paragraph -->') === false) {
							$elArray[$key]->outertext = $openingComment.$figureElement.$closingComment;
						} else {
						$elArray[$key - 1]->outertext = $openingComment;
						$elArray[$key]->outertext = $figureElement;
						$elArray[$key + 1]->outertext = $closingComment;
						}
					}
				};
			}
		}
		if ($post_changed) {wp_update_post(array('ID' => $post_to_update, 'post_content' => implode('', $elArray))); echo 'updated '.$post_to_update.' '.get_the_title().'<br />';}
	}
endwhile;
endif;
wp_reset_query( );
// echo 'number of posts is '.count($img_posts->posts);
// print_r($img_posts);


get_footer();