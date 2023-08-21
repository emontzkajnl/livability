<?php
/**
 * Plugin Name: JCI Default Post Type Blocks 
 * Description: Assign Pattern to custom post type.
 * Version: 1.0
 * Author: Journal Communications, Inc.
 * Text Domain: jci-default-post-type-blocks 
 */

/*Assign the block pattern to custom post type*/
add_filter( 'default_content', 'livability_default_editor_content' );
function livability_default_editor_content( $content ) {
    global $post_type;
    
    $content = "";
    switch( $post_type )  {
        // case 'best_places';
        //     if ( function_exists( 'getBlockPattern') ) { 
        //        $content = getBlockPattern('place-template');
        //     }
        //     break;

        case 'liv_magazine':
            if ( function_exists( 'getBlockPattern') ) { 
               $content = getBlockPattern('magazine-template');
            }
            break;

        case 'liv_place':
            if ( function_exists( 'getBlockPattern') ) { 
               $content = getBlockPattern('single-post-template');
            } 
            break;
    }

    return $content;
}

?>