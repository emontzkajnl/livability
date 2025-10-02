<?php $expire_date = do_shortcode( '[futureaction type=date dateformat="F j, Y"]'); ?>
<tr>
    <td style="max-width: 100px;"><?php echo the_post_thumbnail( 'thumb'); ?></td>
    <td style="max-width: 300px;"><p><a class="unstyle-link" href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(  ); ?></a></p></td>
    <td><p><?php echo get_post_status(); ?></p></td>
    <td>Published <?php echo get_the_date(); ?></td>
    <td>Expires <?php echo $expire_date; ?></td>
    <td></td>
</tr>