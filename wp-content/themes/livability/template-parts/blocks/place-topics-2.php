
<?php 
$posts = $args['posts']; 
$cat = $args['cat'];
$catObj = get_category_by_slug($cat);
$catID = $catObj->term_id;

$single_class = count($posts) == 1 ? 'single' : ''; 
$length = count($posts) > 3 ? 3 : count($posts); 
$sponsor = get_topic_sponsors(); // defined in functions.php
if ($sponsor) {
    // print_r($sponsor);
    foreach($sponsor as $key => $S) {
        if ($S['category'] == $catID) {
        $sponsor_name = $S['sponsor'];
        $sponsor_url = $S['url']; 
        echo $sponsor_url ?
        '<p class="topic-sponsor">Sponsored by <a href="'.$sponsor_url.'" target="_blank">'.$sponsor_name.'</a></p>' : 
        '<p class="topic-sponsor">Sponsored by '.$sponsor_name.'</p>';
        break;
        }
    }
} 
$cat = str_replace('-','_', $cat);

// move 1st and 2nd lattice tagged posts to 3rd and 4th positions
$lattice_posts = [];
$all_posts = [];
if (count($posts) > 3) {
    foreach ($posts as $key => $value) {
        if (count($lattice_posts) < 2) {
            $current = get_post($value);
            if (has_tag('lattice', $current)) {
                $lattice_posts[] = $value;
            } else {
                $all_posts[] = $value;
            }
        } else {
            $all_posts[] = $value;
        }
    }
 
    if (!empty($lattice_posts)) {
        array_splice($all_posts, 2, 0, $lattice_posts);
        $posts = $all_posts;
    }
}
?>


<div class="place-topics-2 <?php echo $cat; ?>">
    <div class="place-topics-2__container">
    <?php for ($i=0; $i < $length; $i++) { ?>
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