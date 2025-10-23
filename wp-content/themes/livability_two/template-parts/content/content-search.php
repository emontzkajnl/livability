<?php 
$type = get_post_type();
$heading = '';
switch ($type) {
    case 'liv_place':
        $heading = get_field('place_type').' page';
        break;
    case 'post':
        $cat = get_the_category();
        $heading = $cat[0]->name;
        break;  
    case 'liv_magazine':
        $heading = 'Magazine';
        break;     
    case 'best_places':
        $parent = get_post_parent();
        if ($parent) {
            $heading = get_the_title($parent->ID);
        } else {
            $heading = 'Best Places';
        }
        break;   
    default:
        break;
}
// liv_place = city or state, best_places = parent title, post = cat, liv_magazine = magazine, 

?>
<li class="one-hundred-list-container">
<a href="<?php echo get_the_permalink( ); ?>" class="ohl-thumb" style="background-image: url(<?php echo the_post_thumbnail_url(); ?>);"></a>
<div class="ohl-text">
<h5 class="green-text uppercase"><?php echo $heading; ?></h5>
<a href="<?php  echo get_the_permalink( ); ?>">
<?php _e(the_title('<h2>','</h2>'), 'livability'); 
the_excerpt();
 ?>
 </div>
</a>

</li>