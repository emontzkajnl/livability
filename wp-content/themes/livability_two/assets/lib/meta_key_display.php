<?php
function display_meta_key($meta_key) {
    $meta_key_display;
    switch ($meta_key) {
			case 'amenities':
				$meta_key_display = 'amenities';
				break;
			case 'ls_environment':  
				$meta_key_display = 'environment';
				break;
			case 'ls_economy':
				$meta_key_display = 'economy';
				break;
			case 'ls_education':  
				$meta_key_display = 'education';
				break;
			case 'ls_transportation':
				$meta_key_display = 'transportation';
				break;
			case 'ls_safety':  
				$meta_key_display = 'safety';
				break;
			case 'ls_health':
				$meta_key_display = 'health';
				break;
			case 'ls_housing':  
				$meta_key_display = 'housing & Cost of Living';
				break;
			default: 
				$meta_key_display = 'LivScore';
		}
        return $meta_key_display;
}