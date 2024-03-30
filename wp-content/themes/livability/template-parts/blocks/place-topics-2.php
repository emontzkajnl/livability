<?php 
$posts = $args['posts']; 
$cat = $args['cat'];
$cat = str_replace('-','_', $cat);
$single_class = count($posts) == 1 ? 'single' : ''; 
$length = count($posts) > 3 ? 3 : count($posts); ?>


<div class="place-topics-2 <?php echo $cat; ?>">
    <div class="place-topics-2__container">
    <?php for ($i=0; $i < $length; $i++) { 
        # code...
    // }
     //foreach ($posts as $p) {  ?>
       <div class="place-topics-2__card <?php echo $single_class; ?>">
           <a href="<?php echo get_the_permalink($posts[$i]); ?>">
           
           <div class="place-topics-2__img-container">
               <?php echo get_the_post_thumbnail($posts[$i], 'medium'); ?>
           </div>
            </a>
            <div class="place-topics-2__text-container">
           <h3 class="place-topics-2__title"><a class="unstyle-link" href="<?php echo get_the_permalink($posts[$i]); ?>"><?php echo get_the_title($posts[$i]); ?></a></h3>
           <p class="place-topics-2__excerpt"><?php echo get_the_excerpt( $posts[$i] ); ?></p>
            </div>
       </div>
    <?php } 
    echo '</div>'; // place-topics-2__container
    if (count($posts) > 3):
        echo '<button class="place-topics-2__button" data-cat="'.$cat .'">More Articles</button>'; ?>
        <script>
            window['<?php echo $cat; ?>'] = {};
            Object.assign(window['<?php echo $cat; ?>'], {current_page: '1'});
            Object.assign(window['<?php echo $cat; ?>'], {posts: '<?php echo json_encode($posts); ?>'});
            Object.assign(window['<?php echo $cat; ?>'], {max_pages: '<?php echo ceil(count($posts) / 3); ?>'});
            window['test'] = {};
        </script>
    <?php endif; ?>
</div>

<?php 
// if $posts > 3, add button
// create array of next three posts
// increase "page" by one and send array to ajax function
// functions.php can use template for ajax
// should store data on window object: full array of posts, category, page

?>