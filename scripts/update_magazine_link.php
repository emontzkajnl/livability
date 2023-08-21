<?php
/*** Load Wordpress Files ***/
require_once('../wp-config.php');

global $wpdb;
$qrySel = "SELECT * FROM wp_posts p WHERE p.post_status = 'publish' AND post_type = 'liv_magazine'";
if(!empty($_REQUEST['limit'])) {
	$qrySel .= " LIMIT " . $_REQUEST['limit'];
}
echo $qrySel . "<br/>";
$results = $wpdb->get_results($qrySel);

if(!empty($results)) {
	$prevPlacePostId = array();
	foreach ($results as $key => $result) {
		if(!empty($result->ID)) {
			$magazineID = $result->ID;
			$placeRelationship = get_post_meta($magazineID, 'place_relationship')[0];

			$prevPlacePostId[$magazineID] = array();
			foreach ($placeRelationship as $index => $place_post_id) {
				if(!array_key_exists($place_post_id, $prevPlacePostId[$magazineID])) {
					$place_post_content = get_post($place_post_id);			
					
					$random = rand(1, 10000);
					$find = '<!-- wp:jci-blocks/quick-facts -->';
					$replace = '<!-- wp:acf/full-width-magazine-link {"id":"block_' . $random .'","name":"acf/full-width-magazine-link","data":{"magazine_link":' . $magazineID . ',"_magazine_link":"field_60bd3ea6c7229"},"align":"","mode":"preview"} /--><!-- wp:jci-blocks/quick-facts -->';
				
					$updated_place_content = str_replace($find, $replace, $place_post_content->post_content);


					$updatePost = array(
						'ID'           => $place_post_id,
						'post_content' => $updated_place_content,
					);

					// Update the post into the database
					wp_update_post( $updatePost );

					$prevPlacePostId[$magazineID][$place_post_id] = $place_post_id;
				}
			}

	    }
	}
}

echo "Script execution is completed...! :)";