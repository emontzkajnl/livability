<?php
/*** Load Wordpress Files ***/
require_once('../wp-config.php');
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($connection, DB_NAME);

global $wpdb;
$qrySel = "SELECT * FROM wp_posts p, wp_postmeta pm WHERE p.ID = pm.post_id AND p. post_type = 'attachment' AND pm.meta_key = '_wp_attachment_image_alt' AND pm.meta_value != p.post_title";
echo $qrySel . "<br/>";
$results = $wpdb->get_results($qrySel);


if(!empty($results)) {
	foreach ($results as $key => $result) {
		if(!empty($result->ID)) {
			$postID = $result->ID;

			$qryUpdate = "UPDATE wp_posts 
						SET post_title = '{$result->meta_value}'
						WHERE ID = '{$postID}'";
			$wpdb->query($qryUpdate);
			echo $qryUpdate . "<br/>";
	    }
	}
}