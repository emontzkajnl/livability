<?php
use \Yoast\WP\SEO\Generators\Schema\Abstract_Schema_Piece;
use \Yoast\WP\SEO\Config\Schema_IDs;

class JCI_Place extends Abstract_Schema_Piece {
	/**
	 * A value object with context variables.
	 *
	 * @var WPSEO_Schema_Context
	 */
	public $context;


	/**
	 * Determines whether or not a piece should be added to the graph.
	 *
	 * @return bool
	 */
	public function is_needed() {

		if( is_singular( array('liv_place', 'place_category_page') ) ) {
				return true;
		} elseif ( is_singular( 'post' ) && get_field('place_relationship', get_the_ID())) {
			return true; 
		} else {
			return false;
		}
	}

    public function __construct( WPSEO_Schema_Context $context ) {
		$this->context = $context;
	}

	/**
	 * Adds our City piece of the graph.
	 *
	 * @return array $graph Review markup
	 */
	public function generate() {
		$post_id = YoastSEO()->meta->for_current_page()->post_id;
		$post_type = get_post_type( $post_id );
		if ( $post_type == 'liv_place') {
			// $canonical = YoastSEO()->meta->for_current_page()->canonical;
			
			$type = get_field('place_type');
			$data = [];

			if ($type == 'city') {
				$state = get_post_parent();
				$abbv = $state->post_name;
				$city = substr(get_the_title($post_id), 0, -4);
				$latitude = get_post_meta(get_the_ID(), 'jci-latitude', true);
				$longitude = get_post_meta(get_the_ID(), 'jci-longitude', true);
				$liargs = array(
					'post_type'        => 'local_insights',
					'posts_per_page'   => 15,
					'post_status'      => 'publish',
				   //  'orderby'          => 'rand', // caching breaks this, so doing this with ajax
					'meta_query'        => array(
					   array(
						   'key'           => 'place',
						   'value'         => '"' . get_the_ID() . '"',
						   'compare'       => 'LIKE'
					   ) 
				   )
				);
				$local_insights = get_posts($liargs);
				// print_r($local_insights);
				$data[] = array(
					'@type'			=> $type,
					'name'			=> $city,
					'address'		=> array(
						'@type'				=> 'PostalAddress',
						'addressLocality'	=> substr(get_the_title($post_id), 0, -4),
						'addressRegion'		=> strtoupper($abbv),
						'addressCountry'	=> 'US'
					),
					'geo'			=> array(
						'@type'				=> 'GeoCoordinates',
						'latitude'			=> $latitude,
						'longitude'			=> $longitude,
					),
					'containedInPlace'	=> array(
						'@type'				=> 'State',
						'name'				=> $state->post_title,
						'address'			=> array(
							'@type'				=> 'PostalAddress',
							'addressRegion'		=> strtoupper($abbv),
							'addressCountry'	=> 'US'
						),
					),
					'description'	=> get_the_excerpt( $post_id ),
				);
				if ($local_insights) {
					foreach ($local_insights as $key => $value) {
						$insightId = $value->ID;
						$questions = array('opportunities', 'area', 'local_vibe');
						foreach ($questions as $key => $q) {
							$q_string = 'q_'.$q;
							$a_string = 'a_'.$q;
							$question = get_field($q_string, $insightId);
							if ($question) {
								$answer = get_field($a_string, $insightId);
								$f_name = get_field('first_name', $insightId) ? get_field('first_name', $insightId) : '';
    							$l_name = get_field('last_name', $insightId) ? get_field('last_name', $insightId) : '';
								$result = array(
									'@type'				=> 'Question',
									'name'				=> $question,
									'acceptedAnswer'	=> array (
										'@type'				=> 'Answer',
										'text'				=> $answer
									),
									'author'			=> array(
										'@type'				=> 'Person',
										'name'				=> $f_name.' '.$l_name,
										'address'			=> array(
											'@type'				=> 'PostalAddress',
											'addressLocality'	=> $city,
											'addressRegion'		=> $state->post_title
										)
									),
								);
								$data[] = $result;
							}
						}

					}
				}
			} elseif ($type == 'state' ) {
				$latitude = get_post_meta(get_the_ID(), 'jci-latitude', true);
				$longitude = get_post_meta(get_the_ID(), 'jci-longitude', true);
				$abbv = get_post()->post_name;
				$data = array(
					'@type'			=> $type,
					'name'			=> get_the_title(),
					'address'			=> array(
						'@type'				=> 'PostalAddress',
						'addressRegion'		=> strtoupper($abbv),
						'addressCountry'	=> 'US'
					),
					'geo'			=> array(
						'@type'				=> 'GeoCoordinates',
						'latitude'			=> $latitude,
						'longitude'			=> $longitude,
					),
					'description'	=> get_the_excerpt( $post_id ),
				);

			}
			return $data;
			$newtest = array();
		} else { // is article or place category page

			$place = get_field('place_relationship');
			$place = $place[0];
			
			$type = get_field('place_type', $place);
			$test = array(

			);
			if ($type == 'city') {
				$name = substr(get_the_title($place), 0, -4);
				$abbv = substr(get_the_title($place), -2);
			} else {
				$name = get_the_title($place);
				$abbv = get_post($place)->post_name;
			}
			
			$data = array(
				'test'			=> array(
					'contentLocation'		=> array(
						'@type'					=> $type,
						'name'					=> $name,
						'address'				=> array(
							'@type'				=> 'PostalAddress',
							'addressRegion'		=> strtoupper($abbv),
							'addressCountry'	=> 'US'
						)
					)
				)
			);
			return $data;

		}
	}

}
