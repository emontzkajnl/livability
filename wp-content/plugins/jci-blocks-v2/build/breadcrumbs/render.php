<?php 
global $post;
    $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    // $delimiter = '&raquo;'; // delimiter between crumbs
    $delimiter = '&gt;'; // delimiter between crumbs
    $home = 'Home'; // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb
    $homeLink = get_bloginfo('url');
    $output = ''; //returned output
    $currentID = get_the_ID();
    // $post = get_post();
    // $post_type = $post->post_type;
    $post_type = get_post_type();
    $cat = get_the_category( $currentID );
    // print_r($post);
    if (!function_exists('getCategoryPageUrl')) {
    function getCategoryPageUrl($cat) {
        $url = '';
        switch ($cat[0]->term_id) { 
            case 32: //affordable places
                $url = 70439;
                break;
            case 12: // education, carreers, oppotunity
                $url = 70441;
                break;
            case 11: // experiences and adventures
                $url = 70443;
                break;
            case 16: // food scenes 
                $url = 70445;
                break;
            case 13: // healthy places 
                $url = 70447;
                break;
            case 14: // love where u live
                $url = 70451;
                break;                                
            case 18: // make your move
                $url = 70453;
                break;
            case 47: // where to live now
                $url = 70457;
                break;    
            default: 
                $url = '';               
        }
            return get_the_permalink($url);
        }
    }

    $output .=  '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ' ;
    if ( is_author()) {
        $author = get_queried_object();
        $name = get_user_meta($author->ID);
        $output .= ' Authors ' . $delimiter . ' '  . get_the_author_meta('display_name') . ' ';
    } elseif ( !has_post_parent()) {
        if ( $post_type == 'post') { 
            $parsed = wp_parse_url( get_permalink());
            $split = explode('/',$parsed['path']);
            if (count($split) > 4) { // has state
                $state_segment = '/'.$split[1].'/';
                $ss_obj = get_page_by_path($state_segment, OBJECT, 'liv_place');
                if ($ss_obj) {
                    $output .= ' <a href="' . get_permalink( $ss_obj->ID) . '"> ' . get_field('state_code', $ss_obj->ID) . '</a> ' . $delimiter . ' ';
                }
                
            }
            if (count($split) > 5) { // has city
                $city_rel = get_field('place_relationship');
                $city_title = ucwords(str_replace('-', ' ', $split[2]));
                if (count($city_rel) == 1) {
                    $city_link = get_the_permalink( $city_rel[0]);
                } elseif (count($city_rel) > 1) {
                    $rc_array = get_posts(array(
                        'post_type'	=> 'liv_place',
                        'orderby'   => 'post__in',
                        'post__in' => $city_rel,
                        'posts_per_page' => 1
                    ));
                    $city_link = get_the_permalink($rc_array[0]->ID );
                }
                $output .= ' <a href="' . $city_link . '"> ' . $city_title . '</a> ' . $delimiter . ' ';
            }

            $output .= ' <a href="' . getCategoryPageUrl($cat) . '">'  . $cat[0]->name . '</a> ' . $delimiter . ' ';
        }
        $output .=  $before . get_the_title() . $after;
    } elseif (has_post_parent($currentID)) {
        $parent_id  = wp_get_post_parent_id($currentID);
        $breadcrumbs = array();
        
        while ($parent_id) {
            $page = get_page($parent_id);
            $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
            $parent_id  = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        for ($i = 0; $i < count($breadcrumbs); $i++) {
            $output .=  $breadcrumbs[$i];
            if ($i != count($breadcrumbs)-1) {
                $output .=  ' ' . $delimiter . ' ';
            }
        }
        $output .=  ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
    }
    $output .= '</div>';
 return $output;
 die();