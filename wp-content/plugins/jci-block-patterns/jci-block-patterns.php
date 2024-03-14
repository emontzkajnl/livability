<?php
/**
 * Plugin Name: JCI Block Patterns
 * Description: Adds JCI custom block patterns to Gutenberg.
 * Version: 1.0
 * Author: Journal Communications, Inc.
 * Text Domain: jci-gutenberg-block-patterns
 */

 /**
 * Register Block Pattern Category.
 */
if ( function_exists( 'register_block_pattern_category' ) ) {

	register_block_pattern_category(
		'jci',
		array( 'label' => esc_html__( 'JCI Block Pattern', 'jci-gutenberg-block-patterns' ) )
	);
}

/**
 * Register Custom Block Styles
 */
function jci_register_block_patterns() {
    if ( function_exists( 'register_block_pattern' ) ) {
    /**
    * Register block patterns
    */

        // register_block_pattern( 
        //     'jci_gutenberg-block-patterns/top-one-hundred-template', 
        //     array( 'title'      => __( ' Page template for top 100 pages', 'jci-gutenberg-block-patterns' ),
        //             'categories'    => array( 'jci' ),
        //             'content'   => getBlockPattern('top-one-hundred-template'),
        //     ) 
        // );
        register_block_pattern(
            'jci_gutenberg-block-patterns/place-template',
            array( 'title'  => __( 'Page template for Places', 'jci-gutenberg-block-patterns'),
            'categories'    => array( 'jci'),
            'content'       => getBlockPattern('place-template'),
            )
        );

        register_block_pattern( 'jci_gutenberg-block-patterns/magazine-template',
            array( 'title'  => __('Template for Magazine Post', 'jci-gutenberg-block-patterns'),
            'categories'    => array( 'jci'),
            'content'       => getBlockPattern('magazine-template'),
            )  );

        // register_block_pattern( 
        //     'jci_gutenberg-block-patterns/best-places-single-template',
        //     array( 'title'  => __('Template for Single Best Place', 'jci-gutenberg-block-patterns'),
        //     'categories'    => array( 'jci'),
        //     'content'       => getBlockPattern('best-places-single-template'),
        //     ) );

        // register_block_pattern( 
        //     'jci_gutenberg-block-patterns/single-post-template',
        //     array( 'title'  => __('Template for Single Posts', 'jci-gutenberg-block-patterns'),
        //     'categories'    => array('jci'),
        //     'content'       => getBlockPattern('single-post-template'),
        //     )
        // );

        register_block_pattern(
            'jci_gutenberg-block-patterns/topic-page-template',
            array('title'   => __('Template for Topic Pages', 'jci-gutenberg-block-patterns'),
            'categories'    => array('jci'),
            'content'       => getBlockPattern('topic-page-template'),
            )
        );

        register_block_pattern(
            'jci_gutenberg-block-patterns/basic-page-layout',
            array( 'title'  => __('Template for Page Layout', 'jci_register_block_patterns'),
            'categories'    => array('jci'),
            'content'       => getBlockPattern('basic-page-layout'),
            )
        );

        register_block_pattern(
            'jci_gutenberg-block-patterns/2024-top-100-content',
            array( 'title'  => __('Template for Page Layout', 'jci_register_block_patterns'),
            'categories'    => array('jci'),
            'content'       => getBlockPattern('2024-top-100-content'),
            )
        );

  
    }
}
add_action( 'init', 'jci_register_block_patterns' );



/*
	Helper function to return block pattern based on the pattern_name
*/
function getBlockPattern($pattern_name){

    switch ($pattern_name) {
      case "top-one-hundred-template":
        return '<!-- wp:columns {"className":"full-width-off-white"} -->
                <div class="wp-block-columns full-width-off-white"><!-- wp:column -->
                <div class="wp-block-column"><!-- wp:jci-blocks/ad-area-one /--></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->
                
                <!-- wp:columns {"className":"liv-columns"} -->
                <div class="wp-block-columns liv-columns"><!-- wp:column {"className":"\\\u0022wp-block-column\\\u0022"} -->
                <div class="wp-block-column \&quot;wp-block-column\&quot;"><!-- wp:columns {"className":"liv-columns-2"} -->
                <div class="wp-block-columns liv-columns-2"><!-- wp:column {"className":"\\\u0022wp-block-column\\\u0022"} -->
                <div class="wp-block-column \&quot;wp-block-column\&quot;"><!-- wp:block {"ref":62583} /--></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"className":"\\\u0022wp-block-column\\\u0022"} -->
                <div class="wp-block-column \&quot;wp-block-column\&quot;"><!-- wp:yoast-seo/breadcrumbs /-->
                
                <!-- wp:heading {"level":1,"className":"h3"} -->
                <h1 class="h3" id="h-"></h1>
                <!-- /wp:heading --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"className":"\\\u0022wp-block-column\\\u0022"} -->
                <div class="wp-block-column \&quot;wp-block-column\&quot;"><!-- wp:block {"ref":62584} /--></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->
                
                <!-- wp:jci-blocks/onehundred-list -->
                <div class="wp-block-jci-blocks-onehundred-list jci-block-placeholder"><p>One Hundred List Placeholder</p></div>
                <!-- /wp:jci-blocks/onehundred-list -->';
        break;

      case "place-template":
        return '<!-- wp:columns {"className":"full-width-off-white"} -->
            <div class="wp-block-columns full-width-off-white"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/ad-area-one /--></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:columns {"className":"liv-columns"} -->
            <div class="wp-block-columns liv-columns"><!-- wp:column {"className":"wp-block-column"} -->
            <div class="wp-block-column"><!-- wp:columns {"className":"liv-columns-2"} -->
            <div class="wp-block-columns liv-columns-2"><!-- wp:column {"className":"wp-block-column"} -->
            <div class="wp-block-column"><!-- wp:block {"ref":62583} /-->
            
            <!-- wp:acf/related-posts-list {"id":"block_60afbf5404521","name":"acf/related-posts-list","align":"","mode":"edit"} /--></div>
            <!-- /wp:column -->
            
            <!-- wp:column {"className":"wp-block-column"} -->
            <div class="wp-block-column \&quot;wp-block-column\&quot;"><!-- wp:jci-blocks/breadcrumbs -->
            <div class="wp-block-jci-blocks-breadcrumbs jci-block-placeholder"><p>Breadcrumbs Placeholder</p></div>
            <!-- /wp:jci-blocks/breadcrumbs -->
            
            <!-- wp:jci-blocks/post-title {"content":"","className":"wp-block-jci-blocks-post-title"} /-->
            
            <!-- wp:paragraph -->
            <p></p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns --></div>
            <!-- /wp:column -->
            
            <!-- wp:column {"className":"wp-block-column"} -->
            <div class="wp-block-column \&quot;wp-block-column\&quot;"><!-- wp:block {"ref":62584} /--></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:jci-blocks/quick-facts -->
            <div class="wp-block-jci-blocks-quick-facts jci-block-placeholder"><p>Quick Facts Placeholder</p></div>
            <!-- /wp:jci-blocks/quick-facts -->
            
            <!-- wp:block {"ref":62582} /-->';
        break;

      case "magazine-template":
        return '<!-- wp:columns -->
            <div class="wp-block-columns"><!-- wp:column {"className":"full-width-off-white"} -->
            <div class="wp-block-column full-width-off-white"><!-- wp:jci-blocks/ad-area-one -->
            <div class="wp-block-jci-blocks-ad-area-one jci-block-placeholder"><p>Ad Area One Placeholder</p></div>
            <!-- /wp:jci-blocks/ad-area-one --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:columns {"className":"liv-columns"} -->
            <div class="wp-block-columns liv-columns"><!-- wp:column {"className":"\\\u0022wp-block-column\\\u0022"} -->
            <div class="wp-block-column \&quot;wp-block-column\&quot;"><!-- wp:columns {"className":"liv-columns-2"} -->
            <div class="wp-block-columns liv-columns-2"><!-- wp:column -->
            <div class="wp-block-column"></div>
            <!-- /wp:column -->
            
            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/breadcrumbs -->
            <div class="wp-block-jci-blocks-breadcrumbs jci-block-placeholder"><p>Breadcrumbs Placeholder</p></div>
            <!-- /wp:jci-blocks/breadcrumbs -->
            
            <!-- wp:jci-blocks/post-title /--></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:columns {"className":"liv-columns-2"} -->
            <div class="wp-block-columns liv-columns-2"><!-- wp:column {"className":"\\\u0022wp-block-column\\\u0022"} -->
            <div class="wp-block-column \&quot;wp-block-column\&quot;"><!-- wp:block {"ref":62583} /--></div>
            <!-- /wp:column -->
            
            <!-- wp:column {"className":"\\\u0022wp-block-column\\\u0022"} -->
            <div class="wp-block-column \&quot;wp-block-column\&quot;"><!-- wp:paragraph -->
            <p></p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns --></div>
            <!-- /wp:column -->
            
            <!-- wp:column {"className":"\\\u0022wp-block-column\\\u0022"} -->
            <div class="wp-block-column \&quot;wp-block-column\&quot;">
            </div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:jci-blocks/magazine -->
            <div class="wp-block-jci-blocks-magazine jci-block-placeholder"><p>Magazine Placeholder</p></div>
            <!-- /wp:jci-blocks/magazine -->
            
            <!-- wp:jci-blocks/magazine-articles -->
            <div class="wp-block-jci-blocks-magazine-articles jci-block-placeholder"><p>Magazine Articles Placeholder</p></div>
            <!-- /wp:jci-blocks/magazine-articles -->
            
            <!-- wp:acf/pwl-carousel {"id":"block_60902a81560f7","name":"acf/pwl-carousel","data":{"field_6078817dcf983":""},"align":"","mode":"edit"} /-->';
        break;

      case "single-post-template":
        return '<!-- wp:columns {"className":"full-width-off-white"} -->
            <div class="wp-block-columns full-width-off-white"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/ad-area-one -->
            <div class="wp-block-jci-blocks-ad-area-one jci-block-placeholder"><p>Ad Area One Placeholder</p></div>
            <!-- /wp:jci-blocks/ad-area-one --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:columns {"className":"liv-columns"} -->
            <div class="wp-block-columns liv-columns"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:columns {"className":"liv-columns-2"} -->
            <div class="wp-block-columns liv-columns-2"><!-- wp:column -->
            <div class="wp-block-column"></div>
            <!-- /wp:column -->
            
            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/breadcrumbs -->
            <div class="wp-block-jci-blocks-breadcrumbs jci-block-placeholder"><p>Breadcrumbs Placeholder</p></div>
            <!-- /wp:jci-blocks/breadcrumbs -->
            
            <!-- wp:jci-blocks/post-title /-->
            
            <!-- wp:jci-blocks/sponsored-by -->
            <div class="wp-block-jci-blocks-sponsored-by jci-block-placeholder"><p>Sponsored By Placeholder</p></div>
            <!-- /wp:jci-blocks/sponsored-by --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:columns {"className":"liv-columns-2"} -->
            <div class="wp-block-columns liv-columns-2"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:shortcode -->
            [addtoany]
            <!-- /wp:shortcode -->
            
            <!-- wp:acf/related-posts-list {"id":"block_60c3d1e16726e","name":"acf/related-posts-list","data":{"optional_title":"","_optional_title":"field_6092c27e4ffc6","post_list":"","_post_list":"field_6092c22b4ffc4"},"align":"","mode":"edit"} /--></div>
            <!-- /wp:column -->
            
            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/excerpt-and-post-author -->
            <div class="wp-block-jci-blocks-excerpt-and-post-author jci-block-placeholder"><p>Excerpt and Post Author Placeholder</p></div>
            <!-- /wp:jci-blocks/excerpt-and-post-author -->
            
            <!-- wp:jci-blocks/featured-image -->
            <div class="wp-block-jci-blocks-featured-image jci-block-placeholder"><p>Featured Image Placeholder</p></div>
            <!-- /wp:jci-blocks/featured-image -->
            
            <!-- wp:paragraph -->
            <p></p>
            <!-- /wp:paragraph -->
            
            <!-- wp:jci-blocks/author-block -->
            <div class="wp-block-jci-blocks-author-block jci-block-placeholder"><p>Author Block Placeholder</p></div>
            <!-- /wp:jci-blocks/author-block --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns --></div>
            <!-- /wp:column -->
            
            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/ad-area-two -->
            <div class="wp-block-jci-blocks-ad-area-two jci-block-placeholder"><p>Ad Area Two Placeholder</p></div>
            <!-- /wp:jci-blocks/ad-area-two --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->';
        break;

      case "best-places-single-template":
        return '<!-- wp:columns {\"className\":\"liv-columns\"} -->\n<div class=\"wp-block-columns liv-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:jci-blocks/best-place-data /-->\n<!-- wp:columns {\"className\":\"liv-columns-2\"} -->\n<div class=\"wp-block-columns liv-columns-2\"><!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column -->\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:freeform -->\n<p><span>Known as the “Rocket City,” </span><a href=\"https://livability.com/al/huntsville\" target=\"_blank\" rel=\"noopener\"><span><u>Huntsville</u></span></a><span> is home to the famous rocket at the </span><a href=\"https://rocketcenter.com/\" target=\"_blank\" rel=\"noopener\"><span><u>US Space \&amp; Rocket Center</u></span></a><span> and has a thriving aerospace industry. Once a sleepy town, Huntsville grew to notoriety during the 1960s Space Race and is now the fastest growing metro area in </span><a href=\"https://livability.com/al\" target=\"_blank\" rel=\"noopener\"><span><u>Alabama</u></span></a><span>. But it isn’t all out space exploration and rocket science here — Huntsville is a diverse city with a great food scene, repurposed buildings and history on every corner. Several universities call Huntsville home, too, including </span><a href=\"https://www.uah.edu/\" target=\"_blank\" rel=\"noopener\"><span><u>University of Alabama - Huntsville</u></span></a><span> and </span><a href=\"http://www.aamu.edu/\" target=\"_blank\" rel=\"noopener\"><span><u>Alabama A\&amp;M University</u></span></a><span>. Additionally, the cost of living in Huntsville is below the national average.\&nbsp;</span></p>\n<p><strong><span>Top Industries and Employers: </span></strong><span><span>The top industries in Huntsville are aerospace and military technology as well as manufacturing.</span></span></p>\n<p><strong><span>Best Coffee Shop: </span></strong><a href=\"http://www.alchemyhsv.com/\" target=\"_blank\" rel=\"noopener\"><span><u>Alchemy</u></span></a></p>\n<p><strong><span>Best Local Beer/Brewery: </span></strong><a href=\"https://www.yellowhammerbrewery.com/\" target=\"_blank\" rel=\"noopener\"><span><u>Yellowhammer Brewing’s</u></span></a><span> Groovy Don’s Groovy IPA is a must-try.\&nbsp;</span></p>\n<p><strong><span>Must-Have Meal:</span></strong><span> The BLT of Curtis Loew from the </span><a href=\"https://www.ilovebacontruck.com/\" target=\"_blank\" rel=\"noopener\"><span><u>I Love Bacon Food Truck</u></span></a><span>. Yes — Huntsville has an entire food truck devoted to bacon and it is just as amazing as it sounds.\&nbsp;</span></p>\n<p><strong><span>Best Co-Working Space: </span></strong><a href=\"https://www.huntsvillewest.com/\" target=\"_blank\" rel=\"noopener\"><span><u>Huntsville West</u></span></a><span> was once home to an elementary school but today it’s a co-working space catering to freelancers, artists and startups who need flexibility, fast wi-fi and free coffee.\&nbsp;\&nbsp;\&nbsp;</span></p>\n<p><strong><span>Best Meetup Spot (When Meeting Up Is a Thing Again)</span></strong><span>: Meet up with your group at any of the bars or eateries located inside of </span><a href=\"https://campus805.com/\" target=\"_blank\" rel=\"noopener\"><span><u>Campus 805</u></span></a><span>, the “coolest middle school in the nation.”\&nbsp;</span></p>\n<p><strong><span>Creative Hub:</span></strong><span> </span><a href=\"https://lowemill.art/\" target=\"_blank\" rel=\"noopener\"><span><u>Lowe Mill ARTS \&amp; Entertainment</u></span></a><span> is the largest, privately-owned arts facility in the South and the re-worked building now houses 150 working studios for more than 200 artists, seven art galleries, four performance venues, a multi-use theater and restaurants.\&nbsp;\&nbsp;</span></p>\n<p><strong><span>Favorite Weekend Activity: </span></strong><span>Huntsville is the craft beer capital of Alabama and the</span><strong><span> </span></strong><a href=\"https://www.facebook.com/craftbeertrail/\" target=\"_blank\" rel=\"noopener\"><span><u><span><span>Downtown Huntsville Craft Beer Trail</span></span></u></span></a><span> is the best way to sample what the city has to offer. </span><span><span>Enjoy any (or all!) of the eleven local breweries on the map, including </span></span><a href=\"https://rocketrepublicbrewing.com/huntsville/\" target=\"_blank\" rel=\"noopener\"><span><u><span><span>Rocket Republic Brewing</span></span></u></span></a><span><span>, </span></span><a href=\"https://straighttoale.com/\" target=\"_blank\" rel=\"noopener\"><span><u><span><span>Straight to Ale</span></span></u></span></a><span><span> and </span></span><a href=\"http://www.saltynutbrewery.com/\" target=\"_blank\" rel=\"noopener\"><span><u><span><span>Salty Nut Brewery</span></span></u></span></a><span><span>.</span></span></p>\n<p><strong><span>Free Way to Have Fun: </span></strong><span>Spend the day looking for all 14 ducks hidden around the city of Huntsville as part of the </span><a href=\"https://www.huntsville.org/listing/lucky-duck-scavenger-hunt/1284/\" target=\"_blank\" rel=\"noopener\"><span><u>Lucky Duck Scavenger Hunt</u></span></a><span>.\&nbsp;</span></p>\n<p><strong><span>Local Dream Job: </span></strong><span><span>Counselor at </span></span><a href=\"https://www.spacecamp.com/\" target=\"_blank\" rel=\"noopener\"><span><u><span><span>Space Camp</span></span></u></span></a><span><span>, because who didn’t dream of being an astronaut when they grow up?</span></span></p>\n<p><strong><span>Read More:</span></strong>\&nbsp;<br>\n<a href=\"https://livability.com/al/huntsville/careers-opportunities/why-huntsville-is-the-best-place-in-america-to-pursue-a-stem\" target=\"_blank\" rel=\"noopener\"><span><u>Why Huntsville is the Best Place in America to Pursue a STEM Career</u></span></a><strong>\&nbsp;</strong><br>\n<a href=\"https://livability.com/al/affordable-places-to-live/5-most-affordable-cities-in-alabama\" target=\"_blank\" rel=\"noopener\"><span><u>5 Most Affordable Cities in Alabama</u></span></a><strong>\&nbsp;</strong><br>\n<a href=\"https://livability.com/topics/beyond-silicon-valley-5-up-and-coming-tech-hotspots\" target=\"_blank\" rel=\"noopener\"><span><u>Beyond Silicon Valley: Huntsville is an Up-and-Coming Tech Hotspots</u></span></a></p>\n<p><em><span>- Cara Sanders</span></em></p>\n<!-- /wp:freeform --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph -->\n<p>Add space here.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:acf/best-places-carousel {\"id\":\"block_608b2ed433681\",\"name\":\"acf/best-places-carousel\",\"align\":\"\",\"mode\":\"preview\"} /-->';
         break;

         case "topic-page-template": 
            return '<!-- wp:columns {"className":"full-width-off-white"} -->
            <div class="wp-block-columns full-width-off-white"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/ad-area-one /--></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:columns {"className":"liv-columns"} -->
            <div class="wp-block-columns liv-columns"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:columns {"className":"liv-columns-2"} -->
            <div class="wp-block-columns liv-columns-2"><!-- wp:column -->
            <div class="wp-block-column"></div>
            <!-- /wp:column -->
            
            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/post-title {"content":""} /--></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:columns {"className":"liv-columns-2"} -->
            <div class="wp-block-columns liv-columns-2"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:shortcode -->
            [addtoany]
            <!-- /wp:shortcode -->
            
            <!-- wp:acf/related-posts-list {"id":"block_60c3d1e16726e","name":"acf/related-posts-list","data":{"optional_title":"","_optional_title":"field_6092c27e4ffc6","post_list":"","_post_list":"field_6092c22b4ffc4"},"align":"","mode":"edit"} /--></div>
            <!-- /wp:column -->
            
            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:paragraph -->
            <p></p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns --></div>
            <!-- /wp:column -->
            
            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/ad-area-two -->
            <div class="wp-block-jci-blocks-ad-area-two jci-block-placeholder"><p>Ad Area Two Placeholder</p></div>
            <!-- /wp:jci-blocks/ad-area-two --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:columns -->
            <div class="wp-block-columns"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:acf/topics-masonry-list {"id":"block_60de30fd567d8","name":"acf/topics-masonry-list","data":{"field_60de3066429f0":"","field_60de307c429f1":""},"align":"","mode":"edit"} /--></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->';
            break;

        case "basic-page-layout": 
            return '<!-- wp:columns {"className":"full-width-off-white"} -->
            <div class="wp-block-columns full-width-off-white"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/ad-area-one /--></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:columns {"className":"liv-columns"} -->
            <div class="wp-block-columns liv-columns"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:columns {"className":"liv-columns-2"} -->
            <div class="wp-block-columns liv-columns-2"><!-- wp:column -->
            <div class="wp-block-column"></div>
            <!-- /wp:column -->
            
            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/breadcrumbs -->
            <div class="wp-block-jci-blocks-breadcrumbs jci-block-placeholder"><p>Breadcrumbs Placeholder</p></div>
            <!-- /wp:jci-blocks/breadcrumbs -->
            
            <!-- wp:jci-blocks/post-title /--></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->
            
            <!-- wp:columns {"className":"liv-columns-2"} -->
            <div class="wp-block-columns liv-columns-2"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:shortcode -->
            [addtoany]
            <!-- /wp:shortcode --></div>
            <!-- /wp:column -->
            
            <!-- wp:column -->
            <div class="wp-block-column"></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns --></div>
            <!-- /wp:column -->
            
            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:jci-blocks/ad-area-two /--></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->';
        case "2024-top-100-content":
            return '<!-- wp:group {"layout":{"type":"constrained"}} -->
            <div class="wp-block-group"><!-- wp:columns -->
            <div class="wp-block-columns"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:heading -->
            <h2 class="wp-block-heading" id="h-what-make-city-state-a-best-place-to-live-in-2024">What make City, State a Best Place to Live in 2024?</h2>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph -->
            <p>Add content here.</p>
            <!-- /wp:paragraph -->
            
            <!-- wp:jci-blocks/livscore-block /-->
            
            <!-- wp:heading -->
            <h2 class="wp-block-heading" id="h-things-to-do-in-city-state">Things to Do in City, State</h2>
            <!-- /wp:heading -->
            
            <!-- wp:yoast/faq-block {"questions":[{"id":"faq-question-1710431183578","question":["Enter question"],"answer":["Answer question"],"jsonQuestion":"Enter question","jsonAnswer":"Answer question"}]} -->
            <div class="schema-faq wp-block-yoast-faq-block"><div class="schema-faq-section" id="faq-question-1710431183578"><strong class="schema-faq-question">Enter question</strong> <p class="schema-faq-answer">Answer question</p> </div> </div>
            <!-- /wp:yoast/faq-block -->
            
            <!-- wp:heading -->
            <h2 class="wp-block-heading" id="h-the-local-and-state-economy-in-city-state">The Local and State Economy in City, State</h2>
            <!-- /wp:heading -->
            
            <!-- wp:yoast/faq-block {"questions":[{"id":"faq-question-1710431183578","question":["Enter question"],"answer":["Answer question"],"jsonQuestion":"Enter question","jsonAnswer":"Answer question"}]} -->
            <div class="schema-faq wp-block-yoast-faq-block"><div class="schema-faq-section" id="faq-question-1710431183578"><strong class="schema-faq-question">Enter question</strong> <p class="schema-faq-answer">Answer question</p> </div> </div>
            <!-- /wp:yoast/faq-block --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns --></div>
            <!-- /wp:group -->';

      default:
        return "";
    }
}

?>