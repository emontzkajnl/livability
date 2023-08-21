<?php 

    $title = get_the_title(  );
    $population = get_field('city_population');
    $income = str_replace(',','', get_field('city_household_income'));
    $income = intval($income);
    $homeValue = str_replace(',','', get_field('city_home_value')); 
    $homeValue = intval($homeValue);
    $startOverLink = get_permalink( 18680);
    $gtkLink = get_permalink(19038);
    $mymLink = get_permalink( 70453 );?>

    <p>Looking to move to <?php echo $title; ?>? You’ve come to the right place. Livability helps people find their perfect places to live, and we’ve got everything you need to know to decide if moving to <?php echo $title; ?> is right for you.</p>

    <p>Let’s start with the basics: <?php echo $title; ?> has a population of <?php echo $population; ?>. What about cost of living in <?php echo $title; ?>? The median income in <?php echo $title; ?> is $<?php echo number_format($income); ?> and the median home value is <?php echo '$'.number_format($homeValue); ?>.</p>
    
    <p>Read on to learn more about <?php echo $title; ?>, and if you’d like some tips and advice for making your big move, check out our <a href="<?php echo $mymLink; ?>">Make Your Move</a> page, where you’ll find all kinds of stories and insights including <a href="<?php echo $startOverLink; ?>">How to Start Over in a New City</a>, <a href="<?php echo $gtkLink; ?>">Tips for Getting to Know a New City Before You Move</a> and so much more.</p>