<?php 
if (isset($_GET['city'])) {
    $city = $_GET['city'];
    $title = get_the_title(); ?>
    <div class="wp-block-jci-blocks-info-box city">
        <p>We're sorry, <?php echo ucfirst(htmlspecialchars($city)).', '.$title; ?> isn't on our site.</p>
        <a href="#city-list" class="components-button info-box-button">See Other <?php echo $title; ?> Cities</a>
    </div>
<?php }
