<div class="author-bio">
    <?php $author_ID = get_the_author_meta( 'ID' );
    $author_image = get_field('author_image', 'user_'.$author_ID);
    if ($author_image) {
        echo wp_get_attachment_image( $author_image, 'three_hundred_wide');
    } else {
        echo get_avatar( get_the_author_meta( 'ID' ), '130' );
    } ?>
    <div class="author-bio-content">
    <h2 class="author-title">About the Author</h2>
    <p class="author-description"><?php echo get_the_author_meta('description'); ?></p>
    <p><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><button>More</button></a></p>
    </div>
</div>