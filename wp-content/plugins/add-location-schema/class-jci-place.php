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

		$data          = array(
			'@type'            => $type,
			'@id'              => $this->context->canonical,
            'name'              => the_title_attribute( array( 'echo' => false ) ),
			'description'		=> get_the_excerpt( $post_id )
		);
		// $data = apply_filters( 'be_review_schema_data', $data, $this->context );
        // $data['mainEntityOfPage'] = [ '@id' => $canonical ];
		if (has_post_parent( $post_id )) {
			$parentLink = get_the_permalink( get_post_parent( $post_id) );
			$data[ 'containedInPlace'] = $parentLink;
		}

		return $data;
	}

}
