

        <?php 
        // global $is_handheld;
        if (have_rows('horizontal_slides')):
            $count = 1;
            $slide_num = count(get_field('horizontal_slides')); 
            $subtitle = get_field('global_subtitle'); ?>
            <!-- <div style="position: relative;"> -->
            <div class="horizontal-article-container" style="width: <?php echo wp_is_mobile() ? 100 : $slide_num * 100; ?>% ;">
            <?php // echo 'number is '.$slide_num;
            while(have_rows('horizontal_slides')): the_row();
            $slide_style = get_sub_field('slide_style');
            $slide_height = get_field('slide_height');
            $bkgrndImage = (get_sub_field('background_image') && $slide_style != 'inset') ? get_sub_field('background_image')['url'] : 'none';
            $title = get_sub_field('title');
            $text = get_sub_field('text');
            $text_color = get_sub_field('text_color');
            $background_color = get_sub_field('background_color');
            $horizontal_text_alignment = get_sub_field('horizontal_text_alignment');
            $vertical_text_alignment = get_sub_field('vertical_text_alignment');
            $hta =  $horizontal_text_alignment == 'right' ? 'right' : 'left'; 
            $vta = $vertical_text_alignment == 'top' ? 'top' : 'bottom'; 
            $vbp = get_sub_field('vertical_background_position');

            ?>
            <?php if ($slide_style == 'inset'): ?>
            <div class="horizontal-slide image-inset" id="slide<?php echo $count; ?>" style="background-color: <?php echo  $background_color; ?>; background-image: url('<?php echo  $bkgrndImage; ?>'); width: <?php echo wp_is_mobile() ? 100 : number_format(100/$slide_num, 4); ?>%; height: <?php echo  $slide_height; ?>px;">
                <div class="container" style="color: <?php echo $text_color; ?>">
                    <div class="inset-text">
                        <h4 class="h-subtitle"><?php echo $subtitle; ?></h4>
                        <h2><?php echo $title; ?></h2>
                        <p><?php echo $text; ?></p>
                    </div>
                    <div class="inset-image">
                        <img src="<?php echo get_sub_field('background_image') ? get_sub_field('background_image')['url'] : 'none'; ?>" alt="">
                    </div>
                </div>
                <?php //echo addSlideNav($count, $slide_num); ?>
            </div>
            <?php else: ?>
            <div class="horizontal-slide image-background" id="slide<?php echo $count; ?>" style="background-color: <?php echo  $background_color; ?>; background-image: url('<?php echo  $bkgrndImage; ?>'); background-position-y: <?php echo $vbp; ?>; width: <?php echo number_format(100/$slide_num, 4); ?>%;">
                <div class="overlay-container <?php echo $hta.' '.$vta; ?>">
                    <div class="horizontal-text-container" style="color: <?php echo $text_color; ?>">
                        <h4 class="h-subtitle"><?php echo $subtitle; ?></h4>
                        <h2><?php echo $title; ?></h2>
                        <p><?php echo $text; ?></p>
                    </div>
                </div>
                <?php //echo addSlideNav($count, $slide_num); ?>
            </div>
            <?php endif; ?>
            
            
            <?php $count++;
            endwhile; ?>
            </div><!-- horizontal-article-container  -->
            <div class="horizontal-nav" >
                    <?php
                    for ($i = 1; $i <= $slide_num; $i++) {
                        echo '<a href="#slide'.$i.'">'.$i.'</a>';
                    }
                     ?>
                </div> 
                </div>
            
            <!-- echo '<div class="nav-experiment"><ol><li>1</li><li>2</li><li>3</li></ol></div>'; -->
            <?php endif;
            $content_after_slide =  get_field('content_after_slide');
            if ($content_after_slide): ?>
            <div class="entry-content">
                <div class="wp-columns">
                    <div class="wp-column">
                        <?php echo $content_after_slide; ?>
                    </div>
                </div>
            </div>

           <?php endif;
         ?>
