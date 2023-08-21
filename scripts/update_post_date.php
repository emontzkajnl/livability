<?php
/*** Load Wordpress Files ***/
require_once('../wp-config.php');

global $wpdb;
$qrySel = "SELECT * FROM wp_posts p WHERE (p.post_date = '0000-00-00 00:00:00' OR p.post_modified = '0000-00-00 00:00:00')";
echo $qrySel . "<br/>";
$results = $wpdb->get_results($qrySel);


if(!empty($results)) {
	foreach ($results as $key => $result) {
		if(!empty($result->ID)) {
			$postID = $result->ID;
			$post_date_gmt = $result->post_date_gmt;
			$post_modified_gmt = $result->post_modified_gmt;

			$qryUpdate = "UPDATE wp_posts 
						SET post_date = '{$post_date_gmt}',
						post_modified = '{$post_date_gmt}'
						WHERE ID = '{$postID}'";
			$wpdb->query($qryUpdate);
			echo $qryUpdate . "<br/>";
	    }
	}
}