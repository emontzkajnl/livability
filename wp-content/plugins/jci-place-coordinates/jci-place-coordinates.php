<?php 
require_once(plugin_dir_path(__FILE__).'places-result.php');
/**
 * Plugin Name: JCI Place Coordinates
 * Plugin URI: https://livability.com
 * Description: Creates fields for coordinates for place post types and requests from google geolocation api.
 * Author: Journal Communications
 */



 function jci_pc_enqueue_script() {
    //  wp_enqueue_script( 'json-coordinates', plugin_dir_url(__FILE__).'js/places-result.js', array(), null, true);
     wp_enqueue_script( 'json-obj', plugin_dir_url(__FILE__).'js/all-places.js', array(), null, true);
     wp_enqueue_script( 'place-coordinates', plugin_dir_url(__FILE__).'js/place-coordinates.js', array('jquery','json-obj' ), null, true );
 }
 add_action('wp_enqueue_scripts', 'jci_pc_enqueue_script');

 /**
  * imports all places to json object
  */
  function make_places_json() {
      echo 'function running<br />';
	$jsonFile = plugin_dir_path(__FILE__).'js/all-places.js';
	$result_array = [];
	$args = array(
        'post_type'         => 'liv_place',
        'post_status'       => 'publish',
        'posts_per_page'    => -1
    );
    $places = new WP_Query($args);
	if ($places->have_posts()):
		// $handle = fopen($jsonFile, 'a+');
        while ($places->have_posts()): $places->the_post();
        echo 'id is '.get_the_id().' and the title is '.get_the_title().'<br />';
		// echo 'inside while';
		$id = get_the_id();
		$title = get_the_title();
		$json_array = ['id' => $id, 'title' => $title];
		array_push($result_array, $json_array);
		// fwrite($handle, json_encode($json_array, JSON_PRETTY_PRINT));

        endwhile;
		// fclose($handle);
		file_put_contents($jsonFile,json_encode($result_array, JSON_PRETTY_PRINT));
    else:
		echo 'file issue';
	endif;
	wp_reset_query(  );
}
// add_action('init', 'make_places_json');

 /**
 * Adds a meta box to the post editing screen
 */
function jci_custom_meta() {
    add_meta_box( 'jci_latitude', __( 'Place Latitude', 'livability' ), 'jci_lat_callback', 'liv_place' );
    add_meta_box( 'jci_longitude', __( 'Place Longitude', 'livability' ), 'jci_long_callback', 'liv_place' );
}
add_action( 'add_meta_boxes', 'jci_custom_meta' );

/**
 * Outputs the content of the meta boxes
 */
function jci_lat_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'jci_nonce' );
    $jci_stored_meta = get_post_meta( $post->ID );
    ?>
 
    <p>
        <label for="jci-latitude" class="jci-row-title"><?php _e( 'Latitude', 'livability' )?></label>
        <input type="text" name="jci-latitude" id="jci-latitude" value="<?php if ( isset ( $jci_stored_meta['jci-latitude'] ) ) echo $jci_stored_meta['jci-latitude'][0]; ?>" />
    </p>
 
    <?php
}

function jci_long_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'jci_nonce' );
    $jci_stored_meta = get_post_meta( $post->ID );
    ?>
 
    <p>
        <label for="jci-longitude" class="jci-row-title"><?php _e( 'Longitude', 'livability' )?></label>
        <input type="text" name="jci-longitude" id="jci-longitude" value="<?php if ( isset ( $jci_stored_meta['jci-longitude'] ) ) echo $jci_stored_meta['jci-longitude'][0]; ?>" />
    </p>
 
    <?php
}

/**
 * Saves the custom meta input
 */
function jci_meta_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'jci_nonce' ] ) && wp_verify_nonce( $_POST[ 'jci_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'jci-latitude' ] ) ) {
        update_post_meta( $post_id, 'jci-latitude', sanitize_text_field( $_POST[ 'jci-latitude' ] ) );
    }
    if( isset( $_POST[ 'jci-longitude' ] ) ) {
        update_post_meta( $post_id, 'jci-longitude', sanitize_text_field( $_POST[ 'jci-longitude' ] ) );
    }
 
}
add_action( 'save_post', 'jci_meta_save' );

function write_json() {
    $id = $_POST['id'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $jsonFile = plugin_dir_path(__FILE__).'js/places-result.js';
    $phpArray = ['id'=> $id, 'lat' => $lat, 'lng' => $lng];
    file_put_contents($jsonFile, json_encode($phpArray, JSON_PRETTY_PRINT).',', FILE_APPEND);
    // add_post_meta( $id, 'jci-latitude', $lat, false );
    // add_post_meta( $id, 'jci-longitude', $lng, false );
    die();


}

add_action('wp_ajax_writeJson', 'write_json');
add_action('wp_ajax_nopriv_writeJson', 'write_json');

function save_coordinates() {
    global $places_result;
    foreach ($places_result as $place) {
        // echo 'place: '.$place['id'].'<br />';
        $new_place = get_post( $place['id']);
        if ($new_place) {
            // echo 'the title is '.get_the_title($place['id']).'.</br />';
            update_post_meta($place['id'], 'jci-latitude',$place['lat']);
            update_post_meta($place['id'], 'jci-longitude',$place['lng']);
            // echo 'updated '.get_the_title($place['id']).'.</br />';
        }
    }
    // print_r($places_result[4]);

}

// register_activation_hook( __FILE__, 'save_coordinates' );
// add_action('init','save_coordinates'  );