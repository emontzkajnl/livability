
    <div class="small-articles sponsor-read-more">
    <?php 
        foreach($args['city-state'] as $p) { ?>
        <div class="small-article-card no-bkgrnd">
        <a href="<?php echo get_the_permalink( $p); ?>" class="sma-img">
        <?php echo get_the_post_thumbnail($p,'rel_article' );  ?>
            </a> 
            <div class="sma-title">
            <a href="<?php echo get_the_permalink( $p); ?>" ><h4><?php echo 'More about Living in '.get_the_title($p); ?></h4></a>
            </div>
        </div>

        <?php }
        ?>
    
    </div><!-- small articles -->
