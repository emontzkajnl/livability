<?php 

// List of accolades for top of place pages

$accolades = get_field('accolades');
$is_client = get_field('client_place');

if ($is_client || $accolades):
    echo '<ul class="place-accolades">';
    echo $is_client ? '<li class="place-accolades__client">Livability Partner</li>' : '';
    if ($accolades) {
        foreach($accolades as $a ) {
            $link = $a['link'];
            if ($link) {
                echo '<li class="place-accolades__accolade"><a class="unstyle-link" href="'.$link.'">'.$a['title'].'</a></li>';
            } else {
                echo '<li class="place-accolades__accolade">'.$a['title'].'</li>';
            }
        }
    }
echo '</ul>';
 endif; 