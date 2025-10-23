<?php 
    $parent = $bp_sponsor = false;
    if ($post) {
        $parent = $post->post_parent;
    }
    if ($parent == 0) {
        $bp_sponsor = get_field('bp_sponsor'); 
    } else {
        $bp_sponsor = get_field('bp_sponsor', $parent); 
    }
    if ($bp_sponsor) { 
        $bp_alt_text = $bp_sponsor['sponsor_alt_text'] ? ' alt="'.$bp_sponsor['sponsor_alt_text'].'" '  : ''; 
        $bp_output = $bp_sponsor['sponsor_name'] ? '<div class="bp-sponsor-container"><p class="container">' : '';
        $bp_output .= $bp_sponsor['sponsor_intro_text'] ? $bp_sponsor['sponsor_intro_text'].' ' : '';
        $bp_output .= $bp_sponsor['sponsor_url'] ? '<a href="'.$bp_sponsor['sponsor_url'].'" target="_blank">' : '';
        $bp_output .= $bp_sponsor['sponsor_name'] ? ' <span>'.$bp_sponsor['sponsor_name'].'</span>' : '';
        $bp_output .= $bp_sponsor['sponsor_logo'] ? '<img height="38" src="'.$bp_sponsor['sponsor_logo'].'" '.$bp_alt_text.' />' : '';
        $bp_output .= $bp_sponsor['sponsor_url'] ? '</a>' : '';
        $bp_output .= $bp_sponsor['sponsor_name'] ? '</p></div>' : '';
        echo $bp_output; ?>
    <?php } ?>
