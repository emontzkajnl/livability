<?php 
$posts = $args['posts']; ?>

<div class="place-topics-2">
    <?php foreach ($posts as $p) { ?>
       <div class="place-topics-2__card">
           <a href="<?php echo get_the_permalink($p); ?>">
           <div class="place-topics-2__img-container">
               <?php echo get_the_post_thumbnail($p, 'medium'); ?>
           </div>
            </a>
           <h3 class="place-topics-2__title"><a class="unstyle-link" href="<?php echo get_the_permalink($p); ?>"><?php echo get_the_title($p); ?></a></h3>
           <p class="place-topics-2__excerpt"><?php echo get_the_excerpt( $p ); ?></p>
       </div>
    <?php } ?>
</div>