<?php
/*** Load Wordpress Files ***/
require_once('../wp-config.php');

global $wpdb;

$cnt = 0;
$file_name = 'image-meta.csv';
if(!empty($_REQUEST['csv_file_name'])) {
	$file_name = $_REQUEST['csv_file_name'];	
}
if (($handle = fopen($file_name , "r")) !== FALSE) {
    while (($data = fgetcsv($handle)) !== FALSE) {
    	if($cnt == 0) {
    		$cnt++;
    		continue;
    	}
		$qrySel = "SELECT * FROM wp_posts WHERE SUBSTRING_INDEX(guid, '/', -1) = \"" . $data[0] . "\" AND post_type = 'attachment'";
		echo $qrySel . "<br/>";
    	$results = $wpdb->get_results($qrySel);
		if(!empty($results)) {
			foreach ($results as $key => $result) {
				if(!empty($result->ID)) {
					$postID = $result->ID;

					$qryUpdate = "UPDATE wp_posts 
								SET post_title = '" . addslashes($data[4]) . "',
								post_content = '" . addslashes($data[5]) . "',
								post_excerpt = '" . addslashes($data[5]) . "', 
								post_date = '" . addslashes($data[2]) . "', 
								post_modified = '" . addslashes($data[1]) . "'
								WHERE ID = '{$postID}'";

					echo $qryUpdate . "<br/>";
					$wpdb->query($qryUpdate);
					checkMeta($postID, 'img_byline', $data[3]);
					checkMeta($postID, 'img_place_name', $data[6]);
					checkMeta($postID, '_img_byline', 'field_60758c7e8baa6');
					checkMeta($postID, '_img_place_name', 'field_60758cb08baa7');
					checkMeta($postID, '_wp_attachment_image_alt', $data[4]);
			    }
			}
	    }

	    $cnt++;
	}
}

echo "Total Image Updated: " . $cnt;


function checkMeta($postID, $metaKey, $metaValue) {
	global $wpdb;
	$qrySel = "SELECT * FROM wp_postmeta WHERE post_id = '{$postID}' AND meta_key = '{$metaKey}'";
	$results = $wpdb->get_results($qrySel);
	if(!empty($results)) {
		$qryMeta = "UPDATE wp_postmeta
						SET meta_value = '" . addslashes($metaValue) . "'
						WHERE meta_key = '" . addslashes($metaKey) . "'
						AND post_id = '{$postID}'";
	} else {
		$qryMeta = "INSERT INTO wp_postmeta (post_id, meta_key, meta_value) 
					VALUES 
					('{$postID}', '{$metaKey}', '{$metaValue}')";
	}

	echo $qryMeta . "<br/>";
	$wpdb->query($qryMeta);
}