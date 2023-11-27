<?php 

// include_once(get_stylesheet_directory() .'/get-place-distance.php');
include_once( get_stylesheet_directory() .'/assets/lib/simple_html_dom.php');
include_once( get_stylesheet_directory() .'/assets/lib/regions.php');
include_once( get_stylesheet_directory() .'/assets/lib/meta_key_display.php');
function liv_mobile_menu() {
  register_nav_menu('liv-mobile-menu',__( 'Liv Mobile Menu' ));
}
add_action( 'init', 'liv_mobile_menu' );

// wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/js/slick.min.js', array('jquery'), null, true);
// wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/js/main.js', array('jquery','slick'), null, true);
// wp_enqueue_style( 'slick', get_stylesheet_directory_uri() . '/css/slick.css');
// wp_enqueue_style( 'slick-theme', get_stylesheet_directory_uri() . '/css/slick-theme.css');
function livability_enqueue_scripts() {
	global $topics;
	wp_enqueue_script( 'headroom', get_stylesheet_directory_uri() . '/assets/js/headroom.js', array('jquery'),null, true );
	wp_enqueue_script( 'main-theme', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery', 'slick', 'waypoints','waypoints-sticky'), null, true);
	wp_enqueue_style( 'style', get_stylesheet_uri(), array('twenty-twenty-one-style'));
	wp_enqueue_style( 'compressed', get_stylesheet_directory_uri() . '/compressed-style.css', array('style'));
	wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array('jquery'), null, true);
	wp_enqueue_script( 'waypoints', get_stylesheet_directory_uri() . '/assets/js/jquery.waypoints.min.js', array('jquery'), null, true );
	wp_enqueue_script( 'waypoints-sticky', get_stylesheet_directory_uri() . '/assets/js/sticky.min.js', array('jquery', 'waypoints'), null, true );
	wp_enqueue_script( 'youtube-api', 'https://www.youtube.com/iframe_api', array(), null, true );
	wp_enqueue_style( 'slick-style', get_stylesheet_directory_uri() . '/assets/css/slick.css');
	wp_enqueue_style( 'slick-theme', get_stylesheet_directory_uri() . '/assets/css/slick-theme.css');
	wp_dequeue_script('twenty-twenty-one-primary-navigation-script');
	wp_dequeue_script('twenty-twenty-one-ie11-polyfills');
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
		'bp23page'	=> 1
	) );
}

add_action('wp_enqueue_scripts', 'livability_enqueue_scripts');

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
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
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
	register_taxonomy( 'best_places_years', array( 'best_places' ), $args );

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
                <h3  class="h4"><?php echo $bp['cityTitle']; ?></h3>
                <p class="meta-sort <?php echo strtolower(display_meta_key($meta_key)); ?>"><strong><?php echo ucfirst(display_meta_key($meta_key)).$score_text.': '.get_field($meta_key, $bp['id']); ?></strong></p>
                <p><?php echo 'Region: '.get_region($bp['stateId']); ?></p>
                <p>Population: <?php echo number_format($bp['cityPopulation']); ?></p>
                <p>Med. Home Value: $<?php echo number_format($bp['cityHomeValue']); ?></p>
            </div>
			<p class="read-more"><a href="<?php echo get_the_permalink( $bp['id'] ); ?>">Read More</a></p>
			</div>
		<?php }

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

function create_listing_object() {
	$pagetype = $currentcat = $oldcategory = $place = $state = $placeid = $year = $articleid =  "";
	$slug = get_the_permalink();
	$global = "false";
	$ID = get_the_ID();
	$post_type = get_post_type();
	$cat = get_the_category();
	$all_cats = get_categories();
	$topic_page_array = array();
	
	foreach($all_cats as $c) {
		$p = get_field('category_page', 'category_'.$c->term_id);
		if ($p) {
			$topic_page_array[] = $p->ID;

		}
	}
	$t = '';
	// Places
	if ($post_type == 'liv_place') {
		$t = get_field('place_type');
		$sc = get_field('state_code');
		$onid = get_field('old_node_id');
		if ($sc) {
			$state = $sc;	
		}
		if ($onid) {
			$placeid = json_encode(array($onid));	
		}
		if ($t == 'city') {
			$pagetype = $t;
		} elseif ($t == 'state') {
			$pagetype = $t;
		} else {
			$pagetype = 'page';
		}
		$place = array(get_the_title( ));

	}

	//Magazines
	if ($post_type ==  'liv_magazine') {
		$pagetype = 'digital_magazine';
		$mag_place_type = get_field('mag_place_type');
		// $placeid = get_field('mag_old_node_id');
		$mag_place_array = get_field('place_relationship');
		if ($mag_place_array) {
		$mag_places = array();
		$mag_ids = array();
		foreach ($mag_place_array as $mpa) {
			$mpa_parent = wp_get_post_parent_id($mpa);
			if ($mag_place_type == 'State' && $mpa_parent < 1) {
				$mag_places[] = get_the_title($mpa);
				$mag_ids[] = get_field('old_node_id', $mpa);
			} elseif ($mag_place_type != 'State' && $mpa_parent > 0) {
				$mag_places[] = get_the_title($mpa);
				$mag_ids[] = get_field('old_node_id', $mpa);
			}
			// if ($mpa_parent > 0) {
			// 	$mag_places[] = get_the_title($mpa);
			// 	$mag_ids[] = get_field('old_node_id', $mpa);
			// }
		}
		$place = $mag_places;
		$placeid = json_encode($mag_ids);
		// $placeid = get_field('mag_old_node_id') ? get_field('mag_old_node_id') : get_the_ID( );

	}

		// var_dump($mag_place_array);
		// if (count($mag_place_array) == 1) {
			// $mag_ID = $mag_place_array[0];
			// $placeid = json_encode(array(get_field('old_node_id', $mag_ID)));
		// } else {
			// 'meta_key'	=> 'place_type','meta_value' => 'state'
			// $mag_place_array = implode(",", $mag_place_array);
			// $state_place = get_posts( array(
			// 	'post_type'	=> 'liv_place',
			// 	'orderby'   => 'post__in',
			// 	'post__in' => $mag_place_array,
			// 	'posts_per_page' => 1
			// ));
			// $state_place_id = $state_place[0]->ID;
			// $placeid = json_encode(array(get_field('old_node_id', $state_place_id)));
			// echo '<pre>';
			// var_dump($state_place[0]->ID);
			// var_dump($mag_place_array);
			// echo '</pre>';
		// }
	}
	// Best Places 
	if ($post_type == 'best_places') {
		$pagetype = 'best_places';
		if (has_post_parent( )) {
			$parent = get_post_parent(  );
			// print_r($parent);
			$currentcat = $parent->post_title;
		} else {
			$currentcat = get_the_title(  );
			$global = "true";

		}
		$year_tax = get_the_terms($ID, 'best_places_years' );

		if ($year_tax) {
			$year = $year_tax[0]->name;
		}

		$bp_place = get_field('place_relationship');
		// $bp_places = array();
		// print_r($bp_place);
		if ($bp_place) {
			$bpp_Id = $bp_place[0];
			$place = get_the_title($bpp_Id);
			// $placeid = get_field('old_node_id',$bpp_Id);
			// $placeid = json_encode($bp_place);
			$placeid = $bp_place[0];
			$t = get_field('place_type', $bpp_Id);
			if ($t == 'state') { 
				$state = get_field('state_code',$bp_place);
			} elseif ($t == 'city') {
				$p = get_post_parent($bpp_Id);
				$state = get_field('state_code',$p->ID);
			}
		}
	}

	// Pages
	if ($post_type == 'page') {
		if ( in_array($ID, $topic_page_array)) {
			$pagetype = 'topic';
		} elseif (is_front_page()) {
			$pagetype = 'home';
			$global = "true";
		} else {
			$pagetype = 'page';
		}
	}
	// Articles
	if ($post_type == 'post') {
		$articleid = $ID;
		$pagetype = 'article';
		$currentcat = $cat[0]->name;
		$article_place_rel = get_field('place_relationship');
		$post_places = array();
		$place_ids = array();
		$state_names = array();
		// $placeid = get_field('article_node_id');
		if ($article_place_rel) {
			foreach($article_place_rel as $apr) {
				// $post_places[] = "'".get_the_title($apr)."'";
				$apr_parent = wp_get_post_parent_id($apr);
				if ($apr_parent && $apr_parent > 0) {
					$state_names[] = get_field('state_code', $apr_parent);
				} else {
					$state_names[] = get_field('state_code', $apr);
				}

				$post_places[] = get_the_title($apr);
				$place_ids[] = strval(get_field('old_node_id',$apr));
			}
			if (!empty($state_names)) {
				$state = $state_names[0];
			}
			// print_r($post_places);
			// $place = implode(",", $post_places);
			$place = $post_places;
			// $placeid = implode(",",$place_ids);
			$placeid = json_encode($place_ids);
			// $placeid = get_field('old_node_id', $article_place_rel[0]);
		} else  {
			$global = 'true';
			$placeid = "";
		}
		if (get_field('sponsored')) {
			$global = 'sponsored';
		}
		switch ($cat[0]->term_id) {
			case 11: //exp. and adventures
				$oldcategory = 'things-to-do';
				break;
			case 32: // affordable places
				$oldcategory = 'affordable-places-to-live';
				break;
			case 12: // Ed, careers and op
				$oldcategory = 'business';
				break;
			case 16: // food scenes
				$oldcategory = 'foodscenes';
				break;	
			case 13: // healthy places
				$oldcategory = 'health';
				break;	
			case 14: // love where you live
				$oldcategory = 'community';
				break;	
			case 47: // where to live now
				$oldcategory = 'real-estate';
				break;	
			default: 
				$oldcategory = '';

	}
		
	}
	$currentcat = str_replace('&amp;',' ', $currentcat);
	?>
<script>
		if (!window.ListingObj) {
	var ListingObj = {
		"pageslug" : window.location.pathname,
		"pagetype" : "<?php echo $pagetype; ?>",
		"currentcat": "<?php echo $currentcat; ?>",
		"place"		: <?php echo json_encode($place); ?>,
		"placeid"	: "",
		"state"		: "<?php echo $state; ?>",
		"oldcategory"	: "<?php echo $oldcategory; ?>",
		"global"	: "<?php echo strval($global); ?>",
		"year"	: "<?php echo $year; ?>",
		"articleid":	"<?php echo $articleid; ?>"
	}
	<?php if (strlen($placeid) > 0) { ?>
	ListingObj['placeid'] = <?php echo $placeid; ?>;	
	<?php } ?>
	}
	
</script>
<?php }  
 //echo strlen($placeid) > 0 ? $placeid : ''; 
add_action('wp_head','create_listing_object');

function get_topic_sponsors() {
	if (get_post_type() == 'liv_place') {
		$ID = get_the_ID();
		$sponsors = get_field('sponsorships', 'options');
		$sponsor_array = array();
		// print_r($sponsors);
		if ($sponsors) {
			foreach($sponsors as $s) {
				//  print_r($s);
				//  echo '<br />';
				// echo ($s['place'][0]);
				// if (array_key_exists('place', $s)) {
					if ($s['place'][0] == $ID) {
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
add_action('wp_head','get_topic_sponsors');

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

// Add image meta to images in content
function livability_add_image_meta( $content ) {
	$html = str_get_html($content);
	if (get_post_type() == 'liv_place' ) {
		return $html;
	} elseif(is_front_page()) {	
		$homepage_obj = get_page_by_title( 'Homepage Output');
		if ($homepage_obj && file_exists(WP_CONTENT_DIR.'/uploads/homepage-content/homepage-output.html')){
			include(WP_CONTENT_DIR.'/uploads/homepage-content/homepage-output.html');
		} else {
			return $html;
		}
	} else {
		if (gettype($html) != 'boolean') {
			foreach($html->find('img') as $img){
				 
				$src = $img->src;
				// temp fix for staging url img src until db search/replace can be done 
				$src = str_replace('//livability.com', '//livability.lndo.site', $src);
				$post_image_id = attachment_url_to_postid( $src );
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
	}
}

add_filter( 'the_content', 'livability_add_image_meta' );


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

add_action('wp_ajax_updateLO', 'get_listingObj_data');
add_action('wp_ajax_nopriv_updateLO', 'get_listingObj_data');

function get_listingObj_data() {
	$id = $_POST['id'];
	$placearray = [];
	$placeidarray = [];
	$article_place_rel = get_field('place_relationship', 19597); 
	if ($article_place_rel) {
		foreach($article_place_rel as $apr) {
			$placearray[] = get_the_title($apr);
			$placeidarray[] = strval(get_field('old_node_id',$apr));
		}
	} 
	$result = array(
		'place' 	=> $placearray,
		'placeid'	=> $placeidarray
	);
	wp_send_json($result);
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

add_action('post_updated', 'write_static_homepage', 10, 3);

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
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 10,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'show_in_rest'			=> true,
			'can_export'            => true,
			'has_archive'           => true,
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
		$filters = $_POST['bp23filters'];
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
		die();
	}

	add_action('wp_ajax_loadbp23', 'load_bp_23');
	add_action('wp_ajax_nopriv_loadbp23', 'load_bp_23');

	add_filter( 'gform_enable_credit_card_field', 'enable_creditcard', 11 );
	function enable_creditcard( $is_enabled ) {
	return true;
	}


	// function connected_communities_payment($entry, $form ) {
		// error_log(print_r($entry, true));
	// };
	// add_action('gform_after_submission_11', 'connected_communities_payment', 10, 2);	


	/**
	 * Gravity Perks // GP Populate Anything // Change The Query Limit
	 * https://gravitywiz.com/documentation/gravity-forms-populate-anything/
	 */
	// add_filter( 'gppa_query_limit_11_16', function() {
		// Update "750" to whatever you would like the query limit to be.
		// return 50;
	// } );
// add_action( 'init', 'make_places_csv');



// function get_coordinates() {
// 	// loop through rows
// 	$csvFile = get_stylesheet_directory(  ).'/places-demo.csv';
// 	$resultFile = get_stylesheet_directory(  ).'/result.csv';
// 	ini_set('auto_detect_line_endings', true);
// 	$ran = false;
// 	if (!$ran) {
// 		if ($handle = fopen($csvFile, 'r') !== false){

// 		$ran = true;
// 		}
// 	}
// 	//close php and use php variables to send ajax request in js

// 	// open php and close function

// 	// write ajax handling in new function
// }

// add_action('init', 'get_coordinates');

