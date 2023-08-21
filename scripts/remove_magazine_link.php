<?php
/*** Load Wordpress Files ***/
require_once('../wp-config.php');

global $wpdb;
$qrySel = "SELECT * FROM wp_posts p WHERE p.post_status = 'publish' AND post_type = 'liv_place' AND post_content LIKE '%wp:acf/full-width-magazine-link%'
";

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

			// echo $postID . "<br/>";
			$updated_place_content = removeMagazine($place_post_content->post_content);
			// echo $updated_place_content . "<br/>"; die();

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

echo "<br/> Total $i places updated";

function removeMagazine($content, $cnt = 0) {
    $openPos = strpos($content, '<!-- wp:acf/full-width-magazine-link');
    $closePos = strpos($content, '<!-- wp:jci-blocks/quick-facts -->');
    if($openPos !== false) {         
        $contentBefore = substr($content, 0, $openPos);
        $contentAfter = substr($content, $closePos);
        $content = removeMagazine($contentBefore . $contentAfter);
    }

    return $content;
}