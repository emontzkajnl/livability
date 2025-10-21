<?php
global $post, $top_area;

// Get the dynamically assigned image size for this layout
$image_size = houzez_get_image_size_for('property_detail_v3-4');

$images_ids = get_post_meta($post->ID, 'fave_property_images', false);

// Ensure WordPress featured image is always first in the gallery array
$featured_image_id = get_post_thumbnail_id($post->ID);
if (!empty($featured_image_id)) {
    // Remove featured image if it already exists in the array to avoid duplicates
    $images_ids = array_diff($images_ids, [$featured_image_id]);
    // Prepend featured image to the beginning
    array_unshift($images_ids, $featured_image_id);
} elseif (!empty($images_ids[0])) {
    // Fallback: use first gallery image as featured if no WordPress featured image exists
    $featured_image_id = $images_ids[0];
}

$gallery_caption = houzez_option('gallery_caption', 0);
$property_gallery_popup_type = houzez_get_popup_gallery_type();
$gallery_token = wp_generate_password(5, false, false);

$builtin_gallery_class = ' houzez-trigger-popup-slider-js';
$dataModal = 'href="#" data-bs-toggle="modal" data-bs-target="#property-lightbox"';

$featured_image_data = wp_get_attachment_image_src($featured_image_id, $image_size);
$featured_image_url = $featured_image_data[0] ?? '';
$featured_image_width = $featured_image_data[1] ?? '';
$featured_image_height = $featured_image_data[2] ?? '';
$featured_image_alt = get_post_meta($featured_image_id, '_wp_attachment_image_alt', true);

if( !empty($images_ids) && count($images_ids) ) {
    $images_ids = array_unique($images_ids);
    $total_images = count($images_ids);
?>
<div class="top-gallery-section">
    <?php if(!empty($featured_image_url)): ?>
    <!-- Featured image placeholder - shows immediately for fast LCP -->
    <div id="gallery-featured-placeholder" class="gallery-featured-placeholder">
        <img class="img-fluid w-100 h-100"
             src="<?php echo esc_url($featured_image_url); ?>"
             <?php if($featured_image_width && $featured_image_height): ?>width="<?php echo esc_attr($featured_image_width); ?>" height="<?php echo esc_attr($featured_image_height); ?>"<?php endif; ?>
             fetchpriority="high"
             decoding="async"
             alt="<?php echo esc_attr($featured_image_alt); ?>"
             style="object-fit: cover;" />
    </div>
    <?php endif; ?>

    <div id="property-gallery-js" class="listing-slider" itemscope itemtype="http://schema.org/ImageGallery">
        <?php
        $is_first_image = true;
        foreach( $images_ids as $image_id ) {
            $image_data = wp_get_attachment_image_src($image_id, $image_size);
			$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
			$image_title = get_the_title($image_id);
			$image_caption = wp_get_attachment_caption($image_id);
			$image_meta = wp_get_attachment_metadata($image_id);

            $thumb = wp_get_attachment_image_src($image_id, 'houzez-item-image-6');

            // Skip this iteration if image_data is false
            if(!$image_data) {
                continue;
            }

            $image_url = $image_data[0] ?? '';
			$thumb_url = $thumb[0] ?? '';
			$image_width = $image_data[1] ?? '';
			$image_height = $image_data[2] ?? '';

			if( $property_gallery_popup_type == 'photoswipe' ) {
                $full_image = wp_get_attachment_image_src($image_id, 'full');
                $full_image_url = $full_image[0] ?? '';
				$dataModal = 'href="#" data-src="'.esc_url($full_image_url).'" data-houzez-fancybox data-fancybox="gallery-v3-4"';
				$builtin_gallery_class = '';
			}

			// All slider images use lazy loading since placeholder already has fetchpriority="high"
			$loading_attr = 'loading="lazy"';
			$is_first_image = false;
            ?>
            <div data-thumb="<?php echo esc_url( $thumb_url );?>" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                <a class="<?php echo $builtin_gallery_class; ?>" itemprop="contentUrl" data-gallery-item <?php echo $dataModal; ?>>
                    <img class="img-fluid houzez-gallery-img"
                         src="<?php echo esc_url($image_url); ?>"
                         <?php if($image_width && $image_height): ?>width="<?php echo esc_attr($image_width); ?>" height="<?php echo esc_attr($image_height); ?>"<?php endif; ?>
                         <?php echo $loading_attr; ?>
                         decoding="async"
                         itemprop="thumbnail"
                         alt="<?php echo esc_attr($image_alt); ?>"
                         title="<?php echo esc_attr($image_title); ?>" />
                </a>
                <?php
                if( !empty($image_caption) && $gallery_caption != 0 ) { ?>
                    <span class="hz-image-caption"><?php esc_attr($image_caption); ?></span>
                <?php } ?>
            </div>

        <?php } ?>
    </div>
</div><!-- top-gallery-section -->
<?php } else if( has_post_thumbnail() ) {
        $output = '';
        $thumb = houzez_get_image_by_id( get_post_thumbnail_id(), $image_size) ;
        $output .= '<div data-thumb="'.esc_url( $thumb[0] ).'">';
        $output .= '<a rel="gallery-1" data-slider-no="1" href="#" class="houzez-trigger-popup-slider-js" data-bs-toggle="modal" data-bs-target="#property-lightbox">
            <img class="img-fluid" src="'.esc_url( $thumb[0] ).'" alt="" title="">
        </a>';
        $output .= '</div>';
        echo $output;   

} else { ?>
<div class="top-gallery-section">
    <?php houzez_image_placeholder( $image_size ); ?>
</div>
<?php } ?>