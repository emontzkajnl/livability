<?php
/**
 * ALM Facets Cache functionality.
 *
 * @package ALMFilters
 */


class ALM_Facets_Cache {

	/**
	 * Create a cache entry for the given facet.
	 *
	 * @param array $args The set of args related to the query data.
	 * @param array $data The data to be cached, typically the results of a query.
	 * @return void
	 */
	public function create_cache( array $args, array $data ) {
		$facet_id = array_key_exists( 'alm_facet_id', $args ) ? $args['alm_facet_id'] : '';
		$alm_id   = array_key_exists( 'alm_id', $args ) ? $args['alm_id'] : '';

		if ( $this->use_transient_cache() ) {
			// Transients.
			$transient_name = $this->generate_transient_name( $facet_id, $alm_id, $args );
			set_transient( $transient_name, $data, apply_filters( 'alm_filters_facets_transient_expiration', 7200 ) ); // Save results in transient for 2 hours.

		} else {
			// WP Cache.
			$cache_key  = $this->generate_cache_key( $args );
			$group_name = ALM_FACETS_CACHE_PREFIX . $facet_id;
			wp_cache_set( $cache_key, $data, $group_name, apply_filters( 'alm_filters_facets_cache_expiration', 7200 ) );

		}
	}

	/**
	 * Retrieve a cache entry for the given facet.
	 *
	 * @param array $args The set of args related to the query data.
	 * @return array|bool The cached data if available, false otherwise.
	 */
	public function get_cache( array $args ) {
		$facet_id = array_key_exists( 'alm_facet_id', $args ) ? $args['alm_facet_id'] : '';
		$alm_id   = array_key_exists( 'alm_id', $args ) ? $args['alm_id'] : '';

		if ( $this->use_transient_cache() ) {
			// Transient Cache
			$transient_name = $this->generate_transient_name( $facet_id, $alm_id, $args );
			return get_transient( $transient_name );

		} else {
			// WP Cache.
			$cache_key  = $this->generate_cache_key( $args );
			$group_name = ALM_FACETS_CACHE_PREFIX . $facet_id;
			return wp_cache_get( $cache_key, $group_name );

		}
	}

	/**
	 *  Check if the transient cache is enabled.
	 *
	 * @return bool
	 */
	public function use_transient_cache() {
		return apply_filters( 'alm_filters_facets_transient_cache', true );
	}

	/**
	 * Build and return a transient name based on query arg params.
	 *
	 * @param string $id     The facet ID.
	 * @param string $alm_id The Ajax Load More ID.
	 * @param array  $args   The set of args related to the query data.
	 * @return string        The generated transient name as a string from URL.
	 */
	public function generate_transient_name( string $facet_id, string $alm_id, $args ) {
		$facet_id = array_key_exists( 'alm_facet_id', $args ) ? $args['alm_facet_id'] : '';
		$alm_id   = array_key_exists( 'alm_id', $args ) ? $args['alm_id'] : '';
		if ( empty( $facet_id ) || empty( $alm_id ) ) {
			return ''; // Bail early if missing either param.
		}

		// Generate a hash based on the alm_id and serialized args.
		$hash = $this->generate_cache_key( $args );

		// e.g. alm_facet_filter_{id}_{alm_id}_d41d8cd98f00b204e980099.
		return strtolower( ALM_FILTERS_FACET_PREFIX . $facet_id . '_' . $hash );
	}

	/**
	 * Utility for creating a cache key from a $prefix + MD5 Json encoded set of $attributes.
	 *
	 * @param array $args The set of args related to the query data.
	 * @return string     The key constructed from $prefix + MD5 Json encoded $attributes.
	 */
	public function generate_cache_key( array $args = [] ) {
		return md5( json_encode( $args ) );
	}

	/**
	 * Delete facet WP Cache/transients by filter ID.
	 *
	 * @param string $facet_id The filter/facet ID.
	 * @return void
	 */
	public function delete_cache( string $facet_id ) {
		// This function is not used, look in ./class-facets.php for the delete_cache function.
	}
}
