<?php
/**
 * This file holds the majority of functionality for the facet filtering.
 *
 * @since 2.0.0
 * @package ALMFilters
 *
 * What does this functionality do?
 * 1. Reads from an index created and stored in the options table.
 * 2. Matches returned query results (get_posts -1) to the items in the content index.
 * 3. Runs functionality to pull and organize facets.
 * 4. Returns an array of objects containing the facets and counts.
 */

/*********** GET FACET RESULTS *********** */

/**
 * Get the ALM facets for this filter.
 * Note: $target parameter is set on core ALM shortcode.
 * e.g. [ajax_load_more id="movie_listing" target="actors, movie_type, posts_per_page"]
 *
 * @param  object $args   The base query args.
 * @param  string $target The Filter/Facet ID's to retrieve data from DB.
 * @return array          An array of key/value pair facets.
 */
function alm_filters_get_facets( $args = [], $target = '' ) {
	if ( ! $target ) {
		return [];
	}

	$facets  = [];
	$filters = array_map( 'trim', explode( ',', $target ) ); // Split target into array.

	if ( ! empty( $filters ) ) {
		// Loop each filter to get facets.
		foreach ( $filters as $id ) {
			$facet  = alm_filters_get_facet( $args, $id ); // Get facets for the filter.
			$facets = array_merge( $facets, $facet ); // Merge the facets and make them unique.
		}
	}
	return $facets;
}

/**
 * Get an individual facet.
 *
 * @param array  $args     The base query args.
 * @param string $facet_id The filter ID.
 * @return array           An array of key/value pair facet results.
 */
function alm_filters_get_facet( $args = [], $facet_id = '' ) {
	if ( ! $facet_id ) {
		return [];
	}

	// Add the facet ID to the args.
	$args['alm_facet_id'] = $facet_id;

	// Override ALM query $args for the facet query.
	$args['fields']         = 'ids'; // Return only post IDs.
	$args['posts_per_page'] = apply_filters( 'alm_filters_facets_query_posts_per_page', -1 ); // Get all posts.

	// Supported query keys.
	$supported_keys = [
		'taxonomy',
		'meta',
		'category',
		'category__and',
		'tag',
		'tag__and',
		'author',
		'year',
		'month',
		'day',
		'post_type',
	];

	// Get cached data to reduce query load.
	$cached_data = ( new ALM_Facets_Cache() )->get_cache( $args );
	if ( $cached_data ) {
		return $cached_data; // Return cached data if available.
	}

	// Get all posts from the query.
	$posts = get_posts( apply_filters( 'alm_filters_facet_query_args_' . $facet_id, $args ) );

	// Get the facet index from the options table.
	$facet = ALMFilters::get_facet_index_by_id( $facet_id );

	$index = $facet ? $facet : [];

	// Get the filter options from the options table.
	$filter  = get_option( ALM_FILTERS_PREFIX . $facet_id );
	$filter  = $filter ? unserialize( $filter ) : [];
	$filters = isset( $filter['filters'] ) ? $filter['filters'] : [];
	if ( ! $filters ) {
		return [];
	}

	$default_post_types = isset( $filter['facets_post_types'] ) ? $filter['facets_post_types'] : [ 'post' ];
	$results            = [];

	// Loop each filter and compare to create the facets.
	foreach ( $filters as $filter ) {
		$field_type    = $filter['field_type'];
		$single_select = [ 'radio', 'select' ];
		$key           = $filter['key'];

		switch ( $key ) {
			case 'taxonomy':
				$slug                           = $filter['taxonomy'];
				$data                           = in_array( $field_type, $single_select, true ) ? alm_filters_single_selection_args( $key, $slug, $args, $posts ) : $posts;
				$results['taxonomies'][ $slug ] = alm_filters_compare_index( $data, $index, $slug, 'taxonomy' );
				break;

			case 'meta':
				$slug                     = $filter['meta_key'];
				$data                     = in_array( $field_type, $single_select, true ) ? alm_filters_single_selection_args( $key, $slug, $args, $posts ) : $posts;
				$results['meta'][ $slug ] = alm_filters_compare_index( $data, $index, $slug, 'meta' );
				break;

			case 'post_type':
				if ( in_array( $field_type, $single_select, true ) ) {
					$results[ $key ] = alm_filters_single_selection_args( $key, 'post_type', $args, $posts, $default_post_types );
				} else {
					$results[ $key ] = alm_filters_compare_index( $posts, $index, $key );
				}
				break;

			default:
				$slug = $key;
				switch ( $key ) {
					case 'category':
						$slug = 'category_name'; // Convert `category` to `category_name` for $arg parsing.
						break;
				}
				if ( in_array( $key, $supported_keys, true ) ) {
					$data            = in_array( $field_type, $single_select, true ) ? alm_filters_single_selection_args( $key, $slug, $args, $posts, $default_post_types ) : $posts;
					$results[ $key ] = alm_filters_compare_index( $data, $index, $key );
				}
				break;
		}
	}

	// Create a cache entry for the results.
	( new ALM_Facets_Cache() )->create_cache( $args, $results );

	return $results;
}

/**
 * Run a separate `get_posts` query for each single select field type (Radio, Select).
 *
 * This function will only run if the filter is present in the existing query args.
 * The idea is that if the filter is a single select then it needs to be excluded from the
 * query to get the true facets.
 *
 * Essentially, we need to remove the filter in order to return the true results.
 *
 * @param  string $key        The filter key.
 * @param  string $slug       The filter slug (taxonomy, meta only).
 * @param  array  $args       Array of query args.
 * @param  array  $posts      Current posts from query.
 * @param  array  $post_types Default post type array.
 * @return array              An array of post IDs.
 */
function alm_filters_single_selection_args( $key, $slug, $args = [], $posts = [], $post_types = [ 'post' ] ) {
	if ( $key === 'taxonomy' || $key === 'meta' ) {
		/**
		 * Taxonomy & Meta Query
		 * Remove any existing tax/meta query to get the true facets.
		 */
		$query_var    = $key === 'taxonomy' ? 'tax_query' : 'meta_query';
		$var          = $key === 'taxonomy' ? 'taxonomy' : 'key';
		$nested_query = isset( $args[ $query_var ] ) && is_array( $args[ $query_var ] ) ? $args[ $query_var ] : false;
		if ( ! $nested_query ) {
			return $posts;
		}

		// Loop each nested_query and remove matched filter from args if found.
		foreach ( $nested_query as $index => $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}
			if ( $item[ $var ] === $slug ) {
				// Match found, remove the query arg.
				unset( $args[ $query_var ][ $index ] );
				return get_posts( $args );
			}
		}
	} elseif ( $key === 'post_type' && isset( $args[ $slug ] ) ) {
		/**
		 * Post Type
		 * Run a separate `get_posts` query for each post type to get the count.
		 */
		if ( isset( $args[ $slug ] ) ) {
			$post_type_facets = [];
			foreach ( $post_types as $post_type ) {
				// Loop each post type to get the count.
				$args['post_type']              = $post_type;
				$post_type_facets[ $post_type ] = count( get_posts( $args ) );
			}
			return $post_type_facets;
		}
	} else {
		/**
		 * Standard
		 * Remove any existing query to get the true facets.
		 */
		if ( isset( $args[ $slug ] ) ) {
			unset( $args[ $slug ] ); // Matched - remove query arg.
			return get_posts( $args );
		}
	}

	return $posts; // Fallback to original posts.
}

/**
 * Search post index for matching filter terms.
 * This function loops all posts in the index and looks for matching post IDs in the returned get_posts query.
 * If found, the values and counts are returned in an array.
 *
 * @param  array       $posts The array of posts from the current query.
 * @param  array       $index The facet index of posts.
 * @param  string      $slug  The taxonomy/meta slug.
 * @param  string|null $type  The query type.
 * @return array         An array of matched results.
 */
function alm_filters_compare_index( $posts = [], $index = [], $slug = '', $type = null ) {
	$results = [];
	if ( ! $posts || ! $index ) {
		return [];
	}

	// Loop all items in the index.
	foreach ( $index as $item ) {
		// Find posts in index by ID.
		if ( in_array( $item['id'], $posts, true ) ) {

			if ( $type ) { // Nested query args.
				$value = isset( $item[ $type ] ) && isset( $item[ $type ][ $slug ] ) ? $item[ $type ][ $slug ] : '';
			} else { // Standard query args.
				$value = isset( $item[ $slug ] ) ? $item[ $slug ] : '';
			}

			// Split value into array at comma.
			$value = explode( ',', $value );

			// Create an array entry for each key and value.
			foreach ( $value as $term ) {
				if ( ! empty( $term ) ) {
					$results[] = trim( $term );
				}
			}
		}
	}
	return ! empty( $results ) ? alm_filters_organize_results( $results, true ) : [];
}

/**
 * Count and remove duplicate results of an array.
 *
 * @param  array   $array The array to organize.
 * @param  boolean $flat  Is this a flat array and not multidimensional.
 * @return array          The modified array.
 */
function alm_filters_organize_results( $array, $flat = false ) {
	$results = [];
	if ( ! $array ) {
		return [];
	}
	if ( ! $flat ) {
		foreach ( $array as $key => $item ) {
			$results[ $key ] = array_count_values( $array[ $key ] );
		}
	} else {
		$results = array_count_values( $array );
	}
	return $results;
}

/******************* - *********************/
/*********** BUILD/CREATE FACET INDEX *********** */
/******************* - *********************/

/**
 * Construct an index for an individual post by ID.
 *
 * @param  string $post_id The post ID.
 * @param  array  $facets  The post facets.
 * @return array           The facet index for an individual post.
 */
function alm_filters_build_post_index( $post_id, $facets ) {
	if ( ! $post_id || ! $facets ) {
		return [];
	}

	$cats = alm_filters_facets_get_cats_tags( $post_id, 'category' ); // Get categories.
	$tags = alm_filters_facets_get_cats_tags( $post_id, 'post_tag' ); // Get tags.
	$date = alm_filters_get_facet_dates( $post_id ); // Get date.

	$index = [
		'id'            => $post_id,
		'category'      => $cats['slugs'],
		'category__and' => $cats['ids'],
		'tag'           => $tags['slugs'],
		'tag__and'      => $tags['ids'],
		'taxonomy'      => alm_filters_get_facet_taxonomies( $post_id, $facets['taxonomies'] ),
		'meta'          => alm_filters_get_facet_meta( $post_id, $facets['meta'] ),
		'author'        => alm_filters_get_facet_author( $post_id ),
		'year'          => $date['year'],
		'month'         => $date['month'],
		'day'           => $date['day'],
		'post_type'     => get_post_type( $post_id ),
	];

	return $index;
}

/**
 * Pull the possible facet keys from the filters object and return them as an array.
 *
 * @param  array $filter The filter array.
 * @return array         A constructed array of possible facets.
 */
function alm_filters_pluck_facet_keys( $filter ) {
	// Get the filters from the filter options.
	$filters = isset( $filter ) && isset( $filter['filters'] ) ? $filter['filters'] : [];

	// Default array.
	$array = [
		'taxonomies'    => [],
		'meta'          => [],
		'category'      => false,
		'category__and' => false,
		'tag'           => false,
		'tag_and'       => false,
		'author'        => false,
		'year'          => false,
		'month'         => false,
		'day'           => false,
		'post_type'     => false,
	];

	// phpcs:disable
	/*
	$facets = [
		'taxonomies' => [ 'actor', 'post_tag', 'movie_type' ],
		'meta'       => [ 'test_cf' ],
		'author'     => true
	];
	*/
	// phpcs:enable

	foreach ( $filters as $filter ) {
		$key = $filter['key'];

		if ( $key === 'post_type' ) {
			$array['post_type'] = true;
		}

		if ( $key === 'taxonomy' ) {
			$array['taxonomies'][] = $filter['taxonomy'];
		}

		if ( $key === 'category' ) {
			$array['category'] = true;
		}

		if ( $key === 'category__and' ) {
			$array['category__and'] = true;
		}

		if ( $key === 'tag' ) {
			$array['tag'] = true;
		}

		if ( $key === 'meta' ) {
			$array['meta'][] = $filter['meta_key'];
		}

		if ( $key === 'author' ) {
			$array['author'] = true;
		}

		if ( $key === 'year' ) {
			$array['year'] = true;
		}

		if ( $key === 'month' ) {
			$array['month'] = true;
		}

		if ( $key === 'day' ) {
			$array['day'] = true;
		}
	}
	return $array;
}

/**
 * Build the facet category & tag results.
 * Note: This function is used to generate the index.
 *
 * @param  int    $id  The post ID.
 * @param  string $tax The taxonomy slug.
 * @return array       An array of slugs and ids.
 */
function alm_filters_facets_get_cats_tags( $id = 0, $tax = 'category' ) {
	$terms = get_the_terms( $id, $tax );
	$slugs = join( ', ', wp_list_pluck( $terms, 'slug' ) );
	$ids   = join( ', ', wp_list_pluck( $terms, 'term_taxonomy_id' ) );

	return [
		'slugs' => $slugs,
		'ids'   => $ids,
	];
}

/**
 * Build the facet taxonomy results.
 * Note: This function is used to generate the index.
 *
 * @param  number  $id         The post ID.
 * @param  array   $taxonomies The array of specific taxonomies to loop over.
 * @param  boolean $flat       Is this a flat array and not multidimensional.
 * @return string|array        The taxonomy facets as an array or string.
 */
function alm_filters_get_facet_taxonomies( $id = 0, $taxonomies = [], $flat = false ) {
	if ( ! $taxonomies ) {
		return [];
	}

	if ( $flat ) {
		$terms = get_the_terms( $id, $taxonomies );
		$terms = join( ', ', wp_list_pluck( $terms, 'slug' ) );
		return $terms;

	} else {
		// Taxonomies.
		foreach ( $taxonomies as $tax ) {
			$terms          = get_the_terms( $id, $tax );
			$terms          = join( ', ', wp_list_pluck( $terms, 'slug' ) );
			$facets[ $tax ] = $terms;
		}
		return $facets;
	}
}

/**
 * Build the facet custom field results.
 * Note: This function is used to generate the index.
 *
 * @param  number $id    The post ID.
 * @param  array  $meta  The array of specific custom fields to loop over.
 * @return array         The custom field facets.
 */
function alm_filters_get_facet_meta( $id = 0, $meta = [] ) {
	if ( ! $meta ) {
		return [];
	}
	$facets = [];
	foreach ( $meta as $field ) {
		$value            = get_post_meta( $id, $field, true );
		$facets[ $field ] = is_array( $value ) ? implode( ',', $value ) : $value;
	}
	return $facets;
}

/**
 * Build the facet results for post authors.
 * Note: This function is used to generate the index.
 *
 * @param  number $id The post ID.
 * @return string     The author ID.
 */
function alm_filters_get_facet_author( $id = 0 ) {
	if ( ! $id ) {
		return '';
	}
	$author_id = get_post_field( 'post_author', $id );
	return $author_id;
}

/**
 * Build the facet results for post dates.
 * Note: This function is used to generate the index.
 *
 * @param  number $id The post ID.
 * @return array      The year, month and day as an array.
 */
function alm_filters_get_facet_dates( $id = 0 ) {
	if ( ! $id ) {
		return '';
	}
	$date = get_the_date( 'Y,m,d', $id );
	$date = explode( ',', $date );

	$facets = [
		'year'  => isset( $date[0] ) ? $date[0] : null,
		'month' => isset( $date[1] ) ? $date[1] : null,
		'day'   => isset( $date[2] ) ? $date[2] : null,
	];

	return $facets;
}

/**
 * Determine if facets are "true" in filter.
 *
 * @param string $target The filter target.
 * @return boolean       Does the filter contain facets.
 */
function alm_filters_has_facets( $target ) {
	if ( ! $target ) {
		return false;
	}

	$filters = array_map( 'trim', explode( ',', $target ) ); // Split into array on filters.
	if ( ! empty( $filters ) ) {
		// Loop each filter and check if facets are present.
		foreach ( $filters as $id ) {
			$filter = ALMFilters::alm_filters_get_filter_by_id( $id );
			if ( $filter && isset( $filter['facets'] ) && $filter['facets'] ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Parse the current URL to pluck query params.
 *
 * @return array Array of query params.
 */
function alm_filters_facet_get_querystring() {
	$params  = filter_input_array( INPUT_GET ); // phpcs:ignore
	$is_ajax = isset( $params ) && isset( $params['facets'] );
	if ( $is_ajax ) {
		// Ajax request.
		$parts = wp_parse_url( $_SERVER['HTTP_REFERER'] );
		$query = isset( $parts['query'] ) ? $parts['query'] : '';
		parse_str( $query, $params );
		return $params;
	} else {
		// Serverside request (Preloaded).
		parse_str( $_SERVER['QUERY_STRING'], $params );
		return $params;
	}
}

/******************* - *********************/
/************* TEST INDEX ******************/
/******************* - *********************/

/**
 * Build a test index.
 */
function alm_filters_test_index() {
	$args = [
		'post_type'      => [ 'movie' ],
		'post_status'    => [ 'publish' ],
		'posts_per_page' => -1,
	];

	$array  = [];
	$facets = [
		'taxonomies' => [ 'actor', 'post_tag', 'movie_type' ],
		'meta'       => [ 'test_cf' ],
		'author'     => true,
	];

	// WP_Query.
	$query = new WP_Query( $args );
	while ( $query->have_posts() ) :
		$query->the_post();
		$post_id = get_the_ID();
		$array[] = [
			'id'       => $post_id,
			'taxonomy' => alm_filters_get_facet_taxonomies( $post_id, $facets['taxonomies'] ),
			'meta'     => alm_filters_get_facet_meta( $post_id, $facets['meta'] ),
			'author'   => alm_filters_get_facet_author( $post_id ),
		];
	endwhile;
	wp_reset_postdata();

	return $array;
}
