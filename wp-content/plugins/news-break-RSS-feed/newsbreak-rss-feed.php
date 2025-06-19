<?php
/*
Plugin Name: News Break RSS Feed
Description: Creates a custom RSS feed for media pickups like News Break with specific namespaces and elements.
Version: 1.5
Author: Journal Communications, Inc.
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

if ( ! function_exists( 'get_attachment_id' ) ) {
    /**
     * Get the Attachment ID for a given image URL.
     *
     * @link   http://wordpress.stackexchange.com/a/7094
     *
     * @param  string $url
     *
     * @return boolean|integer
     */
    function get_attachment_id( $url ) {

        $dir = wp_upload_dir();

        // baseurl never has a trailing slash
        if ( false === strpos( $url, $dir['baseurl'] . '/' ) ) {
            // URL points to a place outside of upload directory
            return false;
        }

        $file  = basename( $url );
        $query = array(
            'post_type'  => 'attachment',
            'fields'     => 'ids',
            'meta_query' => array(
                array(
                    'key'     => '_wp_attached_file',
                    'value'   => $file,
                    'compare' => 'LIKE',
                ),
            )
        );

        // query attachments
        $ids = get_posts( $query );

        if ( ! empty( $ids ) ) {

            foreach ( $ids as $id ) {
				$full_img = wp_get_attachment_image_src( $id, 'full' );
				$full_img_src = array_shift($full_img);
                // first entry of returned array is the URL
                if ( $url === $full_img_src ) {
					return $id;
				}
                    
            }
        }

        $query['meta_query'][0]['key'] = '_wp_attachment_metadata';

        // query attachments again
        $ids = get_posts( $query );

        if ( empty( $ids) )
            return false;

        foreach ( $ids as $id ) {

            $meta = wp_get_attachment_metadata( $id );

            foreach ( $meta['sizes'] as $size => $values ) {
				$image_src = wp_get_attachment_image_src( $id, $size ); 
                // if ( $values['file'] === $file && $url === array_shift( wp_get_attachment_image_src( $id, $size ) ) )
                    // return $id;
				if ( $values['file'] === $file && $url === array_shift( $image_src ) ) return $id; 
            }
        }

        return false;
    }
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
                        $thumb_place_name = get_field('img_place_name', get_post_thumbnail_id());
                        $thumb_byline = get_field('img_byline', get_post_thumbnail_id());
                        $caption = get_the_post_thumbnail_caption();
                        $featured_image_html = '<figure><img src="' . esc_url($thumbnail[0]) . '" alt="' . esc_attr($caption ? $caption : get_the_title()) . '">' .
                                            ' Photo Credit: '.($thumb_place_name ? $thumb_place_name : '' ). ' / ' . ($thumb_byline ? strip_tags($thumb_byline) : '') . '<br />' . 
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
                        $post_image_id = get_attachment_id( $src );
                        
                        return '<figure><img src="' . esc_url($img_src) . '" alt="' . esc_attr($caption) . '">' .
                               ($caption ? '<figcaption>' . wp_kses_post($caption) . '</figcaption>' : '') .
                               ($post_image_id ? ' Photo Credit '.get_field('img_place_name', $post_image_id).' / '.strip_tags(get_field('img_byline', $post_image_id)) : '') .
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
				  <nb:scripts>
					<![CDATA[
					  <script>
						(function(i,s,o,g,r,a,m){i['GoogleTagManagerObject']=r;i[r]=i[r]||function(){
						(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
						m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
						})(window,document,'script','https://www.googletagmanager.com/gtag/js?id=G-HVXCXLTZK1','gtag');
						window.dataLayer = window.dataLayer || [];
						function gtag(){dataLayer.push(arguments);}
						gtag('js', new Date());
						gtag('config', 'G-HVXCXLTZK1', {
						  'cookie_flags': 'SameSite=None;Secure' // Optional: for restricted environments
						});
						gtag('event', 'rss_view', {
						  'feed_item': '<?php echo esc_js(get_the_title()); ?>',
						  'feed_source': 'newsbreak',
						  'page_path': '<?php echo esc_js(get_permalink()); ?>',
						  'campaign_name': 'News Break',
						  'campaign_source': 'News Break',
						  'campaign_medium': 'Article',
						  'referrer': 'https://www.newsbreak.com/'
						});
					  </script>
					]]>
				  </nb:scripts>
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