<?php $livscore = get_field('ls_livscore');
$ID = get_the_ID();
$civic = get_post_meta($ID, 'ls_civic', true);
$demographics = get_post_meta($ID, 'ls_demographics', true);
$economy = get_post_meta($ID, 'ls_economy', true);
$education = get_post_meta($ID, 'ls_education', true);
$health = get_post_meta($ID, 'ls_health', true);
$housing = get_post_meta($ID, 'ls_housing', true);
$infrastructure = get_post_meta($ID, 'ls_infrastructure', true); 
$amenities = get_post_meta($ID, 'amenities', true);
$how_we_calculate_link = get_the_permalink( '92097' );

?>
<style>
    .bp-data-image {
        background-image: url("<?php echo get_the_post_thumbnail_url($ID, 'medium_large'); ?>"); 
    }
</style>
<div class="bp-data-container">
    <div class="bp-data-image"></div>
    
    <?php if ($livscore): ?>
    <table class="livscore-table">
        <thead>
            <tr>
                <th colspan=2><span class="livscore">liv score</span><br /><span class="livscore-number"><?php echo $livscore; ?></span><br /><a href="<?php echo  $how_we_calculate_link; ?>" class="livscore-link">How We Calculate Our Data</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Civics</td>
                <td><?php echo $civic; ?></td>
            </tr>
            <tr>
                <td>Demographics</td>
                <td><?php echo $demographics; ?></td>
            </tr>
            <tr>
                <td>Economy</td>
                <td><?php echo $economy; ?></td>
            </tr>
            <tr>
                <td>Education</td>
                <td><?php echo $education; ?></td>
            </tr>
            <tr>
                <td>Health</td>
                <td><?php echo $health; ?></td>
            </tr>
            <tr>
                <td>Housing</td>
                <td><?php echo $housing; ?></td>
            </tr>
            <tr>
                <td>Infrastructure</td>
                <td><?php echo $infrastructure; ?></td>
            </tr>                                                          
        </tbody>
    </table>
    <?php endif; ?>
</div>