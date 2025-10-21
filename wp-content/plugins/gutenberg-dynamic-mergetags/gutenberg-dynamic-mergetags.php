<?php
/** 
 *  Plugin Name:      Gutenberg Dynamic Mergetags 
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
    return [
        [
            'label' => 'City Population',
            'value' => 'CITYPOP',
            'type'  => 'city'
        ],
        [
            'label' => 'State Population',
            'value' => 'STATEPOP',
            'type'  => 'state'
        ]
    ];
  }

  function gddm_enqueue_editor_assets() {
    $asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php' );

    wp_enqueue_script(
        'gddm-editor-script', plugins_url( 'build/index.js', __FIlE__ ), $asset_file['dependencies'], $asset_file['version']
    );

    wp_localize_script( 'gddm-editor-script', 'dynamicDataMergeTags', ['tags' => gddm_get_merge_tags()] );
  }