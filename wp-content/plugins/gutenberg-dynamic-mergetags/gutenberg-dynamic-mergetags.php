<?php
/** 
 *  Plugin Name:        Gutenberg Dynamic Mergetags 
 *  Description:       Adds a merge tag dropdown to the paragraph block for dynamic data. 
 *  Version:           1.0.0 
 *  Author:            Your Name 
 *  License:           GPL-2.0-or-later 
 *  License URI:       https://www.gnu.org/licenses/gpl-2.0.html 
 *  Text Domain:       gddm 
 */

 if ( !defined('ABSPATH')) {
    exit;
 }

 /**
  * Difines the available merge tags
  * 
  * @return array
  */

  function gddm_get_merge_tags() {
    $type = get_post_meta(get_the_ID(), 'place_type', true);
    if ($type == 'city') {
        return [
            [
                'label' => 'Avg Home Value',
                'value' => 'avg_hom_val',
            ],
            [
                'label' => 'Avg Property Tax',
                'value' => 'avg_pro_tax',
            ],
            [
                'label' => 'Population',
                'value' => 'city_pop',
            ],
            [
                'label' => 'Commute',
                'value' => 'avg_com',
            ],
            [
                'label' => 'Household Income',
                'value' => 'avg_hou_inc',
            ],
            [
                'label' => 'Avg Rent',
                'value' => 'avg_rent',
            ],
        ];
    } elseif ($type == 'state') {
        return [
            [
                'label' => 'Population',
                'value' => 'state_pop',
            ],
            [
                'label' => 'Sales Tax',
                'value' => 'sales_tax',
            ],
            [
                'label' => 'Household Income',
                'value' => 'avg_hou_inc',
            ],
            [
                'label' => 'Property Tax',
                'value' => 'avg_pro_tax',
            ],
            [
                'label' => 'Income Tax',
                'value' => 'state_inc_tax',
            ],
            [
                'label' => 'Rent',
                'value' => 'avg_rent',
            ],
        ];
    } else {
        return;
    }
 
  }

  function gddm_enqueue_editor_assets() {
    $asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php' );

    wp_enqueue_script(
        'gddm-editor-script', plugins_url( 'build/index.js', __FIlE__ ), $asset_file['dependencies'], $asset_file['version']
    );

    wp_localize_script( 'gddm-editor-script', 'dynamicDataMergeTags', ['tags' => gddm_get_merge_tags()] );
  }

  add_action( 'enqueue_block_editor_assets', 'gddm_enqueue_editor_assets' );

  function gddm_render_data_shortcode( $atts ) {
    global $wpdb;
    $ID = get_the_ID();

    $atts = shortcode_atts( ['field' => ''], $atts, 'liv_data' );
    $field_key = sanitize_key( $atts['field'] );
    if (empty($field_key)) {return '';}

    $type = get_post_meta($ID, 'place_type', true);
    $table_name = '';
    if ($type == 'city') {
        $table_name = '2025_city_data';
    } elseif ($type == 'state') {
        $table_name = '2025_state_data';
    }

    // place_id is get_the_ID()
    $query = $wpdb->prepare(
        // "SELECT $field_key FROM $table_name WHERE place_id = %d", $ID
        // "SELECT agv_hom_val FROM 2025_city_data WHERE place_id = %d", 50747
        "SELECT {$field_key} FROM {$table_name} WHERE place_id = %d", $ID
    );
    $result = $wpdb->get_var($query);


    return $result;
    

  }

  add_shortcode( 'liv_data', 'gddm_render_data_shortcode' );