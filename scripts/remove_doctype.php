<?php
/*** Load Wordpress Files ***/
require_once('../wp-config.php');

global $wpdb;
$qrySel = "SELECT * FROM wp_posts WHERE post_content LIKE '%!DOCTYPE%'";

if(!empty($_REQUEST['limit'])) {
	$qrySel .= " LIMIT " . $_REQUEST['limit'];
}
echo $qrySel . "<br/>";
$results = $wpdb->get_results($qrySel);

if(!empty($results)) {
	$i = 0;
	foreach ($results as $key => $result) {
		if(!empty($result->ID)) {
			$postID = $result->ID;
			$place_post_content = get_post($postID);			

			$updated_place_content = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">', '', $place_post_content->post_content);

			$updatePost = array(
				'ID'           => $postID,
				'post_content' => $updated_place_content,
			);

			// Update the post into the database
			wp_update_post( $updatePost );

			$i++;
	    }
	}
}

echo "Script execution is completed...! :)";

echo "<br/> Total $i content updated";