<?php 

// include_once(get_stylesheet_directory() .'/get-place-distance.php');
include_once( get_stylesheet_directory() .'/assets/lib/simple_html_dom.php');
include_once( get_stylesheet_directory() .'/assets/lib/regions.php');
include_once( get_stylesheet_directory() .'/assets/lib/meta_key_display.php');
function liv_mobile_menu() {
  register_nav_menu('liv-mobile-menu',__( 'Liv Mobile Menu' ));
  register_nav_menus(
	array(
		'primary' => esc_html__( 'Primary menu', 'livability' ),
		'footer'  => esc_html__( 'Secondary menu', 'livability' ),
		'liv-mobile-menu'	=> esc_html__( 'Liv Mobile Menu', 'livability' )
	)
);
}
add_action( 'init', 'liv_mobile_menu' );

add_action('init', 'myStartSession', 1);
function myStartSession() {
    // if(!session_id()) {
        session_start();
    // }
}

// wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/js/slick.min.js', array('jquery'), null, true);
// wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/js/main.js', array('jquery','slick'), null, true);
// wp_enqueue_style( 'slick', get_stylesheet_directory_uri() . '/css/slick.css');
// wp_enqueue_style( 'slick-theme', get_stylesheet_directory_uri() . '/css/slick-theme.css');
function livability_enqueue_scripts() {
	global $topics;
	wp_enqueue_script( 'headroom', get_stylesheet_directory_uri() . '/assets/js/headroom.js', array('jquery'),null, true );
	wp_enqueue_script( 'main-theme', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery', 'slick', 'waypoints','waypoints-sticky', 'megamenu', 'jquery-ui-autocomplete'), null, true);
	// wp_enqueue_style( 'style', get_stylesheet_uri(), array('twenty-twenty-one-style'));
	wp_enqueue_style( 'houzez-parent', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'compressed', get_stylesheet_directory_uri() . '/compressed-style.css', array(), null);
	wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array('jquery'), null, true);
	wp_enqueue_script( 'waypoints', get_stylesheet_directory_uri() . '/assets/js/jquery.waypoints.min.js', array('jquery'), null, true );
	wp_enqueue_script( 'waypoints-sticky', get_stylesheet_directory_uri() . '/assets/js/sticky.min.js', array('jquery', 'waypoints'), null, true );
	wp_enqueue_script( 'youtube-api', 'https://www.youtube.com/iframe_api', array(), null, true );
	wp_enqueue_script( 'navigation', get_stylesheet_directory_uri() . '/assets/js/navigation.js', array('jquery'), null, true );
	wp_enqueue_style( 'slick-style', get_stylesheet_directory_uri() . '/assets/css/slick.css');
	wp_enqueue_style( 'slick-theme', get_stylesheet_directory_uri() . '/assets/css/slick-theme.css');
	wp_dequeue_style( 'houzez-styling-options-css ' );
	wp_dequeue_style( 'bootstrap-css' );
	// wp_dequeue_style( 'houzez_custom_styling' );
	// wp_dequeue_script('twenty-twenty-one-primary-navigation-script');
	// wp_dequeue_script('twenty-twenty-one-ie11-polyfills');
	if (is_page('city-data-iframe')) {
		wp_enqueue_style( 'city-widget', get_stylesheet_directory_uri() . '/assets/css/city-widget.css');
	}

	wp_enqueue_script('gsap', 'https://unpkg.com/gsap@3.10.4/dist/gsap.min.js', array(), null, true ); 
	wp_enqueue_script('gsap-scrolltrigger', 'https://unpkg.com/gsap@3.10.4/dist/ScrollTrigger.min.js', array('gsap'), null, true );
	wp_enqueue_script('gsap-scrollto', 'https://unpkg.com/gsap@3.10.4/dist/ScrollToPlugin.min.js', array('gsap'), null, true );
	wp_enqueue_script('deffered', get_stylesheet_directory_uri() . '/assets/js/deferred.js', array('jquery','gsap', 'gsap-scrolltrigger', 'gsap-scrollto'), null, true );
	wp_localize_script( 'main-theme', 'params', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
		'bp23filters'	=> [
			'region' 		=> '',
			'population'	=> '',
			'home_value'	=> ''
		],
		'bp24filters'	=> [
			'region' 		=> '',
			'population'	=> '',
			'home_value'	=> ''
		],
		'bp23page'	=> 1,
		'bp24page' => 1
	) );
}



add_action('wp_enqueue_scripts', 'livability_enqueue_scripts');

function ww_load_dashicons(){
	wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'ww_load_dashicons');

function my_enqueue() {
    // wp_enqueue_script('my_custom_script', get_stylesheet_directory_uri(). '/assets/js/admin.js', array('wp-dom-ready', 'wp-blocks', 'wp-data'));
	wp_enqueue_script('my_custom_script', get_stylesheet_directory_uri(). '/assets/js/admin.js', array('wp-dom-ready'));
}

// add_action('admin_enqueue_scripts', 'my_enqueue');

function dequeue_liv_scripts() {
	wp_dequeue_script('twenty-twenty-one-primary-navigation-script');
	wp_dequeue_script('twenty-twenty-one-ie11-polyfills');
}

add_action('wp_print_scripts', 'dequeue_liv_scripts', 100);

// https://www.minddevelopmentanddesign.com/blog/how-to-defer-parsing-enqueued-javascript-files-wordpress/
function liv_defer_scripts($tag, $handle, $src) {
	$defer = array(
		'deffered',
		'gsap', 
		'gsap-scrolltrigger', 
		'gsap-scrollto'
	);
	if (in_array($handle, $defer)) {
		$tag =  '<script src="' . $src . '" defer="defer"></script>';
	}
	return $tag;
}

add_filter( 'script_loader_tag', 'liv_defer_scripts', 10, 3);

add_action( 'wp_print_styles', 'liv_dequeue_dashicons' );
function liv_dequeue_dashicons() { 
    if ( ! is_user_logged_in() ) {
        wp_dequeue_style( 'dashicons' );
        wp_deregister_style( 'dashicons' );
    }
}

function mind_detect_enqueued_scripts() {
	global $wp_scripts;
	echo "Handles: ";
	foreach( $wp_scripts->queue as $handle ) :
	  echo $handle . ', ';
	endforeach;
  }
//   add_action( 'wp_print_scripts', 'mind_detect_enqueued_scripts' );

function twenty_twenty_one_child_widgets_init() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'Right Sidebar', 'livability' ),
			'id'            => 'sidebar-2',
			'description'   => esc_html__( 'Add widgets here to appear in your right sidebar.', 'livability' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'twenty_twenty_one_child_widgets_init' );

add_editor_style( '/assets/css/style-editor.css' );

if ( ! function_exists('liv_place_post_type') ) {

// Register Custom Post Type
function liv_place_post_type() {

	$labels = array(
		'name'                  => _x( 'Places', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Place', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Places', 'text_domain' ),
		'name_admin_bar'        => __( 'Place', 'text_domain' ),
		'archives'              => __( 'Place Archives', 'text_domain' ),
		'attributes'            => __( 'Add a City to This State', 'text_domain' ),
		'parent_item_colon'     => __( 'Select a state:', 'text_domain' ),
		'all_items'             => __( 'All Places', 'text_domain' ),
		'add_new_item'          => __( 'Add New Place', 'text_domain' ),
		'add_new'               => __( 'Add New Place', 'text_domain' ),
		'new_item'              => __( 'New Place', 'text_domain' ),
		'edit_item'             => __( 'Edit Place', 'text_domain' ),
		'update_item'           => __( 'Update Place', 'text_domain' ),
		'view_item'             => __( 'View Place', 'text_domain' ),
		'view_items'            => __( 'View Places', 'text_domain' ),
		'search_items'          => __( 'Search Places', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into Place', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this place', 'text_domain' ),
		'items_list'            => __( 'Places list', 'text_domain' ),
		'items_list_navigation' => __( 'Places list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter place list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Place', 'text_domain' ),
		'description'           => __( 'A state, city or metro region', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
		'taxonomies'            => array( 'place_topic', 'post_tag' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'show_in_rest'			=> true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'menu_icon'   			=> 'dashicons-location',
	);
	register_post_type( 'liv_place', $args );

}
add_action( 'init', 'liv_place_post_type', 0 );

}

if ( ! function_exists('liv_magazine_post_type') ) {

// Register Custom Post Type
function liv_magazine_post_type() {

	$labels = array(
		'name'                  => _x( 'Magazines', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Magazine', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Magazines', 'text_domain' ),
		'name_admin_bar'        => __( 'Magazine', 'text_domain' ),
		'attributes'            => __( 'Add a Magazine', 'text_domain' ),
		'all_items'             => __( 'All Magazines', 'text_domain' ),
		'add_new_item'          => __( 'Add New Magazine', 'text_domain' ),
		'add_new'               => __( 'Add New Magazine', 'text_domain' ),
		'new_item'              => __( 'New Magazine', 'text_domain' ),
		'edit_item'             => __( 'Edit Magazine', 'text_domain' ),
		'update_item'           => __( 'Update Magazine', 'text_domain' ),
		'view_item'             => __( 'View Magazine', 'text_domain' ),
		'view_items'            => __( 'View Magazines', 'text_domain' ),
		'search_items'          => __( 'Search Magazines', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into Magazine', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this magazine', 'text_domain' ),
		'items_list'            => __( 'Magazines list', 'text_domain' ),
		'items_list_navigation' => __( 'Magazines list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter magazine list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Magazine', 'text_domain' ),
		'description'           => __( 'A magazine published for a place (state, city or metro region)', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 7,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'show_in_rest'			=> true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'menu_icon'   			=> 'dashicons-media-document',
	);
	register_post_type( 'liv_magazine', $args );

}
add_action( 'init', 'liv_magazine_post_type', 0 );

}

if ( ! function_exists('liv_best_places') ) {

// Register Custom Post Type
function liv_best_places() {

	$labels = array(
		'name'                  => _x( 'Best Places', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Best Place', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Best Places', 'text_domain' ),
		'name_admin_bar'        => __( 'Best Place', 'text_domain' ),
		'archives'              => __( 'Best Place Archives', 'text_domain' ),
		'attributes'            => __( 'Add to Best Places List', 'text_domain' ),
		'parent_item_colon'     => __( 'Select a place:', 'text_domain' ),
		'all_items'             => __( 'All Best Places', 'text_domain' ),
		'add_new_item'          => __( 'Add a Best Place', 'text_domain' ),
		'add_new'               => __( 'Add a Best Place', 'text_domain' ),
		'new_item'              => __( 'New Best Place', 'text_domain' ),
		'edit_item'             => __( 'Edit Best Place', 'text_domain' ),
		'update_item'           => __( 'Update Best Place', 'text_domain' ),
		'view_item'             => __( 'View Best Place', 'text_domain' ),
		'view_items'            => __( 'View Best Places', 'text_domain' ),
		'search_items'          => __( 'Search Best Places', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into Place', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Best Place', 'text_domain' ),
		'items_list'            => __( 'Best Places list', 'text_domain' ),
		'items_list_navigation' => __( 'Best Places list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter Best Places list', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                  => 'best-places',
		'with_front'            => false,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Best Place', 'text_domain' ),
		'description'           => __( 'A place belonging to a Best Places list', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions','author','page-attributes'  ),
		'taxonomies'            => array('best_places_taxonomy', 'post_tag'),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 6,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'show_in_rest'			=> true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'				=> $rewrite,
		'capability_type'       => 'post',
		'menu_icon'   			=> 'dashicons-location-alt',
	);
	register_post_type( 'best_places', $args );

}
add_action( 'init', 'liv_best_places', 0 );

}


if ( ! function_exists( 'liv_best_places_years' ) ) {

// Register Custom Taxonomy
function liv_best_places_years() {

	$labels = array(
		'name'                       => _x( 'Best Places Years', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Best Place Year', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Best Places Years', 'text_domain' ),
		'all_items'                  => __( 'All Years', 'text_domain' ),
		'new_item_name'              => __( 'New Year', 'text_domain' ),
		'add_new_item'               => __( 'Add Year', 'text_domain' ),
		'edit_item'                  => __( 'Edit Year', 'text_domain' ),
		'update_item'                => __( 'Update Year', 'text_domain' ),
		'view_item'                  => __( 'View Year', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate years with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove years', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Years', 'text_domain' ),
		'search_items'               => __( 'Search Years', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Best Places Year', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'best_places_years', array( 'best_places', 'liv_place' ), $args );

}
add_action( 'init', 'liv_best_places_years', 0 );

}

if ( ! function_exists( 'liv_content_series' ) ) {

// Register Custom Taxonomy
function liv_content_series() {

	$labels = array(
		'name'                       => _x( 'Content Series', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Content Series', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Content Series', 'text_domain' ),
		'all_items'                  => __( 'All Content Series', 'text_domain' ),
		'new_item_name'              => __( 'New Content Series', 'text_domain' ),
		'add_new_item'               => __( 'Add Content Series', 'text_domain' ),
		'edit_item'                  => __( 'Edit Content Series', 'text_domain' ),
		'update_item'                => __( 'Update Content Series', 'text_domain' ),
		'view_item'                  => __( 'View Content Series', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate years with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Content Series', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Content Series', 'text_domain' ),
		'search_items'               => __( 'Search Content Series', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Content Series', 'text_domain' ),
		'items_list_navigation'      => __( 'Content Series navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'content_series', array( 'post' ), $args );

}
add_action( 'init', 'liv_content_series', 0 );

}




if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'General Settings',
		'menu_title'	=> 'Site Options',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	acf_add_options_page(array(
		'page_title' 	=> 'Local Insights Questions',
		'menu_title'	=> 'Local Insights Questions',
		'parent_slug'	=> 'theme-general-settings',
		'capability'	=> 'edit_posts'
	));
	acf_add_options_page(array(
		'page_title' 	=> 'Connected Community Global Carousel',
		'menu_title'	=> 'Connected Community Global Carousel',
		'parent_slug'	=> 'theme-general-settings',
		'capability'	=> 'edit_posts'
	));
	acf_add_options_page(array(
		'page_title' 	=> 'Internal Promotion Articles',
		'menu_title'	=> 'Int. Articles',
		'parent_slug'	=> 'theme-general-settings',
		'capability'	=> 'edit_posts'
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Contact Info',
		'menu_title'	=> 'Contact Info',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Navigation Settings',
		'menu_title'	=> 'Navigation',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Sponsorships',
		'menu_title'	=> 'Sponsorships',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Best Places 2023 Category Descriptions',
		'menu_title'	=> 'Best Places 2023 Category Descriptions',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Article Announcement',
		'menu_title'	=> 'Article Announcement',
		'parent_slug'	=> 'theme-general-settings',
	));
}

// add_filter('acf/fields/relationship/result', 'jci_add_state_to_city_name', 10, 4);
function jci_add_state_to_city_name( $text, $post, $field, $post_id ) {
	$post_type = get_post_type( $post );
	$parent_ID = $post->post_parent;
	if ($parent_ID !== 0 &&  $post_type === 'liv_place') {
		$parent = get_post($parent_ID);
		$name = $parent->post_name;
		$text = $text . ' ' . $name;
	}
    return $text;
}

add_filter( 'excerpt_length', function($length) {
    return 30;
} );

remove_filter( 'excerpt_more', 'twenty_twenty_one_continue_reading_link_excerpt', 1 );

// function custom_excerpt_more( $more ) {
//     return '';
// }
// add_filter( 'excerpt_more', 'custom_excerpt_more' );


function load_topic_posts() {
	$page = $_POST['page'];
	$offset = $page*15 - 12; // first page is 3, then 15 per page
	$args = array(  
		'category_name'     => $_POST['category'],
		'posts_per_page'    => 15,
		'post_status'       => 'publish',
		'post_type'         => 'post',
		'meta_query'        => array(
			array( 
				'key'       => 'place_relationship',
				'value'     => stripslashes($_POST['relationshipID']),
				'compare'   => 'LIKE'
			)
		),
		// 'paged'             => $_POST['page']
		'offset'			=> $offset
	);
	query_posts($args);
	if(have_posts()): 
		while(have_posts()): the_post();
		$id = get_the_ID();  
		// echo 'the args: ';
		// print_r($args); ?>
	<li class="one-hundred-list-container">
	<a href="<?php echo get_the_permalink($id); ?>" class="ohl-thumb" >
	<!-- <div style="background-image: url(<?php //echo the_post_thumbnail_url($id, 'rel_article'); ?>);"> -->
	<?php echo  get_the_post_thumbnail($id, 'rel_article'); ?>
	<?php echo get_field('sponsored', $id) ? '<p class="sponsored-label ">Sponsored</p>' : ""; ?>
	<!-- </div> -->
	</a>
		<div class="ohl-text">

		<a href="<?php  echo get_the_permalink( ); ?>">
		<?php _e(the_title('<h2>','</h2>'), 'acf_blocks'); 
		the_excerpt();
		?>
		</a>
		</div>
	</li>
		<?php endwhile;
	endif;
	wp_reset_query();
	die();
}



add_action('wp_ajax_loadmore', 'load_topic_posts');
add_action('wp_ajax_nopriv_loadmore', 'load_topic_posts');

function load_place_topics_2() {
	$posts = $_POST['posts'];
	foreach ($posts as $p) { 
		if ($p): ?>
		<div class="place-topics-2__card">
		<a href="<?php echo get_the_permalink($p); ?>">
		
		<div class="place-topics-2__img-container">
			<?php echo get_the_post_thumbnail($p, 'medium'); ?>
		</div>
		 </a>
		 <div class="place-topics-2__text-container">
		<h3 class="place-topics-2__title"><a class="unstyle-link" href="<?php echo get_the_permalink($p); ?>"><?php echo get_the_title($p); ?></a></h3>
		<p class="place-topics-2__excerpt"><?php echo get_the_excerpt( $p ); ?></p>
		 </div>
	</div>
	<?php endif;
	}
	die();
}

add_action('wp_ajax_placeTopics2', 'load_place_topics_2');
add_action('wp_ajax_nopriv_placeTopics2', 'load_place_topics_2');

function load_onehundred_list() {
	// $children = $_POST['children'];
	$parent = $_POST['parent'];
	$page = $_POST['current_page'];
	$args = array( 
        'post_type'     => 'best_places',
        'posts_per_page'=> 10,
        'post_status'   => array('publish','private'),
        'orderby'    => 'meta_value_num',
        'meta_key'  => 'bp_rank',
        'order'     => 'ASC',
        // 'post__in'  => $children,
		'post_parent'	=> $parent,
        'paged'     => $page,
    ); 
	query_posts($args);
	if(have_posts()): 
		while(have_posts()): the_post();
		$ID = get_the_ID(); 
		$place = get_field('place_relationship');
		$population = '';
		if ($place) {
            $population = str_replace(',', '', get_field('city_population', $place[0]));
            $population = intval($population);
        }
		?>
		<li class="one-hundred-list-item"><div class="one-hundred-list-container">
<a href="<?php echo get_the_permalink( ); ?>" class="ohl-thumb" >
<!-- <div style="background-image: url(<?php //echo the_post_thumbnail_url('rel_article'); ?>);"> -->
<?php echo get_the_post_thumbnail( $ID,  'rel_article') ?>
<p class="green-circle with-border"><?php echo get_field('bp_rank'); ?></p>
</a>
<div class="ohl-text">

<a href="<?php  echo get_the_permalink( ); ?>">
<?php _e(the_title('<h2>','</h2>'), 'acf_blocks'); ?>
<h3 class="uppercase">Livscore: <?php echo get_field('ls_livscore'); 
if ($population) {
	echo ' | Population: '.number_format($population);
}?>
</h3>
<?php echo get_the_excerpt(); ?>
</a>
</div>
</div>
</li>

		<?php endwhile; ?>
		<!-- Use page var to create unique ID to display ad -->
		<?php if ($page % 2 == 0) { ?>
		<!-- <div class="wp-block-jci-blocks-ad-area-three" id="ohlloop<?php //echo $page; ?>-2"></div> -->
		<?php } ?>
	<?php endif;
	// wp_reset_postdata();
	die();
}

add_action('wp_ajax_loadonehundred', 'load_onehundred_list');
add_action('wp_ajax_nopriv_loadonehundred', 'load_onehundred_list');

function load_masonry_posts() {
	// print_r($_POST);
	$offset = $_POST['offset'];
	$currentPage = $_POST['current_page'];
	// echo 'current page is '.$currentPage;
	// echo 'current page is '.$_POST['current_page']; 
	$args = array(
		'post_type'         => 'post',
		'cat'               => $_POST['categoryId'],
		'posts_per_page'    => 18,
		'post_status'       => 'publish',
		// 'paged'             => $_POST['current_page'],
		// 'offset'			=> -$offset
	);
	// if ($offset > 0) {
		// $args['paged'] = $_POST['current_page'] -1;
		// $args['offset'] = 6 - $offset;
	// } else {
		$args['paged'] = $_POST['current_page'];
	// }
		// echo 'cp is '.$args['paged'];
	query_posts($args);
	$counter = 1; 
	if(have_posts()): 
		while(have_posts()): the_post();
		$ID = get_the_ID(  );
		switch ($counter) {
			case 1: 
			case 4:
				$count_class = 'fb-sixty';
				break;
			case 2: 
			case 3: 
				$count_class = 'fb-forty';
				break;
			default:
				$count_class = '';
				break;
		} ?>
	<?php	if ($counter % 2 == 1) {echo '<div class="curated-posts-2">';} ?>
    <div class="cp <?php echo $count_class; ?>" style="background-image: url(<?php echo get_the_post_thumbnail_url($ID, 'medium_large'); ?>);" >
    <a href="<?php echo get_the_permalink(); ?>">
    <div class="cp-container">
    <h3><?php echo get_the_title(); ?></h3>
    </div>
    </a>
    </div><!-- cp -->
    <?php if ($counter % 2 == 0) {echo '</div>';} ?>
    <?php $counter < 6 ? $counter++ : $counter = 1;
		endwhile;
	endif;
	wp_reset_query();
	// $currentPage++;
	unset($args);
	die();
}

add_action('wp_ajax_loadmasonry', 'load_masonry_posts');
add_action('wp_ajax_nopriv_loadmasonry', 'load_masonry_posts');

function load_bpm_posts() {
	$currentPage = $_POST['current_page'];

	$args = array(
		'post_type'         => 'best_places',
		'posts_per_page'    => 18,
		'post_status'       => 'publish',
		'post_parent'       => 0,
		'paged'             => $currentPage
	);
	query_posts($args);
	$counter = 1; 
	if(have_posts()): 
		while(have_posts()): the_post();
		$ID = get_the_ID();
		switch ($counter) {
			case 1: 
			case 4:
				$count_class = 'fb-sixty';
				break;
			case 2: 
			case 3: 
				$count_class = 'fb-forty';
				break;
			default:
				$count_class = '';
				break;
		} ?>
	<?php	if ($counter % 2 == 1) {echo '<div class="curated-posts-2">';} ?>
    <div class="cp <?php echo $count_class; ?>" style="background-image: url(<?php echo get_the_post_thumbnail_url($ID, 'medium_large'); ?>);" >
    <a href="<?php echo get_the_permalink(); ?>">
    <div class="cp-container">
    <h3><?php echo get_the_title(); ?></h3>
    </div>
    </a>
    </div><!-- cp -->
    <?php if ($counter % 2 == 0) {echo '</div>';} ?>
    <?php $counter < 6 ? $counter++ : $counter = 1;
		endwhile;
	endif;
	wp_reset_query();
	// $currentPage++;
	unset($args);
	die();
}

add_action('wp_ajax_loadbpm', 'load_bpm_posts');
add_action('wp_ajax_nopriv_loadbpm', 'load_bpm_posts');

function load_more_bpm_posts() {
	if ($_POST['year'] == '2023') {
	if (isset($_COOKIE['bp23_cat'])) {
		$meta_key = $_COOKIE['bp23_cat'];
	} else {
		$meta_key = 'ls_livscore';
	}
	$page = $_POST['page'];
	// run bp_posts query
	
	$bp_args = array(
		'post_type'         => 'best_places',
		'post_status'       => 'any',
		'tax_query'			=> array(
			array(
				'taxonomy'	=> 'best_places_years',
				'field'		=> 'slug',
				'terms'		=> '2023'
			)
		),
		'orderby'           => 'meta_value_num',
		'meta_key'          => $meta_key,
		'order'             => 'DESC',
		'posts_per_page'    => 100
	);
	// create array like other func and filter posts
	$bp_posts = get_posts($bp_args);
		$bp23_array = array();
		// print_r($bp_posts);
		foreach($bp_posts as $key => $bp) {
			$arr = array();
            $arr['id'] = $bp->ID;
			$places = get_field('place_relationship', $bp->ID);
			foreach($places as $p) {
				if (get_field('place_type', $p) == 'city') {
					$city = $p;
				} else {
					$state = $p;
				}
			}
			$cityPopulation = str_replace(',','', get_field('city_population', $city)); 
			$cityHomeValue = str_replace(',','',get_field('city_home_value', $city)); 

			$arr['cityPopulation'] = $cityPopulation;
			$arr['cityHomeValue'] = $cityHomeValue;
			$arr['cityTitle'] = get_the_title($city);
			$arr['stateId'] = $state;

			// filter by region
			if ($_POST['bp23filters']['region']) {
				if ( $_POST['bp23filters']['region'] != strtolower(get_region($state)) ) {
					// unset($bp_posts[$key]);
					$arr = null;
					// echo 'not a match <br>';

				}
			}
			// filter by population
			if ($_POST['bp23filters']['population']) {
				$pop_array = explode("-",$_POST['bp23filters']['population']);
				if ($cityPopulation < $pop_array[0] || $cityPopulation > $pop_array[1]) {
					$arr = null;
				}
			}

			// filter by home value
			if ($_POST['bp23filters']['home_value']) {
				$hv_array = explode("-", $_POST['bp23filters']['home_value']);
				if (intval($cityHomeValue) < intval($hv_array[0]) ||  intval($cityHomeValue) > intval($hv_array[1])) {
					$arr = null;
				}
			}
			if ($arr) {
				$bp23_array[] = $arr;
			}

		}
	// slice array to get next "page"
	$bp23_array = array_slice($bp23_array, $page*20, 20);
	$score_text = $meta_key == 'ls_livscore' ? '' : ' Score';
		foreach($bp23_array as $key => $bp){ ?>
			<div class="bp23-card">
				<?php echo '<a class="bp23-img" href="'.get_the_permalink( $bp['id'] ).'">'.get_the_post_thumbnail( $bp['id'], 'rel_article').'</a>'; ?>
				<div class="bp23-card-text">
                <h3  class="h4"><?php echo '<a href="'.get_the_permalink( $bp['id'] ).'">'.$bp['cityTitle'].'</a>'; ?></h3>
                <p class="meta-sort <?php echo strtolower(display_meta_key($meta_key)); ?>"><strong><?php echo ucfirst(display_meta_key($meta_key)).$score_text.': '.get_field($meta_key, $bp['id']); ?></strong></p>
                <p><?php echo 'Region: '.get_region($bp['stateId']); ?></p>
                <p>Population: <?php echo number_format($bp['cityPopulation']); ?></p>
                <p>Med. Home Value: $<?php echo number_format($bp['cityHomeValue']); ?></p>
            </div>
			<p class="read-more"><a href="<?php echo get_the_permalink( $bp['id'] ); ?>">Read More</a></p>
			</div>
		<?php }
	} else {
		if (isset($_SESSION['bp23_cat'])) {
			$sortBy = $_SESSION['bp23_cat'];
		} else {
			$sortBy = 'livscore';
		}
		$year = $_POST['year'];
		$page = $_POST['page'];
		global $wpdb;
		if ($year == '2024') {
			$results = $wpdb->get_results( "SELECT * FROM 2024_top_100  ORDER BY ".$sortBy." DESC", OBJECT );
		} else { // year is 2025
			$results = $wpdb->get_results( "SELECT * FROM 2025_top_100  ORDER BY ".$sortBy." DESC", OBJECT );
		}
		$bp24_array = array();
		foreach ($results as $key => $value) { 
			$arr = array(); // collect data to use in loop
			// get population and home value from city data table
			if ($year == '2024') {
				$city_data = $wpdb->get_results( "SELECT * FROM 2024_city_data  WHERE place_id = $value->place_id", OBJECT );
			} else {
				$city_data = $wpdb->get_results( "SELECT * FROM 2025_city_data  WHERE place_id = $value->place_id", OBJECT );
			}
			$arr['population'] = $city_data[0]->city_pop;
			$arr['home_value'] = $city_data[0]->avg_hom_val;
			$arr['cat_name'] = $sortBy;
			$arr['cat_val'] = json_decode($value->$sortBy, true);
			$arr['place_id'] = $value->place_id;
			$arr['city'] = $value->city;
			$arr['state'] = $value->state;
			

			// add three filters
			// filter by region
		if ($_POST['bp23filters']['region']) {
			if ( $_POST['bp23filters']['region'] != strtolower(get_region_by_state_name($value->state)) ) {
				$arr = null;
			}
		}

		// filter by population
		if ($_POST['bp23filters']['population']) {
			$pop_array = explode("-",$_POST['bp23filters']['population']);
			if ($arr['population'] < $pop_array[0] || $arr['population'] > $pop_array[1]) {
				$arr = null;
			}
		}

		// filter by home value
		if ($_POST['bp23filters']['home_value']) {
			$hv_array = explode("-", $_POST['bp23filters']['home_value']);
			if (intval($arr['home_value']) < intval($hv_array[0]) ||  intval($arr['home_value']) > intval($hv_array[1])) {
				$arr = null;
			}
		}
			if ($arr) {
				$bp24_array[] = $arr;
			}

			?>
			
		<?php } //end foreach
		$total_posts = count($bp24_array);
		$bp24_array = array_slice($bp24_array, $page*20, 20);
		// foreach loop to display
		foreach ($bp24_array as $key => $value) { 
			$score_text = $value['cat_name'] == 'livscore' ? 'LivScore' : $value['cat_name'].' Score'; 
			// print_r($value);?>
			
			<div class="bp24__card">
			<div class="bp24__img-container" >
			<a href="<?php echo get_the_permalink( $value['place_id']).'?top-100='.$year; ?>">
			<?php echo get_the_post_thumbnail( $value['place_id'], 'medium'); ?>
			</a>
			</div>
			<div class="bp24__text-container">
			<a class="unstyle-link" href="<?php echo get_the_permalink( $value['place_id']).'?top-100='.$year; ?>">
			<h4 class="bp24__city"><?php echo $value['city']; ?></h4>
			</a>
			<p class="bp24__state"><?php echo $value['state']; ?></p>
			<p class="bp24__cat-paragraph"><?php echo ucfirst($score_text).': '.$value['cat_val']; ?></p>
			<p>Region: <?php echo get_region_by_state_name($value['state']); ?></p>
			<p>Population: <?php echo  number_format($value['population']); ?></p>
			<p>Med. Home Value: $<?php echo number_format($value['home_value']); ?></p>
			<p class="bp24__read-more"><a class="unstyle-link" href="<?php echo get_the_permalink( $value['place_id']).'?top-100='.$year;  ?>">Read More</a></p>
			</div>
		</div>
		<?php }
		
	} 
	wp_die();
}
add_action('wp_ajax_loadMorebp23', 'load_more_bpm_posts');
add_action('wp_ajax_nopriv_loadMorebp23', 'load_more_bpm_posts');

add_action('admin_head', 'wp_blocks_fullwidth');

function wp_blocks_fullwidth() {
    echo '<style>
        .wp-block {
            max-width: unset;
         }
    </style>';
}

// States Only Admin Submenu
add_action( 'admin_menu' , 'statesonly' );
function statesonly() {
    global $submenu;
	$url = get_bloginfo('url');
	$submenu['edit.php?post_type=liv_place'][501] = array( 'States Only', 'edit_posts' , $url . '/wp-admin/edit.php?s&post_status=all&post_type=liv_place&ac-rules=%7B%22condition%22%3A%22AND%22%2C%22rules%22%3A%5B%7B%22id%22%3A%22605dc3ab8a7ef8%22%2C%22field%22%3A%22605dc3ab8a7ef8%22%2C%22type%22%3A%22string%22%2C%22input%22%3A%22select%22%2C%22operator%22%3A%22equal%22%2C%22value%22%3A%22state%22%7D%5D%2C%22valid%22%3Atrue%7D&m=0&seo_filter&readability_filter&layout=605dc2e7914db&filter_action=Filter&action=-1&paged=1&action2=-1' ); 
}
// Clients Only Admin Submenu
add_action( 'admin_menu' , 'clientsonly' );
function clientsonly() {
    global $submenu;
	$url = get_bloginfo('url');
	$submenu['edit.php?post_type=liv_place'][501] = array( 'Clients Only', 'edit_posts' , $url . '/wp-admin/edit.php?s&post_status=all&post_type=liv_place&m=0&acp_filter%5B608a80a0d645a0%5D=1&filter_action=Filter&action=-1&paged=1&action2=-1' ); 
}
// Best Places Articles Only Admin Submenu
add_action( 'admin_menu' , 'bponly' );
function bponly() {
    global $submenu;
	$url = get_bloginfo('url');
	$submenu['edit.php?post_type=best_places'][501] = array( 'Best Places Articles', 'edit_posts' , $url . '/wp-admin/edit.php?s&post_status=all&post_type=best_places&ac-rules=%7B%22condition%22%3A%22AND%22%2C%22rules%22%3A%5B%7B%22id%22%3A%22609022eb1b645c%22%2C%22field%22%3A%22609022eb1b645c%22%2C%22type%22%3A%22string%22%2C%22input%22%3A%22select%22%2C%22operator%22%3A%22is_empty%22%2C%22value%22%3Anull%7D%5D%2C%22valid%22%3Atrue%7D&m=0&seo_filter&readability_filter&layout=60685a1fe758e&filter_action=Filter&action=-1&paged=1&action2=-1' ); 
}

add_action('after_setup_theme', 'jci_theme_setup');
function jci_theme_setup() {
	add_image_size( 'portrait' , 420, 560, true );
	add_image_size('rel_article', 475, 260, true );
	add_image_size('three_hundred_wide', 300, 0, true);
	// remove_image_size('medium');
	// remove_image_size('rank_card');
	// remove_image_size('large');
	remove_image_size('alm-thumbnail');
	remove_image_size('alm-cta');
	remove_image_size('alm-gallery');
	remove_image_size( '1536x1536' );
	remove_image_size( '2048x2048' );
}

function my_alm_query_args_my_id($args, $id){  
	
	// $ID = $post->ID;
	$year = get_the_date('Y',$current_post);
	$month = get_the_date('m',$current_post);
	$day = get_the_date('j',$current_post);
	$args['post_type'] = 'post';
	$args['date_query'] = array(
		// 'relation' => 'AND',
		array(
			'before' => array(
				// 'year'  => $year,
				// 'month' => $month,
				// 'day'   => $day
			),
		),
		// array(
		// 	'before' => array(
		// 		'year'  => 2019,
		// 		'month' => 1,
		// 		'day'   => 1
		// 	),
		// )
	);
	return $args;
}
add_filter( 'alm_query_args_my_id', 'my_alm_query_args_my_id', 10, 2);
add_filter( 'alm_debug', '__return_true' );



function get_topic_sponsors() {
	if (get_post_type() == 'liv_place' || get_post_type() == 'place_category_page') {
		$ID = get_the_ID();
		$sponsors = get_field('sponsorships', 'options');
		$sponsor_array = array();
		if ($sponsors) {
			foreach($sponsors as $s) {
				//  print_r($s);
				//  echo '<br />';
				// echo ($s['place'][0]);
				// if (array_key_exists('place', $s)) {
					if (!empty($s['place']) && $s['place'][0] == $ID) {
						$sponsor_array[] = array(
							'sponsor'	=> $s['sponsor'],
							'url'		=> $s['sponsor_url'],
							'category'	=> $s['category'][0]
						);
						// print_r($s['category'][0]);
					}
				// }
			}
			return $sponsor_array;
		}
	}
	
}
// add_action('wp_head','get_topic_sponsors');

function change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Articles';
    $submenu['edit.php'][5][0] = 'Articles';
    $submenu['edit.php'][10][0] = 'Add Article';
    $submenu['edit.php'][15][0] = 'Topics'; // Change name for categories
    // $submenu['edit.php'][16][0] = 'Labels'; // Change name for tags
    echo '';
}

function change_post_object_label() {
        global $wp_post_types;
        $labels = &$wp_post_types['post']->labels;
        $labels->name = 'Articles';
        $labels->singular_name = 'Article';
        $labels->add_new = 'Add Article';
        $labels->add_new_item = 'Add Article';
        $labels->edit_item = 'Edit Article';
        $labels->new_item = 'Article';
        $labels->view_item = 'View Article';
        $labels->search_items = 'Search Articles';
        $labels->not_found = 'No Articles found';
        $labels->not_found_in_trash = 'No Articles found in Trash';
    }
    add_action( 'init', 'change_post_object_label' );
    add_action( 'admin_menu', 'change_post_menu_label' );




// Add meta your meta field to the allowed values of the REST API orderby parameter
add_filter(
    'rest_best_places_collection_params',
    function( $params ) {
        $params['orderby']['enum'][] = 'bp_rank';
        return $params;
    },
    10,
    1
);

// Manipulate query
add_filter(
    'rest_best_places_query',
    function ( $args, $request ) {
        $order_by = $request->get_param( 'orderby' );
        if ( isset( $order_by ) && 'bp_rank' === $order_by ) {
            $args['meta_key'] = $order_by;
            $args['orderby']  = 'meta_value_num'; // user 'meta_value_num' for numerical fields
        }
        return $args;
    },
    10,
    2
);

// Permalink Manager fix for ACF place relationship field
function pm_place_tag($custom_field_value, $custom_field, $post) {
	global $permalink_manager_uris;

	if(!empty($post->post_type) && function_exists('get_field_object') && $custom_field == 'place_relationship') {
		$field_object = get_field_object($custom_field, $post->ID);

		if(!empty($field_object['type']) && $field_object['type'] == 'relationship' && !empty($field_object['value'])) {
			$rel_elements = $field_object['value'];

			if(is_numeric($rel_elements[0])) {
				$rel_post = get_post($rel_elements[0]);
			}

			// Get custom permalink of related post
			if(!empty($rel_post->ID) && !empty($permalink_manager_uris[$rel_post->ID])) {
				$custom_field_value = $permalink_manager_uris[$rel_post->ID];
			}
		}
	}

	return $custom_field_value;
}
add_filter('permalink_manager_custom_field_value', 'pm_place_tag', 5, 3);

function pm_acf_update_uri($value, $post_id, $field, $original) {
    if(!empty($field['name']) && $field['name'] == 'place_relationship') {
		// Get the default URI
        $default_uri = Permalink_Manager_URI_Functions_Post::get_default_post_uri($post_id);
 
        // Save the default URI
        Permalink_Manager_URI_Functions::save_single_uri($post_id, $default_uri, false, true);
	}
	
	return $value;
}
add_filter('acf/update_value', 'pm_acf_update_uri', 10, 4);

function custom_admin_js() {
    echo "<script type=\"text/javascript\">
	
	jQuery(document).ready(function() {
		acf.add_filter('validation_complete', function(json, form) {
		
			setTimeout(function() {
				var pm_container = jQuery('#permalink-manager.postbox');
				var pm_fields = jQuery(pm_container).find('input, select');
				var pm_data = jQuery(pm_fields).serialize() + '&action=' + 'pm_save_permalink';

				jQuery.ajax({
					type: 'POST',
					url: permalink_manager.ajax_url,
					data: pm_data,
					beforeSend: function() {
						pm_reload_pending = true;
					},
					success: function(html) {
						jQuery(pm_container).find('.permalink-manager-gutenberg').replaceWith(html);
						console.log('PM is reloaded');

						pm_reload_pending = false;
					}
				});
			}, 1000);
			
			return json;
		});
	});
	
	</script>";
}
// add_action('admin_footer', 'custom_admin_js');

// Global Aritcles Permalink
function pm_conditional_permastructure($permastructure, $post) {
	// Check if ACF functions are declared
	if(!function_exists('get_field')) {
		return $permastructure;
	}
	
	// Do not change native permalinks or differnt post types
	if($post->post_type !== 'post') {
		return $permastructure;
	}

	// Change the permastructure format if custom field value is equal to 'yes'
	if(get_field("global_article", $post->ID) == 1) {
		$permastructure =  "topics/%category%/%postname%";
	}

	return $permastructure;
}
add_filter('permalink_manager_filter_permastructure', 'pm_conditional_permastructure', 10, 2);

if ( ! function_exists( 'get_attachment_id' ) ) {
    /**
     * Get the Attachment ID for a given image URL.
     *
     * @link   http://wordpress.stackexchange.com/a/7094
     *
     * @param  string $url
     *
     * @return boolean|integer
     */
    function get_attachment_id( $url ) {

        $dir = wp_upload_dir();

        // baseurl never has a trailing slash
        if ( false === strpos( $url, $dir['baseurl'] . '/' ) ) {
            // URL points to a place outside of upload directory
            return false;
        }

        $file  = basename( $url );
        $query = array(
            'post_type'  => 'attachment',
            'fields'     => 'ids',
            'meta_query' => array(
                array(
                    'key'     => '_wp_attached_file',
                    'value'   => $file,
                    'compare' => 'LIKE',
                ),
            )
        );

        // query attachments
        $ids = get_posts( $query );

        if ( ! empty( $ids ) ) {

            foreach ( $ids as $id ) {
				$full_img = wp_get_attachment_image_src( $id, 'full' );
				$full_img_src = array_shift($full_img);
                // first entry of returned array is the URL
                if ( $url === $full_img_src ) {
					return $id;
				}
                    
            }
        }

        $query['meta_query'][0]['key'] = '_wp_attachment_metadata';

        // query attachments again
        $ids = get_posts( $query );

        if ( empty( $ids) )
            return false;

        foreach ( $ids as $id ) {

            $meta = wp_get_attachment_metadata( $id );

            foreach ( $meta['sizes'] as $size => $values ) {
				$image_src = wp_get_attachment_image_src( $id, $size ); 
                // if ( $values['file'] === $file && $url === array_shift( wp_get_attachment_image_src( $id, $size ) ) )
                    // return $id;
				if ( $values['file'] === $file && $url === array_shift( $image_src ) ) return $id; 
            }
        }

        return false;
    }
}

// Add image meta to images in content
function livability_add_image_meta( $content ) {
	$html = str_get_html($content);
	if (get_post_type() == 'post') {
		if (gettype($html) != 'boolean') {
			foreach($html->find('img') as $img){
				 
				$src = $img->src;
				// temp fix for staging url img src until db search/replace can be done 
				// $src = str_replace('//livability.com', '//livability.lndo.site', $src);
				// $post_image_id = attachment_url_to_postid( $src );
				$post_image_id = get_attachment_id( $src );
				$img_byline = get_field('img_byline', $post_image_id);
				// if ($img_byline) { $img_byline = strip_tags($img_byline, "<p>");}
				$img_place_name = get_field('img_place_name', $post_image_id);
				// $img->alt = 'id-'.$post_image_id;
				if ($img_byline || $img_place_name) {
					$outer = '<div class="livability-image-meta">';
					$outer .= $img_place_name ? $img_place_name : '' ;
					$outer .= $img_place_name && $img_byline ? ' / ' : '';
					$outer .= $img_byline ?  strip_tags($img_byline, "<a>") : '' ;
					// $outer .= $img_byline ? $img_byline : '';
					$outer .= '</div>';
					$img->outertext = '<div class="img-container">'.$img->outertext.$outer.'</div>';
				}
			}
		}
		return $html;
	} else {
		return $html;
	}
}

add_filter( 'the_content', 'livability_add_image_meta' );


/**
 * Attachment ID on Images
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/code/add-attachment-id-class-images/
 */
function be_attachment_id_on_images( $attr, $attachment ) {
	if( !strpos( $attr['class'], 'wp-image-' . $attachment->ID ) )
		$attr['class'] .= 'test wp-image-' . $attachment->ID;
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'be_attachment_id_on_images', 10, 2 );


// add_filter('the_content', 'content_test');

// Remove Yoast Premium's Redirect tracking - We're using Redirection
add_filter( 'wpseo_premium_post_redirect_slug_change', '__return_true' );
add_filter( 'wpseo_premium_term_redirect_slug_change', '__return_true' );
add_filter( 'wpseo_enable_notification_post_trash', '__return_false' );
add_filter( 'wpseo_enable_notification_post_slug_change', '__return_false' );
add_filter( 'wpseo_enable_notification_term_delete', '__return_false' );
add_filter( 'wpseo_enable_notification_term_slug_change', '__return_false' );

// Better Search Replace fix for Pantheon Live env
function better_search_replace_cap_override() {
    return 'manage_options';
}
add_filter( 'bsr_capability', 'better_search_replace_cap_override' );


// site instructions: 102507
add_action( 'admin_bar_menu', 'admin_bar_item', 500 );
function admin_bar_item ( WP_Admin_Bar $admin_bar ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    $admin_bar->add_menu( array(
        'id'    => 'menu-id',
        'parent' => null,
        'group'  => null,
        'title' => 'Site Instructions', //you can use img tag with image link. it will show the image icon Instead of the title.
        // 'href'  => admin_url('admin.php?page=custom-page'),
		'href'	=> get_permalink(107277),
        'meta' => [
            'title' => __( 'Site Instructions', 'livability' ), //This title will show on hover
        ]
    ) );
}

function arrange_block_categories( $block_categories, $editor_context) {
	if (! empty($editor_context)) {
		$new_array = [];
		$liv_array = [];
		foreach ($block_categories as $bc) {
			if ($bc['title'] == 'Livability' ) {
				$liv_array[] = $bc;
			} else {
				$new_array[] = $bc;
			}
		}
		array_splice($new_array, 1, 0, $liv_array);
	}
	return $new_array;
}
add_filter('block_categories_all','arrange_block_categories', 10, 2);

if ( is_admin() ) {
    add_action( 'admin_menu', 'add_sponsored_menu', 100 );
}
function add_sponsored_menu() {
	add_submenu_page( 'edit.php', __( 'Sponsored Articles'), __( 'Sponsored Articles' ), 'edit_posts', 'edit.php?ac-actions-form=1&orderby=60c09ed4ef9db4&order=desc&s&post_status=all&post_type=post&ac-rules={"condition"%3A"AND"%2C"rules"%3A[{"id"%3A"60c09ed4ef9db4"%2C"field"%3A"60c09ed4ef9db4"%2C"type"%3A"string"%2C"input"%3A"select"%2C"operator"%3A"equal"%2C"value"%3A"1"}]%2C"valid"%3Atrue}&m=0&cat=0&layout=607080b8d44d4&filter_action=Filter&action=-1&paged=1&action2=-1' );
}

add_action('wp_ajax_findCity', 'load_city_results');
add_action('wp_ajax_nopriv_findCity', 'load_city_results');

function load_city_results() {
	$args = array(
		'post_type'			=> 'liv_place', 
		'post_status'		=> 'publish',
		'posts_per_page'	=> 20,
		's'					=> $_POST['query'],
		'orderby'			=> 'relevance'
	);
	$lcr_query = new WP_Query($args);
	if ($lcr_query->have_posts()): 
		$widget_page = site_url( '/city-data-widget?cityid=');
		echo '<ul class="widget-list">';
		while ($lcr_query->have_posts()): $lcr_query->the_post();
		echo '<li><a href="'.$widget_page.get_the_ID().'">'.get_the_title().'</a></li>';
		endwhile;
		echo '</ul>';
	endif;
	wp_reset_query( );
	die();
}

	add_action( 'wpseo_register_extra_replacements', function() {
		wpseo_register_var_replacement( '%%Related_Place%%', 'get_bp_city', 'advanced', 'Some help text' );
	} );


function get_bp_city() {
	$place_rel = get_field('place_relationship');
	if ($place_rel) {
		$place_str = get_the_title($place_rel[0]);
		if ($place_rel[1]) {$place_str = $place_str.' '.$place_rel[1];}
		return $place_str;
	} else {
		return '';
	}
}

add_filter( 'wp_parsely_metadata', 'filter_parsely_metadata', 10, 3 );
function filter_parsely_metadata( $parsely_metadata, $post, $parsely_options ) {
	if ($post->post_type == 'best_places' && $post->post_parent > 0 ) {
		$meta = get_post_meta($post->ID);
		$years = get_the_terms( $post->ID, 'best_places_years' );
		if (isset($meta['is_top_one_hundred_page'][0])  && is_countable($years) && count($years) > 0 ){
			if ($years[0]->name == '2022') {
				$parsely_metadata['headline'] = get_the_title($post->ID).' Best Places to Live in the US in 2022';
			}
		}
	}
	return $parsely_metadata;
}

// function make_places_csv() {
//     $csvFile = get_stylesheet_directory(  ).'/places.csv';
//     $args = array(
//         'post_type'         => 'liv_place',
//         'post_status'       => 'publish',
//         'posts_per_page'    => -1
//     );
//     $places = new WP_Query($args);
//     if ($places->have_posts()):
// 		ini_set('auto_detect_line_endings', true);
// 		$handle = fopen($csvFile, 'w');
		
//         while ($places->have_posts()): $places->the_post();
//         // echo 'id is '.get_the_id().' and the title is '.get_the_title().'<br />';
// 		// echo 'inside while';
// 		$id = get_the_id();
// 		$title = get_the_title();

// 		fputcsv($handle, array( $id, $title ));
//         endwhile;
// 		fclose($handle);
//     else:
// 		echo 'file issue';
// 	endif;
// 	wp_reset_query(  );

// }

add_editor_style( 'assets/css/style-editor.css' );


function write_static_homepage($post_id) {
	// global $_POST;
	$homepage_obj = get_page_by_title( 'Homepage Output');
	if ($homepage_obj && $homepage_obj->ID == $post_id) {
		$file = ABSPATH.'wp-content/uploads/homepage-content/homepage-output.html';
		// ini_set('auto_detect_line_endings', true);
		$myfile = fopen($file, w);
		$txt = wp_remote_retrieve_body( wp_remote_get( get_permalink( $homepage_obj->ID ) ) );
		$begin = strpos($txt, '<div class="entry-content">') + 27;
		$end = strpos($txt, '</div><!-- .entry-content -->');
		$diff = $end - $begin;
		$newtxt = substr($txt, $begin, $diff);
		fwrite($myfile, $newtxt);
		fclose($myfile);
	}
	

}

// add_action('post_updated', 'write_static_homepage', 10, 3);

function add_place_options($form) {
	foreach($form['fields'] as $field) {
		// error_log(print_r($field, true));
		// error_log('end');
		if ( $field->id != '8' ) {
            continue;
        } 
		$places_array = [];
		$places = get_posts(array('post_type' => 'liv_place', 'numberposts' => '-1', 'post_status' => 'publish', 'order' => 'ASC', 'orderby' => 'title'));
		foreach($places as $place) {
			$p = array('text' => $place->post_title, 'value' => $place->ID);
			array_push($places_array, $p);
		}
		$field->choices = $places_array;
	}
	
	return $form;
}
// add_filter('gform_pre_render_10', 'add_place_options');
// add_filter('gform_pre_validation_10', 'add_place_options');
// add_filter('gform_pre_submission_filter_10', 'add_place_options');
// add_filter('gform_admin_pre_render_10', 'add_place_options');
// add_filter('gform_pre_render_11', 'add_place_options');
// add_filter('gform_pre_validation_11', 'add_place_options');
// add_filter('gform_pre_submission_filter_11', 'add_place_options');
// add_filter('gform_admin_pre_render_11', 'add_place_options');

function add_local_insights_place_options($form) {
	foreach($form['fields'] as $field) {
		if ( $field->id != '26' ) {
            continue;
        } 
		// if url has city parameter, add that as selected choice
		if ($_GET['city']) {
			$field->choices = array($_GET['city']);
			
		} else {
		$places_array = [];
		$places = get_posts(array('post_type' => 'liv_place', 'numberposts' => '-1', 'post_status' => 'publish', 'order' => 'ASC', 'orderby' => 'title'));
		foreach($places as $place) {
			$p = array('text' => $place->post_title, 'value' => $place->ID);
			array_push($places_array, $p);
		}
		$field->choices = $places_array;
		}
	}
	
	return $form;
}
// add_filter('gform_pre_render_10', 'add_local_insights_place_options');
// add_filter('gform_pre_validation_10', 'add_local_insights_place_options');
// add_filter('gform_pre_submission_filter_10', 'add_local_insights_place_options');
// add_filter('gform_admin_pre_render_10', 'add_local_insights_place_options');

if ( ! function_exists('local_insights') ) {

	// Register Custom Post Type
	function local_insights() {
	
		$labels = array(
			'name'                  => _x( 'Local Insights', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Local Insight', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Local Insights', 'text_domain' ),
			'name_admin_bar'        => __( 'Local Insight', 'text_domain' ),
			'archives'              => __( 'Local Insight Archives', 'text_domain' ),
			'attributes'            => __( 'Add to Local Insights List', 'text_domain' ),
			'parent_item_colon'     => __( 'Select a place:', 'text_domain' ),
			'all_items'             => __( 'All Local Insights', 'text_domain' ),
			'add_new_item'          => __( 'Add a Local Insight', 'text_domain' ),
			'add_new'               => __( 'Add a Local Insight', 'text_domain' ),
			'new_item'              => __( 'New Local Insight', 'text_domain' ),
			'edit_item'             => __( 'Edit Local Insight', 'text_domain' ),
			'update_item'           => __( 'Update Local Insight', 'text_domain' ),
			'view_item'             => __( 'View Local Insight', 'text_domain' ),
			'view_items'            => __( 'View Local Insights', 'text_domain' ),
			'search_items'          => __( 'Search Local Insights', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into Place', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Local Insight', 'text_domain' ),
			'items_list'            => __( 'Local Insights list', 'text_domain' ),
			'items_list_navigation' => __( 'Local Insights list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter Local Insights list', 'text_domain' ),
		);
		// $rewrite = array(
		// 	'slug'                  => 'best-places',
		// 	'with_front'            => false,
		// 	'pages'                 => true,
		// 	'feeds'                 => true,
		// );
		$args = array(
			'label'                 => __( 'Local Insight', 'text_domain' ),
			'description'           => __( 'Local realtor impression of current city', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
			'taxonomies'            => array('local_insight_source_taxonomy', 'post_tag'),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 10,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'show_in_rest'			=> true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			// 'rewrite'				=> $rewrite,
			'capability_type'       => 'post',
			'menu_icon'   			=> 'dashicons-location-alt',
		);
		register_post_type( 'local_insights', $args );
	
	}
	add_action( 'init', 'local_insights', 0 );
	
	}

if ( ! function_exists( 'liv_local_insight_source_taxonomy' ) ) {
	// Register Custom Taxonomy
function liv_local_insight_source_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Sources', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Source', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Sources', 'text_domain' ),
		'all_items'                  => __( 'All Sources', 'text_domain' ),
		'new_item_name'              => __( 'New Source', 'text_domain' ),
		'add_new_item'               => __( 'Add Source', 'text_domain' ),
		'edit_item'                  => __( 'Edit Source', 'text_domain' ),
		'update_item'                => __( 'Update Source', 'text_domain' ),
		'view_item'                  => __( 'View Source', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate Sources with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Sources', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Sources', 'text_domain' ),
		'search_items'               => __( 'Search Sources', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( ' Sources', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'local_insight_source_taxonomy', array( 'local_insights' ), $args );

}
add_action( 'init', 'liv_local_insight_source_taxonomy', 0 );

}




	function add_city_to_local_insight($post_id, $feed, $entry, $form ) {
		// error_log('in error log now');
		$meta = get_post_meta( $post_id );
		// error_log(print_r($meta, true));
		$new_opp = str_replace('<span>CITY, STATE</span>', $city, $meta['q_opportunities']);
		$new_area = str_replace('<span>CITY, STATE</span>', $city, $meta['q_area']);
		$new_lv = str_replace('<span>CITY, STATE</span>', $city, $meta['q_local_vibe']);
		update_post_meta( $post_id, 'q_opportunities', $new_opp );
		update_post_meta( $post_id, 'q_area', $new_area );
		update_post_meta( $post_id, 'q_local_vibe', $new_lv );
	}
	add_action( 'gform_advancedpostcreation_post_after_creation_10', 'add_city_to_local_insight', 10, 4 );

	
	function load_bp_23(){
		$meta_key = $_POST['cat']; 
		setcookie('bp23_cat', $meta_key, time()+3600);
		// $filters = $_POST['bp23filters'];
		if ($_POST['year'] == '2023'):
		$bp_args = array(
			'post_type'         => 'best_places',
			'post_status'       => 'any',
			'tax_query'			=> array(
				array(
					'taxonomy'	=> 'best_places_years',
					'field'		=> 'slug',
					'terms'		=> '2023'
				)
			),
			'orderby'           => 'meta_value_num',
			'meta_key'          => $meta_key,
			'order'             => 'DESC',
			'posts_per_page'    => 100
		);
		// add filters to args here. 
		$bp_posts = get_posts($bp_args);
		$bp23_array = array();
		// print_r($bp_posts);
		foreach($bp_posts as $key => $bp) {
			$arr = array();
            $arr['id'] = $bp->ID;
			$places = get_field('place_relationship', $bp->ID);
			foreach($places as $p) {
				if (get_field('place_type', $p) == 'city') {
					$city = $p;
				} else {
					$state = $p;
				}
			}
			$cityPopulation = str_replace(',','', get_field('city_population', $city)); 
			$cityHomeValue = str_replace(',','',get_field('city_home_value', $city)); 

			$arr['cityPopulation'] = $cityPopulation;
			$arr['cityHomeValue'] = $cityHomeValue;
			$arr['cityTitle'] = get_the_title($city);
			$arr['stateId'] = $state;

			// filter by region
			if ($_POST['bp23filters']['region']) {
				if ( $_POST['bp23filters']['region'] != strtolower(get_region($state)) ) {
					$arr = null;
				}
			}
			// filter by population
			if ($_POST['bp23filters']['population']) {
				$pop_array = explode("-",$_POST['bp23filters']['population']);
				if ($cityPopulation < $pop_array[0] || $cityPopulation > $pop_array[1]) {
					$arr = null;
				}
			}
			// filter by home value
			if ($_POST['bp23filters']['home_value']) {
				$hv_array = explode("-", $_POST['bp23filters']['home_value']);
				if (intval($cityHomeValue) < intval($hv_array[0]) ||  intval($cityHomeValue) > intval($hv_array[1])) {
					$arr = null;
				}
			}
			if ($arr) {
				$bp23_array[] = $arr;
			}
		}
		
		// store posts in session variable 
		// $_SESSION['bp23_array'] = $bp23_array;
		// load the first 20 posts
		$bp23_array = array_slice($bp23_array, 0, 20);
		if (count($bp23_array) == 0 ) {
			echo '<p>No posts found. </p>';
		} else {
			$score_text = $meta_key == 'ls_livscore' ? '' : ' Score';
		foreach($bp23_array as $key => $bp){ ?>
			<div class="bp23-card">
				<?php echo '<a class="bp23-img" href="'.get_the_permalink( $bp['id'] ).'">'.get_the_post_thumbnail( $bp['id'], 'rel_article').'</a>'; ?>
				<div class="bp23-card-text">
                <h3  class="h4"><?php echo $bp['cityTitle']; ?></h3>
                <p class="meta-sort <?php echo strtolower(display_meta_key($meta_key)); ?>"><strong><?php echo ucfirst(display_meta_key($meta_key)).$score_text.': '.get_field($meta_key, $bp['id']); ?></strong></p>
                <p><?php echo 'Region: '.get_region($bp['stateId']); ?></p>
                <p>Population: <?php echo number_format($bp['cityPopulation']); ?></p>
                <p>Med. Home Value: $<?php echo number_format($bp['cityHomeValue']); ?></p>
            </div>
			<p class="read-more"><a href="<?php echo get_the_permalink( $bp['id'] ); ?>">Read More</a></p>
			</div>
		<?php }
		}
		// ad goes here. 
		// die();
		else:
		// else:
			global $wpdb;
			$sortBy = $_POST['cat'] ? $_POST['cat'] : 'livscore'; 
			$_SESSION["bp23_cat"] = $sortBy;
			$year = $_POST['year'];
			// echo 'sort by '.$sortBy;
			if ($year == '2024') {
				$results = $wpdb->get_results( "SELECT * FROM 2024_top_100  ORDER BY ".$sortBy." DESC", OBJECT );
			} else {
				$results = $wpdb->get_results( "SELECT * FROM 2025_top_100  ORDER BY ".$sortBy." DESC", OBJECT );
			}
			$bp24_array = array();
			foreach ($results as $key => $value) { 
				$arr = array(); // collect data to use in loop
				// get population and home value from city data table
				if ($year == '2024') {
					$city_data = $wpdb->get_results( "SELECT * FROM 2024_city_data  WHERE place_id = $value->place_id", OBJECT );
				} else {
					$city_data = $wpdb->get_results( "SELECT * FROM 2025_city_data  WHERE place_id = $value->place_id", OBJECT );
				}
				
				$arr['population'] = $city_data[0]->city_pop;
				$arr['home_value'] = $city_data[0]->avg_hom_val;
				$arr['cat_name'] = $sortBy;
				$arr['cat_val'] = json_decode($value->$sortBy, true);
				$arr['place_id'] = $value->place_id;
				$arr['city'] = $value->city;
				$arr['state'] = $value->state;
				

				// add three filters
				// filter by region
			if ($_POST['bp23filters']['region']) {
				if ( $_POST['bp23filters']['region'] != strtolower(get_region_by_state_name($value->state)) ) {
					$arr = null;
				}
			}

			// filter by population
			if ($_POST['bp23filters']['population']) {
				$pop_array = explode("-",$_POST['bp23filters']['population']);
				if ($arr['population'] < $pop_array[0] || $arr['population'] > $pop_array[1]) {
					$arr = null;
				}
			}

			// filter by home value
			if ($_POST['bp23filters']['home_value']) {
				$hv_array = explode("-", $_POST['bp23filters']['home_value']);
				if (intval($arr['home_value']) < intval($hv_array[0]) ||  intval($arr['home_value']) > intval($hv_array[1])) {
					$arr = null;
				}
			}
				if ($arr) {
					$bp24_array[] = $arr;
				}

				?>
				
			<?php } //end foreach
			$total_posts = count($bp24_array);
			$bp24_array = array_slice($bp24_array, 0, 20);
			// foreach loop to display
			echo '<p class="bp24__total-posts">We found '.$total_posts.' cities based on your filter selection.</p><br />';
			foreach ($bp24_array as $key => $value) { 
				$score_text = $value['cat_name'] == 'livscore' ? 'LivScore' : $value['cat_name'].' Score'; 
				// print_r($value);?>
				
				<div class="bp24__card">
				<div class="bp24__img-container" >
				<a href="<?php echo get_the_permalink( $value['place_id']).'?top-100=2024'; ?>">
				<?php echo get_the_post_thumbnail( $value['place_id'], 'medium'); ?>
				</a>
				</div>
				<div class="bp24__text-container">
				<a class="unstyle-link" href="<?php echo get_the_permalink( $value['place_id']).'?top-100=2024'; ?>">
				<h4 class="bp24__city"><?php echo $value['city']; ?></h4>
			</a>
				<p class="bp24__state"><?php echo $value['state']; ?></p>
				<p class="bp24__cat-paragraph"><?php echo ucfirst($score_text).': '.$value['cat_val']; ?></p>
				<p>Region: <?php echo get_region_by_state_name($value['state']); ?></p>
				<p>Population: <?php echo  number_format($value['population']); ?></p>
				<p>Med. Home Value: $<?php echo number_format($value['home_value']); ?></p>
				<p class="bp24__read-more"><a class="unstyle-link" href="<?php echo get_the_permalink( $value['place_id']).'?top-100=2024';  ?>">Read More</a></p>
				</div>
			</div>
			<?php }
			// die();
		endif; // if year is 23 or 24
		die();
	}

	add_action('wp_ajax_loadbp23', 'load_bp_23');
	add_action('wp_ajax_nopriv_loadbp23', 'load_bp_23');

	function add_img_byline(){
		$attachmentId = $_POST['attachmentId'];
		$img_byline = get_field('img_byline', $attachmentId);
		$img_place_name = get_field('img_place_name', $attachmentId);
		if ($img_byline || $img_place_name) {
			echo '<div class="livability-image-meta">';
			echo $img_place_name ? $img_place_name : '' ;
			echo $img_place_name && $img_byline ? ' / ' : '';
			echo $img_byline ?  strip_tags($img_byline, "<a>") : '' ;
			echo '</div>';
		}
		wp_die();
	}

	add_action('wp_ajax_addImgByline', 'add_img_byline');
	add_action('wp_ajax_nopriv_addImgByline', 'add_img_byline');

	add_filter( 'gform_enable_credit_card_field', 'enable_creditcard', 11 );
	function enable_creditcard( $is_enabled ) {
	return true;
	}

	// Advanced Ads Label changes for GA4
	add_filter( 'advanced-ads-tracking-ga-click', function(){
	return 'Livability Ad Clicks';
	} );
	add_filter( 'advanced-ads-tracking-ga-impression', function(){
	return 'Livability Ad Impressions';
	} );
	

// remove Woo Zoom
add_action( 'after_setup_theme', 'bc_remove_magnifier', 100 ); function bc_remove_magnifier() { remove_theme_support( 'wc-product-gallery-zoom' ); }


// ADD THIS TO REPO ... removed checkout functionality while developing
add_action( 'template_redirect', 'redirect_to_cart' );
function redirect_to_cart() {
    if ( is_checkout() && ! is_wc_endpoint_url() ) {
        wp_redirect( wc_get_cart_url() );
        exit;
    }
}

function single_query($args, $id ) {
	$places = get_field('place_relationship'); 
	$cat = get_the_categories();
	if ($places) {
		$implode_places = implode(',', $places);
		$args['meta_query'] = array(
			array(
				'key'		=> 'place_relationship',
				'value'		=> $implode_places,
				'compare'	=> 'IN'
			)
		);
	} 
	// if ( ! empty( $categories ) ) {
	// 	$args['cat'] = $categories[0]->ID;
	// }
	return $args;
}

// add_filter('alm_query_args_custom_single_query', 'single_query', 10, 2);

add_filter( 'alm_debug', '__return_true' );

function place_data_shortcode($atts, $content = null) {
	$a = shortcode_atts( array(
		'data'		=> ''
	), $atts );
	$id = get_the_ID();
	$data = $a['data'];
	switch ($variable) {
		case 'value':
			# code...
			break;
		
		default:
			# code...
			break;
	}
	$html = '<span>id is '.$id.' and data is '.$a['data'].'</span>';
	return $html;

}

add_shortcode( 'place_data', 'place_data_shortcode' );

// Track Best Place Years in GA4
add_action('wp_head', function () {
    // Only run on singular pages of best_places or liv_place post types
    if (is_singular(['best_places', 'liv_place']) && taxonomy_exists('best_places_years')) {
        $terms = get_the_terms(get_the_ID(), 'best_places_years');
        if ($terms && !is_wp_error($terms)) {
            $term = reset($terms); // Get the first term
            $term_name = esc_js($term->name); // Escape for JavaScript
            ?>
            <script>
                // Wait for gtag to be available
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof gtag === 'function') {
                        // Set custom dimension for the pageview
                        gtag('set', 'user_properties', {
                            'best_places_years': '<?php echo $term_name; ?>'
                        });
                        // Send pageview with custom dimension
                        gtag('event', 'page_view', {
                            'best_places_years': '<?php echo $term_name; ?>'
                        });
                        console.log('GA4: Tracked best_places_years = <?php echo $term_name; ?> for post type <?php echo esc_js(get_post_type()); ?>');
                    } else {
                        console.warn('GA4: gtag not found');
                    }
                });
            </script>
            <?php
            // Log for debugging
            error_log('GA4 Debug: Tracked best_places_years = ' . $term_name . ' for post ID ' . get_the_ID() . ' (post type: ' . get_post_type() . ')');
        }
    }
}, 20);

function load_insights() {
	$insights = $_POST['insights'];
	foreach ($insights as $key => $value) {
		$hide_class = $key != 0 ? ' insight-hidden' : '';
		$f_name = get_field('first_name', $value);
		$l_name = get_field('last_name', $value);
		$company = get_field('company', $value);
		$title = get_field('title', $value);
		$slash = $company && $title ? ' / ' : '';
		$email = get_field('email', $value);
		$contact_link = get_field('contact_link', $value) ? '<a href="'.get_field('contact_link', $value).'" class="insights-link" target="_blank">Website</a>' : '';
		$phone = get_field('phone', $value) ? '<a class="insights-phone" href="tel:'.get_field('phone', $value).'">'.get_field('phone', $value).'</a>' : '' ;
		$q_opportunities = get_field('q_opportunities', $value);
		$a_opportunities = get_field('a_opportunities', $value);
		$q_area = get_field('q_area', $value);
		$a_area = get_field('a_area', $value);
		$q_local_vibe = get_field('q_local_vibe', $value);
		$a_local_vibe = get_field('a_local_vibe', $value);
		?>

		<div class="insights <?php echo $hide_class; ?>">
		<div class="insights-header">
		<div class="insights-img-container">
	<?php echo get_the_post_thumbnail( $value, 'thumbnail', array('class' => 'insights-img')); ?>
	</div> <!--img-container -->
	<div>
	  <h2 class="insights-title"><?php echo $f_name.' '.$l_name; ?></h2>  
	  <span class="insights-name"><?php echo $title.$slash.$company; ?></span>
	</div>
	</div><!--insights header -->
	
<?php if ($a_opportunities): ?>
	<h2 class="insights-q">Q. <?php echo $q_opportunities; ?></h2>
	<p class="insights-a"><span class="insights-q">A.</span> <?php echo $a_opportunities; ?></p>
<?php endif; ?>
<?php if ($a_area): ?>
	<h2 class="insights-q">Q. <?php echo $q_area; ?></h2>
	<p class="insights-a"><span class="insights-q">A.</span> <?php echo $a_area; ?></p>
<?php endif; ?>
<?php if ($a_local_vibe): ?>
	<h2 class="insights-q">Q. <?php echo $q_local_vibe; ?></h2>
	<p class="insights-a"><span class="insights-q">A.</span> <?php echo $a_local_vibe; ?></p>
<?php endif; ?>
<div class="insights-contact-section">
	<p class="insights-connect-with"><strong>Connect With <?php echo $f_name;?>:</strong></p>
	<div class="insights-contact-lower-section">
	<p style="margin-top: 0"><?php echo ' '.$phone.' '.$contact_link; ?></p>
<?php $rows = get_field('social_profile', $value); // social_platform, social_url
if( $rows ) {

   echo '<ul class="insights-social">';
   foreach( $rows as $row ) {
	   $platform = $row['social_platform'];
	   $url = $row['social_url'];
	   switch ($platform) {
		case 'facebook':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#164988" d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z"/></svg>';
			break;
		case 'instagram':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#164988" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>';
			break;
		case 'twitter':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path  fill="#164988" d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>';
			break;
		case 'linkedin':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#164988" d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>';
			break;               
		default:
			$svg = null;
			break;
	   }
	   echo '<li><a href="'.$url.'">'.$svg.'</a></li>';
   }
   echo '</ul>';
}

?> 
</div> <!--insights-contact-lower-section -->
</div> <!--insights-contact-section -->
</div>
	<?php }
	die();
}

add_action('wp_ajax_loadInsights', 'load_insights');
add_action('wp_ajax_nopriv_loadInsights', 'load_insights');

function load_tn_mym_posts() {
	$tnpostargs = array(
		'numberposts'       => 3,
		'post_type'         => 'post',
		'tag'				=> 'fbitn',
		'orderby'           => 'rand',
	);
	$tnposts = get_posts($tnpostargs);
	if ( !empty($tnposts)) {
		echo '<h2 class="wp-block-heading green-line">Make Your Move to Tennessee</h2>';
		echo '<p class="brand-stories__sponsor-text" style="margin-top: -30px !important; margin-bottom: 30px;">Sponsored by <a href="https://www.fbitn.com/">Farm Bureau Insurance of Tennessee</a></p>';
		echo '<div class=" tn-mym">';
		foreach ($tnposts as $key => $value) {
			$ID = $value->ID;
			// $slidebkgrnd = get_the_post_thumbnail_url( $ID, 'rel_article' ); 
			echo '<div class="tn-mym__item"><a class="unstyle-link" href="'.get_the_permalink( $ID ).'">';
			echo '<div class="tn-mym__img-container object-fit-image">'.get_the_post_thumbnail( $ID, 'medium_large').'</div>';
			echo '<h4 class="tn-mym__title">'.get_the_title($ID).'</h4>';
			echo '</a></div>';
		}
		echo '</div>'; // pwl-container
	}
	wp_die();
}

add_action('wp_ajax_loadtnmymposts', 'load_tn_mym_posts');
add_action('wp_ajax_nopriv_loadtnmymposts', 'load_tn_mym_posts');

add_filter( 'wpseo_canonical', 'my_custom_canonical_no_slash', 999 ); // High priority
function my_custom_canonical_no_slash( $canonical_url ) {
    // Ensure it's not the homepage or a root URL where a slash is often default and harmless
    if ( ! empty( $canonical_url ) && $canonical_url !== home_url('/') ) {
        return untrailingslashit( $canonical_url );
    }
    return $canonical_url;
}

// function wp_rocket_add_purge_posts_to_author() {
// 	$role = get_role('advanced_ads_admin');
// 	$role->add_cap('rocket_purge_posts', true);
// 	$role->add_cap('rocket_purge_cache', true); // Required for purge functionality
// 	}
// add_action('init', 'wp_rocket_add_purge_posts_to_author', 12);

	// Register Custom Post Type
	if ( ! function_exists('place_category_page') ) {
	function place_category_page() {
	
		$labels = array(
			'name'                  => _x( 'Place Category Pages', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Place Category Page', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Place Category Pages', 'text_domain' ),
			'name_admin_bar'        => __( 'Place Category Page', 'text_domain' ),
			'archives'              => __( 'Place Category Page Archives', 'text_domain' ),
			'attributes'            => __( 'Add to Place Category Pages List', 'text_domain' ),
			'parent_item_colon'     => __( 'Select a place:', 'text_domain' ),
			'all_items'             => __( 'All Place Category Pages', 'text_domain' ),
			'add_new_item'          => __( 'Add a Place Category Page', 'text_domain' ),
			'add_new'               => __( 'Add a Place Category Page', 'text_domain' ),
			'new_item'              => __( 'New Place Category Page', 'text_domain' ),
			'edit_item'             => __( 'Edit Place Category Page', 'text_domain' ),
			'update_item'           => __( 'Update Place Category Page', 'text_domain' ),
			'view_item'             => __( 'View Place Category Page', 'text_domain' ),
			'view_items'            => __( 'View Place Category Pages', 'text_domain' ),
			'search_items'          => __( 'Search Place Category Pages', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into Place', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Place Category Page', 'text_domain' ),
			'items_list'            => __( 'Place Category Pages list', 'text_domain' ),
			'items_list_navigation' => __( 'Place Category Pages list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter Place Category Pages list', 'text_domain' ),
		);
		// $rewrite = array(
		// 	'slug'                  => 'best-places',
		// 	'with_front'            => false,
		// 	'pages'                 => true,
		// 	'feeds'                 => true,
		// );
		$args = array(
			'label'                 => __( 'Place Category Page', 'text_domain' ),
			'description'           => __( 'Category page for places', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes'),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 10,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'show_in_rest'			=> true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'taxonomies'			=> array( 'category' ),
			// 'rewrite'				=> $rewrite,
			'capability_type'       => 'page',
			'menu_icon'   			=> 'dashicons-location-alt',
		);
		register_post_type( 'place_category_page', $args );
	}
	add_action( 'init', 'place_category_page', 0 );
	
	}
	function make_place_category_pages() {
		add_meta_box( 'add_place_cat_page', __( 'Place Category Pages' , 'livability' ), 'make_pc_pages_callback', 'liv_place');
	}
	function make_pc_pages_callback($post) {
		$place_ID = $post->ID;
		$all_cats = get_terms(['taxonomy' => 'category']);
		$cp_args = array(
			'post_type'		=> 'place_category_page',
			'post_status'	=> array('publish', 'draft'),
			'meta_query'        => array(
				array( 
					'key'       => 'place_relationship',
					'value'     => $place_ID,
					'compare'   => 'LIKE'
				)
			),
			'numberposts'	=> 100	
		);
		$current_posts = get_posts($cp_args); 
		// echo 'place id is '.$place_ID;
		// echo '<pre>';
		// print_r($current_posts);
		// echo '</pre>';
		// foreach ($current_posts as $cp ) {
		// 	$p = get_post_meta($cp->ID, 'place_relationship');
		// 	echo 'title is '.$cp->post_title.' and place id is '.$p[0].'<br />';
		// }
		foreach ($all_cats as $cat) { 
			$has_pcp = false;
			if ($current_posts) {
				foreach ($current_posts as $cp) {
					if (has_term($cat->term_id, 'category', $cp->ID)) {
						$has_pcp = true;
						// echo '<p><a href="'.get_the_permalink($cp->ID).'">'.$cat->name.'</a></p>';
						echo edit_post_link( $cat->name, '<p>', '</p>', $cp->ID);
						break;
					}
				}
			}
			if ($has_pcp == false) {
			?>
				<p><input type="checkbox" id="<?php echo $cat->slug; ?>" name="<?php echo $cat->slug ?>" value="<?php echo $cat->term_id; ?>">
				<label for="" ><?php echo $cat->name; ?></label></p>
		<?php }
		}
		?>
		
	<?php 
		// echo 'id is '.$post->ID;
		// get all place category pages of this place
		// loop through categories
		// if category matches pcp, create a link
		// wp_insert_post with array including post_category (id) and meta_input
		// if not matching, create checkbox to run function on save
		// 

	}
	add_action( 'add_meta_boxes', 'make_place_category_pages');

	function save_place_category_pages( $post_id) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}
		$all_cats = get_terms(['taxonomy' => 'category']);
		foreach ($all_cats as $cat) {
			// $cat_id = $cat->term_id;
			if ( array_key_exists( $cat->slug, $_POST ) ) {
				$insert_args = array(
					'post_category'		=> array($cat->term_id),
					'post_type'			=> 'place_category_page',
					'post_title'		=> get_the_title($post_id).', '.$cat->name, 
					// 'post_name'			=> get_the_title($post_id).' '.$cat->name, 
					'post_status'		=> 'draft',  
					'meta_input'		=> array('place_relationship' => $post_id) 
				);
				remove_action( 'save_post_liv_place', 'save_place_category_pages'); // to avoid infinite loop
				wp_insert_post( $insert_args, true, true );
				add_action( 'save_post_liv_place', 'save_place_category_pages');
			}
		}
	}

	add_action( 'save_post_liv_place', 'save_place_category_pages');

	function return_sponsor_list() {
		$ac_objects = array();
		$sponsor_args = array(
            'post_type'			=> 'post',
            'meta_key'			=> 'sponsored',
            'meta_value'		=> true,
            'posts_per_page'	=> -1,
            'post_status'		=> array('publish', 'draft')
        );
        $sponsor_query = new WP_Query($sponsor_args);
		if ($sponsor_query->have_posts()) {
			while ($sponsor_query->have_posts()) {
				$sponsor_query->the_post();
				$place = get_field('place_relationship');
				if ($place) { 
					// foreach ($ac_objects as $ac) {
					// 	if ($array_key_exists($place[0], $ac)) {
					// 		// break;
					// 	}
					// }
						$ac_objects[$place[0]] = get_the_title($place[0]);
				}
			}
		}
		wp_send_json($ac_objects);
		die();
	}

	add_action('wp_ajax_createSponsorList', 'return_sponsor_list');
	add_action('wp_ajax_nopriv_createSponsorList', 'return_sponsor_list');





	function filter_sponsors() {
		$statusFilter = $_POST['status'];
		$placeFilter = $_POST['place'];
		$orderbyFilter = $_POST['orderby'];
		// $sponsor_args = array(
        //     'post_type'			=> 'post',
        //     'meta_key'			=> 'sponsored',
        //     'meta_value'		=> true,
        //     'posts_per_page'	=> -1,
        //     'post_status'		=> array('publish', 'draft')
        // );
        // $sponsor_query = new WP_Query($sponsor_args); 

        // $sponsor_posts = get_posts($sponsor_args);
        // $sponsor_array = array();
        // if ($sponsor_query->have_posts()):
        //     while ($sponsor_query->have_posts()):
        //         $sponsor_query->the_post();
     
        //     $ID = get_the_ID();
        //     $places = get_post_meta( $ID, 'place_relationship', true );
		// 	$temp_array = array();
        //     $temp_array['title'] = get_the_title();
        //     $temp_array['thumb_url'] = get_the_post_thumbnail_url( $ID, 'rel_article');
        //     $temp_array['thumb'] = get_the_post_thumbnail( $ID, 'rel_article');
        //     $temp_array['permalink'] = get_the_permalink( );
        //     $temp_array['place'] = $places ? $places[0] : '';
        //     $temp_array['place_name'] = $places ? get_the_title($places[0]) : '';
        //     $temp_array['status'] = get_post_status();
        //     $temp_array['sponsor_name'] = get_post_meta( $ID, 'sponsor_name', true );
        //     $temp_array['sponsor_url'] = get_post_meta( $ID, 'sponsor_url', true );
        //     $temp_array['post_time'] = get_post_time('U', true, $ID);
        //     $temp_array['expire_time'] = do_shortcode( '[futureaction type=date dateformat="U"]');
        //     $sponsor_array[] = $temp_array;
        //     endwhile;
        // endif;
		
		$filtered_sponsors = array();
		// $ordered_sponsors = array();
		// foreach ($sponsors as $key => $value) {
		// 	// echo '<br />status is '.$value['status'].' filter is '.$statusFilter;
		// 	if ($statusFilter && statusFilter != 'all') {
		// 		if ($statusFilter == $value['status']) {
		// 			$filtered_sponsors[] = $value;
		// 		}
		// 	}
		// }
		// error_log('status filter is '.$statusFilter);

		
		// $temp_array = array();

		$sponsor_array = get_transient( 'sponsor_array' );
	
		foreach ($sponsor_array as $key => $value) {
			$selected = true;
			if ($statusFilter && $statusFilter != 'all') {
				$statusFilter != $value['status'] ? $selected = false : ''; 
			}
			if ($placeFilter) {
				$placeFilter != $value['place'] ? $selected = false :'' ;
			}
			if ($selected) {$filtered_sponsors[] = $value;}
		}
		
		// $filtered_sponsors[] = array('my key' => 'my value');
		
		// error_log('sponsors');
		// error_log(var_dump($sponsors));
		// error_log('filtered sponsors');
		// error_log(print_r($filtered_sponsors, true));
		if ($orderbyFilter) {
			switch($orderbyFilter) {
				case 'publish-desc': 
					$filtered_sponsors = array_reverse($filtered_sponsors);
				break;
				case 'sponsor-asc':
					usort($filtered_sponsors, function($a, $b) use ($orderbyFilter){
						if ($a['sponsor_name'] == $b['sponsor_name']) {
							return 0;
						}
						return ($a['sponsor_name'] < $b['sponsor_name']) ? -1 : 1;
					});
					// $filtered_sponsors = $ordered_sponsors;
				break;
				case 'sponsor-desc':
					usort($filtered_sponsors, function($a, $b) use ($orderbyFilter) {
						if ($a['sponsor_name'] == $b['sponsor_name']) {
							return 0;
						}
						return ($a['sponsor_name'] > $b['sponsor_name']) ? -1 : 1;
					});
				break;
				case 'place-asc':
					usort($filtered_sponsors, function($a, $b) {
						if ($a['place_name'] == $b['place_name']) {
							return 0;
						}
						return ($a['place_name'] < $b['place_name']) ? -1 : 1;
					});
					
				break;
				case 'place-desc':
					usort($filtered_sponsors, function($a, $b) {
						if ($a['place_name'] == $b['place_name']) {
							return 0;
						}
						return ($a['place_name'] > $b['place_name']) ? -1 : 1;
					});
					
				break;
				case 'expire-asc':
					usort($filtered_sponsors, function($a, $b) {
						if ($a['expire_time'] == $b['expire_time']) {
							return 0;
						}
						return ($a['expire_time'] < $b['expire_time']) ? -1 : 1;
					});
					
				break;
				case 'expire-desc':
					usort($filtered_sponsors, function($a, $b) {
						if ($a['expire_time'] == $b['expire_time']) {
							return 0;
						}
						return ($a['expire_time'] > $b['expire_time']) ? -1 : 1;
					});
					
				break;
				default: 
				
			}
		}

	
		wp_send_json( $filtered_sponsors);
		wp_die();

	}

	add_action('wp_ajax_filterSponsors', 'filter_sponsors');
	add_action('wp_ajax_nopriv_filterSponsors', 'filter_sponsors');

// override Houzez function
if(!function_exists('houzez_author_pre_get')) {
	function houzez_author_pre_get( $query ) {
	    if ( $query->is_author() && $query->is_main_query() && !is_admin() ) :
	        // $query->set( 'posts_per_page', houzez_option('num_of_agent_listings', 10) );
	        // $query->set( 'post_type', array('property') );
	    endif;
	}
	add_action( 'pre_get_posts', 'houzez_author_pre_get' );
}

function limitWordsAndAddEllipsis($text, $wordLimit, $ellipsis = '...') {
    // Split the text into an array of words
    $words = explode(' ', $text);

    // Check if the number of words exceeds the limit
    if (count($words) > $wordLimit) {
        // Truncate the array of words to the specified limit
        $limitedWords = array_slice($words, 0, $wordLimit);
        
        // Join the limited words back into a string and append the ellipsis
        return implode(' ', $limitedWords) . $ellipsis;
    } else {
        // If the word count is within the limit, return the original text
        return $text;
    }
}

