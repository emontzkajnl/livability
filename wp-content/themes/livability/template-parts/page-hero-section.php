<?php
// short hero section for pages or author archive pages
if (is_author()) {
	$hero = get_field('global_author_header', 'option');
	// print_r($hero);
	$author_ID = $post->post_author;
	$author_name = get_the_author_meta( 'display_name', $author_ID );
	$background = $hero['url'];
	$override_page_title = 'Livability Author: '.$author_name;
} else {
	$background = get_field('hero_background_image');
	$override_page_title = get_field('override_page_title');
}


?>
<div class="hero-section page-hero alignfull"  style="background-image: url(<?php echo wp_get_attachment_url($background); ?>)">
    <div class="container" style="position: relative;">
		<div class="hero-title-area">
		<?php if ($override_page_title) {
			echo '<h2 class="h2">'.$override_page_title.'</h2>';
		} else {
			the_title( '<h2 class="h2">', '</h2>' );
		} ?>
		</div>
    </div>
</div>