<?php require(get_stylesheet_directory(  ).'/assets/lib/state_abbv.php'); 
$bptitle = get_the_title();
$comma = strpos($bptitle, ',');
$stateabbv = substr($bptitle, -2);
$state = strtolower($us_state_abbrevs_names[$stateabbv]);
$city = substr($bptitle, 0, $comma);
$statelisting = 'https://exprealty.com/us/'.strtolower($stateabbv);
$listing = $statelisting.'/'.strtolower(str_replace(' ','-',$city)).'/houses'; ?>
    
<?php echo '<h2>Thinking of Moving to '.$city.', '.ucwords($state).'?</h2>';
echo '<p>You’ll find listings of <a href="'.$listing.'" target="_blank">'.$city.'</a> homes for sale as well as all of <a href="'.$statelisting.'" target="_blank">'.ucwords($state).'</a> real estate on eXp Realty and you can refine your search.  These top-rated real estate agents in <a href="'.$listing.'" target="_blank">'.$city.'</a> are local eXperts and are ready to answer your questions about the best new neighborhoods.</p>'; ?>
   
