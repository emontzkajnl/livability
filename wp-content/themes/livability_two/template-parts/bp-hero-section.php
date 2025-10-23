<?php
if (get_field('display_hero')): 
    $rank = get_field('bp_rank');
    // $badge = get_field('badge');
    global $post;
    $isParent = $post->post_parent < 1;
    $heroClass = $isParent ? 'list-parent' : 'list-post';
    // $badgeClass = $badge ? 'has-badge' : '';
    // $image_url = get_field('hero_background_image') ? get_field('hero_background_image') : get_the_post_thumbnail_url(get_the_ID(), 'post-thumbnail');
    $hero_bkgrnd_img = get_field('hero_background_image');
    $hide_text = get_field('hide_hero_text');
    $title_text = get_field('override_page_title') ? get_field('override_page_title') : get_the_title(); 
    $byline = $hero_bkgrnd_img ? get_field('img_byline', $hero_bkgrnd_img) : get_field('img_byline',get_post_thumbnail_id()); 
 
?>

    <div class="hero-section alignfull <?php echo $heroClass; ?>">
    <?php echo $hero_bkgrnd_img ? wp_get_attachment_image( $hero_bkgrnd_img, 'full' ) : get_the_post_thumbnail(  ); ?>
    <div class="container" style="position: relative;">
    <h2><?php //echo 'custom background is '.wp_get_attachment_image( $hero_bkgrnd_img, 'full' ); ?></h2>
    <?php //if ($badge) {
        //echo '<img class="hero-badge" src="'.$badge['url'].'" />';
    //}
    ?>
    <?php if (!$hide_text): ?> 
    <?php if ($isParent) { ?>
    <div class=" hero-title-area">
    <?php echo '<h2>'.$title_text.'</h2>'; ?>
    <!-- <h3><a href="#">Our Methology</a><a href="#">Ranking Criteria</a></h3> -->
    </div>
    <?php } else {
    $parent = get_post($post->post_parent); 
    $parentLink = get_permalink( $parent->ID);
    $badge = get_field('badge', $parent->ID);
    if ($badge) {echo '<a href="'.$parentLink.'"><img class="hero-badge" src="'.$badge['url'].'" /></a>';}
    ?>
        <div class="bp-text-area">
            <div class="green-circle"><?php echo $rank; ?></div>
            <!-- <h5 class="green-text uppercase"><a href="<?php //echo $parentLink; ?>"><?php //echo $parent->post_title;  ?></a></h5> -->
            <?php //the_title( '<h3>', '</h3>' ); ?>
            <a href="<?php echo $parentLink; ?>"><h1 class="bp-child-title"><?php echo '<span>'.get_the_title().'</span>'.$parent->post_title; ?></h1></a>
        </div>
        <?php } 
        endif; // end hide text 
        if ($byline) {
            echo '<p class="livability-image-meta" style="position: absolute; bottom: 0; right: 0;">'.strip_tags($byline).'</p>';
        }
        
        ?>
    </div>
    
</div>
<?php get_template_part( 'template-parts/content/content-bp-sponsor'); ?>

<?php endif;  ?>