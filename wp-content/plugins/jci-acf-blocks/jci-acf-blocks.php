<?php
/*
Plugin Name: JCI ACF Blocks
Plugin URI: https://www.jnlcom.com/
Description: Adds Custom Blocks Based On ACF Fields
Author: Journal Communications, Inc.
Text Domain: acf-blocks
Version: 1.0.0

*/

add_action('acf/init', 'my_acf_init_block_types');

function my_acf_init_block_types() {
    if( function_exists('acf_register_block_type')) {
        
        acf_register_block_type(array(
            'name'              => 'curated_blocks_1',
            'title'             => __('Curated Posts 1', 'acf-blocks'),
            'description'       => __('Select a piece of content to feature in a single, wide row.', 'acf-blocks'), 
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/curated-posts/curated-posts-1.php',
            'category'          => 'jci-category',
            'icon'              => '<svg enable-background="new 0 0 682 682" viewBox="0 0 682 682" xmlns="http://www.w3.org/2000/svg"><path d="m571.3 481.4h-461.2c-18.2 0-32.9-15.3-32.9-34.1v-181.6c0-18.8 14.8-34.1 32.9-34.1h461.3c18.2 0 32.9 15.3 32.9 34.1v181.7c0 18.7-14.8 34-33 34zm-461.2-227.1c-6 0-11 5.1-11 11.4v181.7c0 6.2 4.9 11.4 11 11.4h461.3c6 0 11-5.1 11-11.4v-181.7c0-6.2-4.9-11.4-11-11.4z"/><path d="m593.3 458.7c-82.1 0-152.7-28.9-215-54.4-47.8-19.6-89-36.4-125.5-36.4-82.6 0-156.9 64.7-157.6 65.4-4.6 4.1-11.5 3.5-15.5-1.2-4-4.8-3.4-11.9 1.2-16 3.3-2.9 81-70.9 171.9-70.9 40.7 0 83.8 17.6 133.6 38 60.5 24.8 129 52.8 206.9 52.8 6.1 0 11 5.1 11 11.4 0 6.2-4.9 11.3-11 11.3z"/><path d="m593.3 401.5c-1.4 0-2.7-.2-4-.8-45.6-18.6-83.2-32.8-116.8-32.8-40.7 0-78.4 15.6-102.8 28.7-5.3 2.9-12 .7-14.8-4.8-2.8-5.6-.7-12.4 4.7-15.3 26.6-14.3 67.7-31.3 112.9-31.3 37.5 0 77 14.9 124.9 34.4 5.6 2.3 8.4 8.9 6.2 14.7-1.8 4.4-5.9 7.2-10.3 7.2z"/></svg>',
            'keywords'          => array( 'block', 'curated', 'post' ),
            'example'           => array(
                'attributes'    => array(
                    'mode'      => 'preview',
                    'data'      => array(
                        'is_preview'    => true,
                        'preview_img'  => plugin_dir_url( __FILE__ ) . 'image-preview/1-curated-preview.jpg'
                    ),
                ),
            ),
        ));
        
        acf_register_block_type(array(
            'name'              => 'curated_blocks_2',
            'title'             => __('Curated Posts 2', 'acf-blocks'),
            'description'       => __('Select 2 pieces of content to feature side by side in a single row.', 'acf-blocks'), 
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/curated-posts/curated-posts-2.php',
            'category'          => 'jci-category',
            'icon'              => '<svg enable-background="new 0 0 682 682" viewBox="0 0 682 682" xmlns="http://www.w3.org/2000/svg"><path d="m579.3 465.9h-476.6c-18.8 0-34-15.3-34-34.1v-181.6c0-18.8 15.3-34.1 34-34.1h476.7c18.8 0 34 15.3 34 34.1v181.7c0 18.7-15.3 34-34.1 34zm-476.6-227.1c-6.2 0-11.3 5.1-11.3 11.4v181.7c0 6.2 5.1 11.4 11.3 11.4h476.7c6.2 0 11.3-5.1 11.3-11.4v-181.7c0-6.2-5.1-11.4-11.3-11.4z"/><path d="m332.5 424.3c-40.1 0-74.6-13.2-105-24.9-23.3-9-43.5-16.7-61.3-16.7-40.4 0-76.6 29.7-77 30-2.3 1.9-5.6 1.6-7.6-.6-1.9-2.2-1.7-5.5.6-7.3 1.6-1.3 39.6-32.5 84-32.5 19.9 0 40.9 8.1 65.3 17.4 29.5 11.3 63 24.2 101.1 24.2 3 0 5.4 2.3 5.4 5.2s-2.6 5.2-5.5 5.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m340.6 398c-.6 0-1.3-.1-1.9-.4-21.6-8.5-39.4-15-55.3-15-19.3 0-37.1 7.2-48.7 13.2-2.6 1.3-5.7.3-7-2.2-1.3-2.6-.3-5.7 2.2-7 12.6-6.5 32.1-14.3 53.5-14.3 17.8 0 36.5 6.8 59.2 15.8 2.7 1.1 4 4.1 2.9 6.7-.8 2-2.8 3.2-4.9 3.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m161.1 309.8h-31.2c-8.6 0-15.6-7-15.6-15.6 0-7.8 5.7-14.2 13.1-15.4 3.5-6.5 10.4-10.6 18.1-10.6s14.6 4.1 18.1 10.6c7.4 1.2 13.1 7.7 13.1 15.4 0 8.6-7 15.6-15.6 15.6zm-31.9-21c-2.2.2-4.5 2.5-4.5 5.4s2.3 5.2 5.2 5.2h31.2c2.9 0 5.2-2.3 5.2-5.2s-2.3-5.2-5.2-5.2c-1.3.4-2.4.2-3.6-.4s-1.8-1.8-2.2-3.1c-1.3-4.1-5.2-6.9-9.7-6.9s-8.4 2.8-9.7 6.9c-.4 1.3-1.4 2.4-2.6 3-1.4.6-2.8.7-4.1.3z"/><path d="m594.6 424.3c-40.7 0-75.7-13.2-106.6-24.9-23.7-9-44.1-16.7-62.2-16.7-41 0-77.8 29.7-78.1 30-2.3 1.9-5.7 1.6-7.7-.6-1.9-2.2-1.7-5.5.6-7.3 1.6-1.3 40.2-32.5 85.2-32.5 20.2 0 41.5 8.1 66.2 17.4 30 11.3 63.9 24.2 102.5 24.2 3 0 5.4 2.3 5.4 5.2s-2.3 5.2-5.3 5.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m602.8 398c-.6 0-1.3-.1-1.9-.4-21.6-8.5-39.4-15-55.3-15-19.3 0-37.1 7.2-48.7 13.2-2.6 1.3-5.7.3-7-2.2-1.3-2.6-.3-5.7 2.2-7 12.6-6.5 32.1-14.3 53.5-14.3 17.8 0 36.5 6.8 59.2 15.8 2.7 1.1 4 4.1 2.9 6.7-.8 2-2.8 3.2-4.9 3.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m423.3 309.8h-31.2c-8.6 0-15.6-7-15.6-15.6 0-7.8 5.7-14.2 13.1-15.4 3.5-6.5 10.4-10.6 18.1-10.6s14.6 4.1 18.1 10.6c7.4 1.2 13.1 7.7 13.1 15.4 0 8.6-7 15.6-15.6 15.6zm-31.9-21c-2.2.2-4.5 2.5-4.5 5.4s2.3 5.2 5.2 5.2h31.2c2.9 0 5.2-2.3 5.2-5.2s-2.3-5.2-5.2-5.2c-1.3.4-2.4.2-3.6-.4s-1.8-1.8-2.2-3.1c-1.3-4.1-5.2-6.9-9.7-6.9s-8.4 2.8-9.7 6.9c-.4 1.3-1.4 2.4-2.6 3-1.4.6-2.8.7-4.1.3z"/><path d="m341 216.1v249.8z" stroke="#000" stroke-miterlimit="10" stroke-width="19"/></svg>',
            'keywords'          => array( 'block', 'curated', 'post' ),
            'example'           => array(
                'attributes'    => array(
                    'mode'      => 'preview',
                    'data'      => array(
                        'is_preview'    => true,
                        'preview_img'  => plugin_dir_url( __FILE__ ) . 'image-preview/2-curated-preview.jpg'
                    ),
                ),
            ),
        ));
        
        acf_register_block_type(array(
            'name'              => 'curated_blocks_3',
            'title'             => __('Curated Posts 3', 'acf-blocks'),
            'description'       => __('Select 3 pieces of content to feature side by side in a single row.', 'acf-blocks'), 
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/curated-posts/curated-posts-3.php',
            'category'          => 'jci-category',
            'icon'              => '<svg enable-background="new 0 0 682 682" viewBox="0 0 682 682" xmlns="http://www.w3.org/2000/svg"><path d="m579.3 465.9h-476.6c-18.8 0-34-15.3-34-34.1v-181.6c0-18.8 15.3-34.1 34-34.1h476.7c18.8 0 34 15.3 34 34.1v181.7c0 18.7-15.3 34-34.1 34zm-476.6-227.1c-6.2 0-11.3 5.1-11.3 11.4v181.7c0 6.2 5.1 11.4 11.3 11.4h476.7c6.2 0 11.3-5.1 11.3-11.4v-181.7c0-6.2-5.1-11.4-11.3-11.4z"/><path d="m335.6 422.7c-40.6 0-75.5-13.2-106.3-24.9-23.6-9-44-16.7-62.1-16.7-40.9 0-77.6 29.7-77.9 30-2.3 1.9-5.7 1.6-7.7-.6-1.9-2.2-1.7-5.5.6-7.3 1.6-1.3 40.1-32.5 85-32.5 20.1 0 41.4 8.1 66.1 17.4 29.9 11.3 63.8 24.2 102.3 24.2 3 0 5.4 2.3 5.4 5.2 0 2.8-2.4 5.2-5.4 5.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m376.8 406.9c-.6 0-1.3-.1-1.9-.4-21.6-8.5-39.4-15-55.3-15-19.3 0-37.1 7.2-48.7 13.2-2.6 1.3-5.7.3-7-2.2-1.3-2.6-.3-5.7 2.2-7 12.6-6.5 32.1-14.3 53.5-14.3 17.8 0 36.5 6.8 59.2 15.8 2.7 1.1 4 4.1 2.9 6.7-.8 2-2.8 3.2-4.9 3.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m156.5 299.4h-31.2c-8.6 0-15.6-7-15.6-15.6 0-7.8 5.7-14.2 13.1-15.4 3.5-6.5 10.4-10.6 18.1-10.6s14.6 4.1 18.1 10.6c7.4 1.2 13.1 7.7 13.1 15.4 0 8.6-7 15.6-15.6 15.6zm-31.9-21c-2.2.2-4.5 2.5-4.5 5.4s2.3 5.2 5.2 5.2h31.2c2.9 0 5.2-2.3 5.2-5.2s-2.3-5.2-5.2-5.2c-1.3.4-2.4.2-3.6-.4s-1.8-1.8-2.2-3.1c-1.3-4.1-5.2-6.9-9.7-6.9s-8.4 2.8-9.7 6.9c-.4 1.3-1.4 2.4-2.6 3-1.3.6-2.8.7-4.1.3z"/><path d="m589.5 445.6c-47.3 3.7-89.3-10-126.4-22.2-28.4-9.3-53-17.4-74-15.7-47.6 3.8-87.4 45.2-87.8 45.6-2.5 2.6-6.5 2.6-9 0s-2.5-6.9 0-9.5c1.7-1.8 43.4-45.3 95.8-49.5 23.5-1.9 49.1 6.5 78.7 16.3 36 11.8 76.8 25.2 121.7 21.6 3.5-.3 6.6 2.5 6.9 6.2s-2.5 6.9-5.9 7.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m592 419.8c-.8 0-1.5-.1-2.3-.4-25.6-8.5-46.6-15-65.4-15-22.8 0-43.9 7.2-57.6 13.2-3 1.3-6.7.3-8.3-2.2-1.6-2.6-.4-5.7 2.6-7 14.9-6.5 38-14.3 63.3-14.3 21 0 43.2 6.8 70 15.8 3.2 1.1 4.7 4.1 3.5 6.7-1.1 2-3.4 3.2-5.8 3.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m535.8 320.2h-31.2c-8.6 0-15.6-7-15.6-15.6 0-7.8 5.7-14.2 13.1-15.4 3.5-6.5 10.4-10.6 18.1-10.6s14.6 4.1 18.1 10.6c7.4 1.2 13.1 7.7 13.1 15.4 0 8.6-7 15.6-15.6 15.6zm-31.9-21c-2.2.2-4.5 2.5-4.5 5.4s2.3 5.2 5.2 5.2h31.2c2.9 0 5.2-2.3 5.2-5.2s-2.3-5.2-5.2-5.2c-1.3.4-2.4.2-3.6-.4s-1.8-1.8-2.2-3.1c-1.3-4.1-5.2-6.9-9.7-6.9s-8.4 2.8-9.7 6.9c-.4 1.3-1.4 2.4-2.6 3-1.4.6-2.8.7-4.1.3z"/><g stroke="#000" stroke-miterlimit="10" stroke-width="19"><path d="m251.7 216.1v249.8z"/><path d="m429.9 216.1v249.8z"/></g></svg>',
            'keywords'          => array( 'block', 'curated', 'post' ),
            'example'           => array(
                'attributes'    => array(
                    'mode'      => 'preview',
                    'data'      => array(
                        'is_preview'    => true,
                        'preview_img'  => plugin_dir_url( __FILE__ ) . 'image-preview/3-curated-preview.jpg'
                    ),
                ),
            ),
        ));

        acf_register_block_type(array(
            'name'              => 'one_tall_two_short_curated',
            'title'             => __('One Tall Two Short Curated', 'acf-blocks'),
            'description'       => __('Select 1 tall post and two short posts in a single section', 'acf-blocks'), 
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/curated-posts/2-1-curated.php',
            'category'          => 'jci-category',
            'mode'              => 'preview',
            'icon'              => '<svg enable-background="new 0 0 682 682" viewBox="0 0 682 682" xmlns="http://www.w3.org/2000/svg"><path d="m579.3 465.9h-476.6c-18.8 0-34-15.3-34-34.1v-181.6c0-18.8 15.3-34.1 34-34.1h476.7c18.8 0 34 15.3 34 34.1v181.7c0 18.7-15.3 34-34.1 34zm-476.6-227.1c-6.2 0-11.3 5.1-11.3 11.4v181.7c0 6.2 5.1 11.4 11.3 11.4h476.7c6.2 0 11.3-5.1 11.3-11.4v-181.7c0-6.2-5.1-11.4-11.3-11.4z"/><path d="m335.6 422.7c-40.6 0-75.5-13.2-106.3-24.9-23.6-9-44-16.7-62.1-16.7-40.9 0-77.6 29.7-77.9 30-2.3 1.9-5.7 1.6-7.7-.6-1.9-2.2-1.7-5.5.6-7.3 1.6-1.3 40.1-32.5 85-32.5 20.1 0 41.4 8.1 66.1 17.4 29.9 11.3 63.8 24.2 102.3 24.2 3 0 5.4 2.3 5.4 5.2 0 2.8-2.4 5.2-5.4 5.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m376.8 406.9c-.6 0-1.3-.1-1.9-.4-21.6-8.5-39.4-15-55.3-15-19.3 0-37.1 7.2-48.7 13.2-2.6 1.3-5.7.3-7-2.2-1.3-2.6-.3-5.7 2.2-7 12.6-6.5 32.1-14.3 53.5-14.3 17.8 0 36.5 6.8 59.2 15.8 2.7 1.1 4 4.1 2.9 6.7-.8 2-2.8 3.2-4.9 3.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m156.5 299.4h-31.2c-8.6 0-15.6-7-15.6-15.6 0-7.8 5.7-14.2 13.1-15.4 3.5-6.5 10.4-10.6 18.1-10.6s14.6 4.1 18.1 10.6c7.4 1.2 13.1 7.7 13.1 15.4 0 8.6-7 15.6-15.6 15.6zm-31.9-21c-2.2.2-4.5 2.5-4.5 5.4s2.3 5.2 5.2 5.2h31.2c2.9 0 5.2-2.3 5.2-5.2s-2.3-5.2-5.2-5.2c-1.3.4-2.4.2-3.6-.4s-1.8-1.8-2.2-3.1c-1.3-4.1-5.2-6.9-9.7-6.9s-8.4 2.8-9.7 6.9c-.4 1.3-1.4 2.4-2.6 3-1.3.6-2.8.7-4.1.3z"/><path d="m589.5 445.6c-47.3 3.7-89.3-10-126.4-22.2-28.4-9.3-53-17.4-74-15.7-47.6 3.8-87.4 45.2-87.8 45.6-2.5 2.6-6.5 2.6-9 0s-2.5-6.9 0-9.5c1.7-1.8 43.4-45.3 95.8-49.5 23.5-1.9 49.1 6.5 78.7 16.3 36 11.8 76.8 25.2 121.7 21.6 3.5-.3 6.6 2.5 6.9 6.2s-2.5 6.9-5.9 7.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m592 419.8c-.8 0-1.5-.1-2.3-.4-25.6-8.5-46.6-15-65.4-15-22.8 0-43.9 7.2-57.6 13.2-3 1.3-6.7.3-8.3-2.2-1.6-2.6-.4-5.7 2.6-7 14.9-6.5 38-14.3 63.3-14.3 21 0 43.2 6.8 70 15.8 3.2 1.1 4.7 4.1 3.5 6.7-1.1 2-3.4 3.2-5.8 3.2z" stroke="#000" stroke-miterlimit="10" stroke-width="5"/><path d="m535.8 320.2h-31.2c-8.6 0-15.6-7-15.6-15.6 0-7.8 5.7-14.2 13.1-15.4 3.5-6.5 10.4-10.6 18.1-10.6s14.6 4.1 18.1 10.6c7.4 1.2 13.1 7.7 13.1 15.4 0 8.6-7 15.6-15.6 15.6zm-31.9-21c-2.2.2-4.5 2.5-4.5 5.4s2.3 5.2 5.2 5.2h31.2c2.9 0 5.2-2.3 5.2-5.2s-2.3-5.2-5.2-5.2c-1.3.4-2.4.2-3.6-.4s-1.8-1.8-2.2-3.1c-1.3-4.1-5.2-6.9-9.7-6.9s-8.4 2.8-9.7 6.9c-.4 1.3-1.4 2.4-2.6 3-1.4.6-2.8.7-4.1.3z"/><g stroke="#000" stroke-miterlimit="10" stroke-width="19"><path d="m251.7 216.1v249.8z"/><path d="m429.9 216.1v249.8z"/></g></svg>',
            'keywords'          => array( 'block', 'curated', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'embedded_article',
            'title'             => __('Embedded Article', 'acf-blocks'),
            'description'       => __('Select Article to Link Within Article Content', 'acf-blocks'), 
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/embedded-article/embedded-article.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'embed', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'pwl_carousel',
            'title'             => __('PWL Carousel', 'acf-blocks'),
            'description'       => __('Select Articles to Add to Carousel', 'acf-blocks'), 
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/pwl-carousel/pwl-carousel.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'embed', 'article', 'post' ),
            'example'           => array(
                'attributes'    => array(
                    'mode'      => 'preview',
                    'data'      => array(
                        'is_preview'    => true,
                        'preview_img'  => plugin_dir_url( __FILE__ ) . 'image-preview/places-we-love-preview.jpg'
                    ),
                ),
            ),
        ));

        acf_register_block_type(array(
            'name'              => 'place_topics',
            'title'             => __('Place Topics', 'acf-blocks'),
            'description'       => __('List Articles of a Particular Topic, Optionally Filtered by Place', 'acf-blocks'), 
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/place-topics/place-topics.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'embed', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'best_places_carousel',
            'title'             => __('Best Places Carousel', 'acf-blocks'),
            'description'       => __('Dynamically loads best places into a carousel, beginning with current best place', 'acf-blocks'),
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/best-places-carousel/best-places-carousel.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'embed', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'related_posts_list',
            'title'             => __('Related Posts List', 'acf-blocks'),
            'description'       => __('Curated list of posts for sidebar', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/related-posts-list/related-posts-list.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'place_with_related_posts',
            'title'             => __('Place with Related Posts', 'acf-blocks'),
            'description'       => __('Image of place next to three related article links', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/place-with-related-posts/place-with-related-posts.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
            'example'           => array(
                'attributes'    => array(
                    'mode'      => 'preview',
                    'data'      => array(
                        'is_preview'    => true,
                        'preview_img'  => plugin_dir_url( __FILE__ ) . 'image-preview/places-related-posts-preview.jpg'
                    ),
                ),
            ),
        ));

        acf_register_block_type(array(
            'name'              => 'category_or_curated_section',
            'title'             => __('Category or Curated Section', 'acf-blocks'),
            'description'       => __('Section with five curated posts or five category posts in random order', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/cat-or-curated/cat-or-curated.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
            'example'           => array(
                'attributes'    => array(
                    'mode'      => 'auto',
                    'data'      => array(
                        'is_preview'    => true,
                        'preview_img'  => plugin_dir_url( __FILE__ ) . 'image-preview/category-or-curated-preview.jpg'
                    ),
                ),
            ),
        ));

        // acf_register_block_type(array(
        //     'name'              => 'four article section',
        //     'title'             => __('Four Article Section', 'acf-blocks'),
        //     'description'       => __('Section with one article on the right and three on left'),
        //     'mode'              => 'preview',
        //     'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/four-articles/four-articles.php',
        //     'category'          => 'jci-category',
        //     'icon'              => 'layout',
        //     'keywords'          => array( 'block', 'related', 'article', 'post' ),
        // ));

        acf_register_block_type(array(
            'name'              => 'content_series',
            'title'             => __('Content Series', 'acf-blocks'),
            'description'       => __('Content series with one article on the left and three on right', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/content-series/content-series.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'content_series_list',
            'title'             => __('Content Series List', 'acf-blocks'),
            'description'       => __('Full list of articles from selected content series', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/content-series-list/content-series-list.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        // acf_register_block_type(array(
        //     'name'              => 'magazine_link',
        //     'title'             => __('ACF Magazine Link', 'acf-blocks'),
        //     'description'       => __('Magazine link sidebar. If none selected, queries magazine link from the magazine itself', 'acf-blocks'),
        //     'mode'              => 'preview',
        //     'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/magazine-link/magazine-link.php',
        //     'category'          => 'jci-category',
        //     'icon'              => 'layout',
        //     'keywords'          => array( 'block', 'related', 'article', 'post' ),
        // ));

        acf_register_block_type(array(
            'name'              => 'topics_masonry_list',
            'title'             => __('Topics Masonry List', 'acf-blocks'),
            'description'       => __('List articles in masonry design on topics page', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/topics-masonry-list/topics-masonry-list.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
            'supports'          => [
                'jsx'           => true
            ]
        ));

        acf_register_block_type(array(
            'name'              => 'best_place_masonry_list',
            'title'             => __('Best Place Masonry List', 'acf-blocks'),
            'description'       => __('List parent best place articles in masonry design', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/best-place-masonry/best-place-masonry.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
            'supports'          => [
                'jsx'           => true
            ]
        ));

        acf_register_block_type(array(
            'name'              => 'full_width_magazine_link',
            'title'             => __('Full Width Magazine Link', 'acf-blocks'),
            'description'       => __('In place content section, links to magazine post type.', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/fw-magazine-link/fw-magazine-link.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'sponsored_article_carousel',
            'title'             => __('Sponsored Article Carousel', 'acf-blocks'),
            'description'       => __('Carousel of sponsored articles', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/sponsored-carousel/sponsored-carousel.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'two_magazine_row',
            'title'             => __('Two Magazine Row', 'acf-blocks'),
            'description'       => __('Row of two magazines', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/two-magazine-row/two-magazine-row.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'three_magazine_row',
            'title'             => __('Three Magazine Row', 'acf-blocks'),
            'description'       => __('Row of three magazines', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/three-magazine-row/three-magazine-row.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'person',
            'title'             => __('Person Block', 'acf-blocks'),
            'description'       => __('Photo and info of a person', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/person/person.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'cc_call_to_action',
            'title'             => __('Connected Community Call to Action Block', 'acf-blocks'),
            'description'       => __('Link to Connected Community page and article. ', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/cc-cta/cc-cta.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'acf-info-box',
            'title'             => __('New Info Box', 'acf-blocks'),
            'description'       => __('ACF version of info box', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/acf-info-box/acf-info-box.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'cc-carousel',
            'title'             => __('Connected Communities Carousel', 'acf-blocks'),
            'description'       => __('Displays global CC articles in carousel', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/cc-carousel/cc-carousel.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'cc-local-list',
            'title'             => __('Local Connected Communities List', 'acf-blocks'),
            'description'       => __('Displays CC articles of the same state', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/cc-local-list/cc-local-list.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'cc-global-masonry-list',
            'title'             => __('Global Connected Communities Masonry', 'acf-blocks'),
            'description'       => __('Displays global CC articles', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/cc-global-masonry/cc-global-masonry.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));
        acf_register_block_type(array(
            'name'              => 'state-connected-communities',
            'title'             => __('State Connected Communities List', 'acf-blocks'),
            'description'       => __('For Connected Communities page. Displays list of states that have connected community cities.', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/state-cc-list/state-cc-list.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));
        acf_register_block_type(array(
            'name'              => 'Local Insights',
            'title'             => __('Local Insights', 'acf-blocks'),
            'description'       => __('Displays all local insights about the current city', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/local-insights/local-insights.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));
        acf_register_block_type(array(
            'name'              => 'Top 100 Coming Soon',
            'title'             => __('Top 100 Coming Soon', 'acf-blocks'),
            'description'       => __('Displays hero section for coming soon page', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/coming-soon/coming-soon.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));
        acf_register_block_type(array(
            'name'              => 'Brand Stories for Places',
            'title'             => __('Brand Stories for Places', 'acf-blocks'),
            'description'       => __('Displays sponsored posts for places', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/brand-stories/brand-stories.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'Journal Recent Posts',
            'title'             => __('Journal Recent Posts', 'acf-blocks'),
            'description'       => __('Displays recent posts', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/recent-posts/recent-posts.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'Find Your BP to Live',
            'title'             => __('Find Your BP to Live', 'acf-blocks'),
            'description'       => __('Displays tab view of articles on map', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/find-bp2l/find-bp2l.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        acf_register_block_type(array(
            'name'              => 'Find Your BP to Play/Work',
            'title'             => __('Find Your BP to Play/Work', 'acf-blocks'),
            'description'       => __('Displays tab view of articles on map', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/find-bp2pw/find-bp2pw.php',
            'category'          => 'jci-category',
            'icon'              => 'layout',
            'keywords'          => array( 'block', 'related', 'article', 'post' ),
        ));

        // acf_register_block_type(array(
        //     'name'              => 'listicle',
        //     'title'             => __('Listicle Block', 'acf-blocks'),
        //     'description'       => __('List style article with jump links', 'acf-blocks'),
        //     'mode'              => 'preview',
        //     'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/listicle/listicle.php',
        //     'category'          => 'jci-category',
        //     'icon'              => 'layout',
        //     'keywords'          => array( 'block', 'related', 'article', 'post' ),
        // ));

        // acf_register_block_type(array(
        //     'name'              => 'magazine_articles',
        //     'title'             => __('Magazine Articles', 'acf-blocks'),
        //     'description'       => __('List of all articles related to magazine'),
        //     'mode'              => 'preview',
        //     'render_template'   => plugin_dir_path( __FILE__ ) . 'blocks/magazine-articles/magazine-articles.php',
        //     'category'          => 'jci-category',
        //     'icon'              => 'layout',
        //     'keywords'          => array( 'block', 'related', 'article', 'post' ),
        // ));
    }
} ?>