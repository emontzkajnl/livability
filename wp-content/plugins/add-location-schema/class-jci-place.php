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

		if( is_singular( 'liv_place' ) ) {
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
        $canonical = YoastSEO()->meta->for_current_page()->canonical;
		$post_id = YoastSEO()->meta->for_current_page()->post_id;
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
	}

}
