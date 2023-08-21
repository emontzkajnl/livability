<div class="wp-block-columns top-100-2022">
    <div class="wp-block-column">
    <?php echo do_shortcode( '[addtoany]' ); ?>
    </div>
    <div class="wp-block-column">
    <div id="crumbs">
        <?php echo return_breadcrumbs(); ?> 
    </div>
     <!-- wp:paragraph {"style":{"typography":{"lineHeight":"2"},"spacing":{"padding":{"right":"35px","bottom":"15px","left":"35px","top":"15px"}}},"backgroundColor":"green"} -->
     <p class="bp23-announcement has-green-background-color has-background" style="padding-top:15px;padding-right:35px;padding-bottom:15px;padding-left:35px;line-height:2"><strong>UPDATE</strong>: Check out our new <a href="https://livability.com/best-places/2023-top-100-best-places-to-live-in-the-us/"><strong>2023 Best Cities to Live in the U.S.</strong></a> <strong>list</strong>.</p>
     <!-- /wp:paragraph -->
    <?php _e('<h1 class="h2">'.get_the_title().'</h1>'); ?>
    <?php if (get_field('sponsored')): 
        $sponsor_text = get_field('sponsor_text') ? get_field('sponsor_text') : 'Sponsored by';
        $name = get_field('sponsor_name');
        $url = get_field('sponsor_url'); ?>
    <div class="sponsored-by">
        <p>Sponsored by: <a href="<?php echo esc_url( $url ); ?>"><?php _e($name, 'livability'); ?></a></p>
    </div>
    <?php endif; ?>
    <?php if (has_excerpt(  )): ?>
        <p class="article-excerpt"><?php echo wp_strip_all_tags(get_the_excerpt()); ?></p>
    <?php endif; ?>
    <p class="author">By <?php echo esc_html__( get_the_author(), 'livibility' ).' on '.esc_html( get_the_date() ); ?></p>
    <div><?php $hide_featured = get_post_meta(get_the_ID(), 'hide_best_place_featured_image', true);
     if (!$hide_featured) {
    echo get_the_post_thumbnail($post->ID, 'medium_large' ); 
    }
    ?></div>
    <?php the_content();
     get_template_part('template-parts/blocks/ad-one' ); ?>
    </div>
    <div class="wp-block-column"></div>
</div>