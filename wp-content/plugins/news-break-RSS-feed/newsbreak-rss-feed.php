<?php
/*
Plugin Name: News Break RSS Feed
Description: Creates a custom RSS feed for media pickups like News Break with specific namespaces and elements.
Version: 1.4
Author: Journal Communications, Inc.
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register custom RSS feed
function nb_rss_feed_init() {
    add_feed('newsbreak', 'nb_rss_feed_callback');
}
add_action('init', 'nb_rss_feed_init');

// Callback to generate the RSS feed
function nb_rss_feed_callback() {
    header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
    echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?'.'>';
    ?>
    <rss version="2.0"
        xmlns:nb="https://www.newsbreak.com/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:media="http://search.yahoo.com/mrss/">
        <channel>
            <title><?php bloginfo_rss('name'); ?></title>
            <link><?php bloginfo_rss('url'); ?></link>
            <description><?php bloginfo_rss('description'); ?></description>
            <language><?php bloginfo_rss('language'); ?></language>
            <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></pubDate>
            <?php
            // Define tags to exclude
            $exclude_tags = array('example1', 'example2', 'example3');
            
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 10,
                'tag__not_in' => array(),
                'has_password' => false, // Exclude password-protected posts
            );
            
            // Get term IDs for the tags to exclude
            foreach ($exclude_tags as $tag) {
                $term = get_term_by('slug', sanitize_title($tag), 'post_tag');
                if ($term) {
                    $args['tag__not_in'][] = $term->term_id;
                }
            }
            
            $query = new WP_Query($args);
            while ($query->have_posts()) : $query->the_post();
                // Get canonical URL
                $canonical_url = wp_get_canonical_url();
                if (!$canonical_url) {
                    $canonical_url = get_permalink();
                }
                // Get authors (supports co-authors if Co-Authors Plus plugin is active)
                $authors = array();
                if (function_exists('get_coauthors')) {
                    $coauthors = get_coauthors();
                    foreach ($coauthors as $coauthor) {
                        $authors[] = $coauthor->display_name;
                    }
                } else {
                    $authors[] = get_the_author();
                }
                // Get post content
                $content = get_the_content(null, false);
                $content = apply_filters('the_content', $content);
                // Prepend featured image to content if available
                $featured_image_html = '';
                if (has_post_thumbnail()) {
                    $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                    if ($thumbnail) {
                        $caption = get_the_post_thumbnail_caption();
                        $featured_image_html = '<figure><img src="' . esc_url($thumbnail[0]) . '" alt="' . esc_attr($caption ? $caption : get_the_title()) . '">' .
                                              ($caption ? '<figcaption>' . wp_kses_post($caption) . '</figcaption>' : '') .
                                              '</figure>';
                    }
                }
                // Parse content for other images and wrap in figure/figcaption
                $content = preg_replace_callback(
                    '/<img[^>]+src=["\'](.*?)["\'][^>]*>(?:<p[^>]*>(.*?)<\/p>)?/i',
                    function($matches) {
                        $img_src = $matches[1];
                        $caption = !empty($matches[2]) ? $matches[2] : '';
                        return '<figure><img src="' . esc_url($img_src) . '" alt="' . esc_attr($caption) . '">' .
                               ($caption ? '<figcaption>' . wp_kses_post($caption) . '</figcaption>' : '') .
                               '</figure>';
                    },
                    $content
                );
                // Combine featured image and content
                $content = $featured_image_html . $content;
                ?>
                <item>
                    <title><?php the_title_rss(); ?></title>
                    <link><?php echo esc_url($canonical_url); ?></link>
                    <guid isPermaLink="true"><?php echo esc_url($canonical_url); ?></guid>
                    <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
                    <?php foreach ($authors as $author) : ?>
                        <dc:creator><![CDATA[<?php echo esc_xml($author); ?>]]></dc:creator>
                    <?php endforeach; ?>
                    <description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
                    <content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
                    <?php
                    // Add featured image as thumbnail if available
                    if (has_post_thumbnail()) {
                        $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                        if ($thumbnail) {
                            ?>
                            <media:thumbnail url="<?php echo esc_url($thumbnail[0]); ?>" />
                            <?php
                        }
                    }
                    ?>
                </item>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </channel>
    </rss>
    <?php
}

// Flush rewrite rules on plugin activation
function nb_rss_feed_activate() {
    nb_rss_feed_init();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'nb_rss_feed_activate');

// Flush rewrite rules on plugin deactivation
function nb_rss_feed_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'nb_rss_feed_deactivate');
?>