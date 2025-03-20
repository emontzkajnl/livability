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

		if( is_singular( ['post', 'liv_place'] )) {
				return true;
		}
		return false;
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
				$latitude = get_post_meta(get_the_ID(), 'jci-latitude', true);
				$longitude = get_post_meta(get_the_ID(), 'jci-longitude', true);
				$data = array(
					'@type'			=> $type,
					'name'			=> substr(get_the_title($post_id), 0, -4),
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
		} else { // is article

			$place = get_field('place_relationship');
			// if (!$place) {return;}
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
