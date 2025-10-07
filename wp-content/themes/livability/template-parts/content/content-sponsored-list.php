<?php
 $expire_date = do_shortcode( '[futureaction type=date dateformat="F j, Y"]');
$place = get_field('place_relationship');
$sponsor_name = get_field('sponsor_name');
$sponsor_url = get_field('sponsor_url') ? get_field('sponsor_url') : '' ;
?>
<tr>
    <td style="max-width: 100px;"><?php echo the_post_thumbnail( 'thumb'); ?></td>
    <td><?php echo get_the_title($place[0]); ?></td>
    <td style="max-width: 300px;"><a class="unstyle-link" href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(  ); ?></a></td>
    <td><?php echo get_post_status(); ?></td>
    <td><?php echo $sponsor_name ? '<a class="unstyle-link" href="'.$sponsor_url.'">'.$sponsor_name.'</a>' : 'no sponsor name'; ?></td>
    <td>Published <?php echo get_the_date(); ?></td>
    <td><?php echo $expire_date ? 'Expires: '.$expire_date : 'No Expiration'; ?></td>
</tr>