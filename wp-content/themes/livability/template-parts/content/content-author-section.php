
    <?php 
    $author_ID = get_the_author_meta( 'ID' );
    $user_meta = get_user_meta($author_ID);
  
    $author_image = get_field('author_image', 'user_'.$author_ID);
    $title = '';
    if ($user_meta['wpseo_user_schema']) {
        $schema = unserialize($user_meta['wpseo_user_schema'][0]);
        $title = $schema['jobTitle'] ? $schema['jobTitle'] : '';
    } ?>
<div class="author-bio">
    <?php if ($author_image) {
        echo wp_get_attachment_image( $author_image['ID'], 'three_hundred_wide');
    } else {
        echo get_avatar( $author_ID, '130' );
    } ?>
    <div class="author-bio-content">
        <h2 class="author-title">About <?php echo get_author_name( ); ?></h2>
        <?php echo $title ? '<h4 style="font-size: 1.125rem;">'.$title.'</h4>' : ''; 
        if (get_the_author_meta('description')) { ?>
        <p class="author-description"><?php echo limitWordsAndAddEllipsis(get_the_author_meta('description'), 40); ?><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><span style="font-style: italic; font-weight: bold; color: #000;">Read Bio</span></a></p>
        <?php } else { ?>
        <p><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><button>Read Full Bio</button></a></p>
        <?php } ?>
        
    </div>
</div> <!-- author bio -->
