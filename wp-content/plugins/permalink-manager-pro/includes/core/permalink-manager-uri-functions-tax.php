<?php

/**
 * A set of functions for processing and applying the custom permalink to terms.
 */
class Permalink_Manager_URI_Functions_Tax {

	public function __construct() {
		add_action( 'init', array( $this, 'init' ), 100 );
		add_action( 'rest_api_init', array( $this, 'init' ) );

		add_filter( 'term_link', array( $this, 'custom_tax_permalinks' ), 999, 2 );
	}

	/**
	 * Allow to edit URIs from "Edit Term" admin pages (register hooks)
	 */
	public function init() {
		global $permalink_manager_options;

		$all_taxonomies = Permalink_Manager_Helper_Functions::get_taxonomies_array();

		// Add "URI Editor" to "Quick Edit" for all taxonomies
		foreach ( $all_taxonomies as $tax => $label ) {
			// Check if taxonomy is allowed
			if ( Permalink_Manager_Helper_Functions::is_taxonomy_disabled( $tax ) ) {
				continue;
			}

			add_action( "edited_{$tax}", array( $this, 'update_term_uri' ), 10, 2 );
			add_action( "create_{$tax}", array( $this, 'update_term_uri' ), 10, 2 );
			add_action( "delete_{$tax}", array( $this, 'remove_term_uri' ), 10, 2 );

			// Check the user capabilities
			if ( is_admin() ) {
				$edit_uris_cap = ( ! empty( $permalink_manager_options['general']['edit_uris_cap'] ) ) ? $permalink_manager_options['general']['edit_uris_cap'] : 'publish_posts';
				if ( current_user_can( $edit_uris_cap ) ) {
					add_action( "{$tax}_add_form_fields", array( $this, 'edit_uri_box' ), 10, 1 );
					add_action( "{$tax}_edit_form_fields", array( $this, 'edit_uri_box' ), 10, 1 );
					add_filter( "manage_edit-{$tax}_columns", array( $this, 'quick_edit_column' ) );
					add_filter( "manage_{$tax}_custom_column", array( $this, 'quick_edit_column_content' ), 10, 3 );
				}
			}
		}
	}

	/**
	 * Apply the custom permalinks to the terms
	 *
	 * @param string $permalink
	 * @param WP_Term|int $term
	 *
	 * @return string
	 */
	function custom_tax_permalinks( $permalink, $term ) {
		global $permalink_manager_uris, $permalink_manager_options, $permalink_manager_ignore_permalink_filters;

		// Do not filter permalinks in Customizer
		if ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) {
			return $permalink;
		}

		// Do not filter in WPML String Editor
		if ( ! empty( $_REQUEST['icl_ajx_action'] ) && $_REQUEST['icl_ajx_action'] == 'icl_st_save_translation' ) {
			return $permalink;
		}

		// Do not filter if $permalink_manager_ignore_permalink_filters global is set
		if ( ! empty( $permalink_manager_ignore_permalink_filters ) ) {
			return $permalink;
		}

		$term = ( is_numeric( $term ) ) ? get_term( $term ) : $term;

		// Check if the term is allowed
		if ( empty( $term->term_id ) || Permalink_Manager_Helper_Functions::is_term_excluded( $term ) || ! is_string( $permalink ) ) {
			return $permalink;
		}

		// Get term id
		$term_id = $term->term_id;

		// Save the old permalink to separate variable
		$old_permalink = $permalink;

		if ( isset( $permalink_manager_uris["tax-{$term_id}"] ) ) {
			// Start with homepage URL
			$permalink = Permalink_Manager_Helper_Functions::get_permalink_base( $term );

			// Encode URI?
			if ( ! empty( $permalink_manager_options['general']['decode_uris'] ) ) {
				$permalink .= rawurldecode( "/{$permalink_manager_uris["tax-{$term_id}"]}" );
			} else {
				$permalink .= Permalink_Manager_Helper_Functions::encode_uri( "/{$permalink_manager_uris["tax-{$term_id}"]}" );
			}
		} else if ( ! empty( $permalink_manager_options['general']['decode_uris'] ) ) {
			$permalink = rawurldecode( $permalink );
		}

		return apply_filters( 'permalink_manager_filter_final_term_permalink', $permalink, $term, $old_permalink );
	}

	/**
	 * Check if the provided slug is unique and then update it with SQL query.
	 *
	 * @param string $slug
	 * @param int $id
	 * @param bool $preview_mode
	 *
	 * @return string
	 */
	static function update_slug_by_id( $slug, $id, $preview_mode = false ) {
		global $wpdb;

		// Update slug and make it unique
		$term = get_term( intval( $id ) );
		$slug = ( empty( $slug ) ) ? get_the_title( $term->name ) : $slug;
		$slug = sanitize_title( $slug );

		$new_slug = wp_unique_term_slug( $slug, $term );

		if ( ! $preview_mode ) {
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->terms} SET slug = %s WHERE term_id = %d", $new_slug, $id ) );
		}

		return $new_slug;
	}

	/**
	 * Get the currently used custom permalink (or default/empty URI)
	 *
	 * @param int $term_id
	 * @param bool $native_uri
	 * @param bool $no_fallback
	 *
	 * @return string
	 */
	public static function get_term_uri( $term_id, $native_uri = false, $no_fallback = false ) {
		global $permalink_manager_uris;

		// Check if input is term object
		$term = ( isset( $term_id->term_id ) ) ? $term_id->term_id : get_term( $term_id );

		if ( ! empty( $permalink_manager_uris["tax-{$term_id}"] ) ) {
			$final_uri = $permalink_manager_uris["tax-{$term_id}"];
		} else if ( ! $no_fallback ) {
			$final_uri = self::get_default_term_uri( $term->term_id, $native_uri );
		} else {
			$final_uri = '';
		}

		return $final_uri;
	}

	/**
	 * Get the default custom permalink (not overwritten by the user) or native permalink (unfiltered)
	 *
	 * @param WP_Term|int $term
	 * @param bool $native_uri
	 * @param bool $check_if_disabled
	 *
	 * @return string
	 */
	public static function get_default_term_uri( $term, $native_uri = false, $check_if_disabled = false ) {
		global $permalink_manager_permastructs, $wp_taxonomies, $icl_adjust_id_url_filter_off;

		// Disable WPML adjust ID filter
		$icl_adjust_id_url_filter_off = true;

		// 1. Load all bases & term
		$term = is_object( $term ) ? $term : get_term( $term );
		// $term_id = $term->term_id;
		$taxonomy_name   = $term->taxonomy;
		$taxonomy        = get_taxonomy( $taxonomy_name );
		$term_slug       = $term->slug;
		$top_parent_slug = '';

		// 1A. Check if taxonomy is allowed
		if ( $check_if_disabled && Permalink_Manager_Helper_Functions::is_taxonomy_disabled( $taxonomy ) ) {
			return '';
		}

		// 2A. Get the native permastructure
		$native_permastructure = Permalink_Manager_Permastructure_Functions::get_default_permastruct( $taxonomy_name );

		// 2B. Get the permastructure
		if ( $native_uri || empty( $permalink_manager_permastructs['taxonomies'][ $taxonomy_name ] ) ) {
			$permastructure = $native_permastructure;
		} else {
			$permastructure = apply_filters( 'permalink_manager_filter_permastructure', $permalink_manager_permastructs['taxonomies'][ $taxonomy_name ], $term );
		}

		// 2C. Set the permastructure
		$default_base = ( ! empty( $permastructure ) ) ? trim( $permastructure, '/' ) : "";

		// 3A. Check if the taxonomy has custom permastructure set
		if ( empty( $default_base ) && ! isset( $permalink_manager_permastructs['taxonomies'][ $taxonomy_name ] ) ) {
			if ( 'category' == $taxonomy_name ) {
				$default_uri = "?cat={$term->term_id}";
			} elseif ( $taxonomy->query_var ) {
				$default_uri = "?{$taxonomy->query_var}={$term_slug}";
			} else if ( ! empty( $term_slug ) ) {
				$default_uri = "?taxonomy={$taxonomy_name}&term={$term_slug}";
			} else {
				$default_uri = '';
			}
		} // 3B. Use custom permastructure
		else {
			$default_uri = $default_base;

			// 3B. Get the full slug
			$term_slug        = Permalink_Manager_Helper_Functions::remove_slashes( $term_slug );
			$custom_slug      = $full_custom_slug = Permalink_Manager_Helper_Functions::force_custom_slugs( $term_slug, $term );
			$term_title_slug  = Permalink_Manager_Helper_Functions::force_custom_slugs( $term_slug, $term, true, 1 );
			$full_native_slug = $term_slug;

			// Add ancestors to hierarchical taxonomy
			if ( is_taxonomy_hierarchical( $taxonomy_name ) ) {
				$ancestors = get_ancestors( $term->term_id, $taxonomy_name, 'taxonomy' );

				foreach ( $ancestors as $ancestor ) {
					$ancestor_term = get_term( $ancestor, $taxonomy_name );

					$full_native_slug = $ancestor_term->slug . '/' . $full_native_slug;
					$full_custom_slug = Permalink_Manager_Helper_Functions::force_custom_slugs( $ancestor_term->slug, $ancestor_term ) . '/' . $full_custom_slug;
				}

				// Get top parent term
				if ( strpos( $default_uri, "%{$taxonomy_name}_top%" ) === false || strpos( $default_uri, "%term_top%" ) === false ) {
					$top_parent_slug = Permalink_Manager_Helper_Functions::get_term_full_slug( $term, $ancestors, 3, $native_uri );
				}
			}

			// Allow filter the default slug (only custom permalinks)
			if ( ! $native_uri ) {
				$full_slug = apply_filters( 'permalink_manager_filter_default_term_slug', $full_custom_slug, $term, $term->name );
			} else {
				$full_slug = $full_native_slug;
			}

			// Get the taxonomy slug
			if ( ! empty( $wp_taxonomies[ $taxonomy_name ]->rewrite['slug'] ) ) {
				$taxonomy_name_slug = $wp_taxonomies[ $taxonomy_name ]->rewrite['slug'];
			} else if ( is_string( $wp_taxonomies[ $taxonomy_name ]->rewrite ) ) {
				$taxonomy_name_slug = $wp_taxonomies[ $taxonomy_name ]->rewrite;
			} else {
				$taxonomy_name_slug = $taxonomy_name;
			}
			$taxonomy_name_slug = apply_filters( 'permalink_manager_filter_taxonomy_slug', $taxonomy_name_slug, $term, $taxonomy_name );

			$slug_tags             = array( "%term_name%", "%term_flat%", "%{$taxonomy_name}%", "%{$taxonomy_name}_flat%", "%term_top%", "%{$taxonomy_name}_top%", "%native_slug%", "%native_title%", "%taxonomy%", "%term_id%" );
			$slug_tags_replacement = array( $full_slug, $custom_slug, $full_slug, $custom_slug, $top_parent_slug, $top_parent_slug, $full_native_slug, $term_title_slug, $taxonomy_name_slug, $term->term_id );

			// Check if any term tag is present in custom permastructure
			$do_not_append_slug = Permalink_Manager_Permastructure_Functions::is_slug_tag_present( $default_uri, $slug_tags, $term );

			// Replace the term tags with slugs or append the slug if no term tag is defined
			if ( ! empty( $do_not_append_slug ) ) {
				$default_uri = str_replace( $slug_tags, $slug_tags_replacement, $default_uri );
			} else {
				$default_uri .= "/{$full_slug}";
			}
		}

		// Enable WPML adjust ID filter
		$icl_adjust_id_url_filter_off = false;

		return apply_filters( 'permalink_manager_filter_default_term_uri', $default_uri, $term->slug, $term, $term_slug, $native_uri );
	}

	/**
	 * Get array with all term items based on the user-selected settings in the "Bulk tools" form
	 *
	 * @return array|false
	 */
	public static function get_items() {
		global $wpdb, $permalink_manager_options;

		// Check if taxonomies are not empty
		if ( empty( $_POST['taxonomies'] ) || ! is_array( $_POST['taxonomies'] ) ) {
			return false;
		}

		$taxonomy_names_array = array_map( 'sanitize_key', $_POST['taxonomies'] );
		$taxonomy_names       = implode( "', '", $taxonomy_names_array );

		// Filter the terms by IDs
		$where = '';
		if ( ! empty( $_POST['ids'] ) ) {
			// Remove whitespaces and prepare array with IDs and/or ranges
			$ids = esc_sql( preg_replace( '/\s*/m', '', $_POST['ids'] ) );
			preg_match_all( "/([\d]+(?:-?[\d]+)?)/x", $ids, $groups );

			// Prepare the extra ID filters
			$where .= "AND (";
			foreach ( $groups[0] as $group ) {
				$where .= ( $group == reset( $groups[0] ) ) ? "" : " OR ";
				// A. Single number
				if ( is_numeric( $group ) ) {
					$where .= "(t.term_id = {$group})";
				} // B. Range
				else if ( substr_count( $group, '-' ) ) {
					$range_edges = explode( "-", $group );
					$where       .= "(t.term_id BETWEEN {$range_edges[0]} AND {$range_edges[1]})";
				}
			}
			$where .= ")";
		}

		// Get excluded items
		$excluded_terms = (array) apply_filters( 'permalink_manager_excluded_term_ids', array() );
		if ( ! empty( $excluded_terms ) ) {
			$where .= sprintf( " AND t.term_id NOT IN ('%s') ", implode( "', '", $excluded_terms ) );
		}

		// Check the auto-update mode
		// A. Allow only user-approved posts
		if ( ! empty( $permalink_manager_options["general"]["auto_update_uris"] ) && $permalink_manager_options["general"]["auto_update_uris"] == 2 ) {
			$where .= " AND meta_value IN (1, -1) ";
		} // B. Allow all posts not disabled by the user
		else {
			$where .= " AND (meta_value IS NULL OR meta_value IN (1, -1)) ";
		}

		// Get the rows before they are altered
		$query = "SELECT t.slug, t.name, t.term_id, tt.taxonomy FROM {$wpdb->terms} AS t INNER JOIN {$wpdb->term_taxonomy} AS tt ON tt.term_id = t.term_id LEFT JOIN {$wpdb->termmeta} AS tm ON (tm.term_id = t.term_id AND tm.meta_key = 'auto_update_uri') WHERE tt.taxonomy IN ('{$taxonomy_names}') {$where}";
		$query = apply_filters( 'permalink_manager_get_items_query', $query, $where, 'taxonomies' );

		return $wpdb->get_results( $query, ARRAY_A );
	}

	/**
	 * Process the permalinks/slugs "Find & replace" & "Regenerate/rest" tool
	 *
	 * @param array $chunk
	 * @param string $mode
	 * @param string $operation
	 * @param string $old_string
	 * @param string $new_string
	 * @param bool $preview_mode
	 *
	 * @return array|false
	 */
	public static function bulk_process_items( $chunk = null, $mode = '', $operation = '', $old_string = '', $new_string = '', $preview_mode = false ) {
		// Reset variables
		$updated_slugs_count = 0;
		$updated_array       = array();

		// Get the rows before they are altered
		$terms_to_update = ( ! empty( $chunk ) ) ? $chunk : self::get_items();

		if ( empty( $operation ) ) {
			return false;
		}

		// Now if the array is not empty use IDs from each subarray as a key
		if ( $terms_to_update ) {
			foreach ( $terms_to_update as $row ) {
				$this_term = get_term( $row['term_id'] );

				// Get default & native URL
				$native_uri  = self::get_default_term_uri( $this_term, true );
				$default_uri = self::get_default_term_uri( $this_term );
				$old_uri     = Permalink_Manager_URI_Functions::get_single_uri( $row['term_id'], true, false, true );

				$old_term_name = $row['slug'];

				if ( $operation == 'regenerate' ) {
					if ( $mode == 'slugs' ) {
						$new_uri       = $old_uri;
						$new_term_name = Permalink_Manager_Helper_Functions::sanitize_title( $row['name'] );
					} else if ( $mode == 'native' ) {
						$new_uri       = $native_uri;
						$new_term_name = $old_term_name;
					} else {
						$new_uri       = $default_uri;
						$new_term_name = $old_term_name;
					}
				} else {
					list( $new_term_name, $new_uri ) = Permalink_Manager_Helper_Functions::replace_uri_slug( $old_string, $new_string, $old_term_name, $old_uri, $mode );
				}

				// Check if native slug should be changed
				if ( $mode == 'slugs' && $old_term_name !== $new_term_name ) {
					$new_term_name = self::update_slug_by_id( $new_term_name, $row['term_id'], $preview_mode );
				}

				$new_uri = apply_filters( 'permalink_manager_pre_update_term_uri', $new_uri, $row['term_id'], $old_uri, $native_uri, $default_uri );

				if ( ! ( empty( $new_uri ) ) && ( $old_uri !== $new_uri ) || ( $old_term_name !== $new_term_name ) ) {
					if ( ! $preview_mode && ( $old_uri !== $new_uri ) ) {
						Permalink_Manager_URI_Functions::save_single_uri( $row['term_id'], $new_uri, true, false );
						do_action( 'permalink_manager_updated_term_uri', $row['term_id'], $new_uri, $old_uri, $native_uri, $default_uri );
					}

					$updated_array[] = array( 'item_title' => $row['name'], 'ID' => $row['term_id'], 'old_uri' => $old_uri, 'new_uri' => $new_uri, 'old_slug' => $old_term_name, 'new_slug' => $new_term_name, 'tax' => $this_term->taxonomy );
					$updated_slugs_count ++;
				}
			}

			// Save all custom permalinks
			if ( ! $preview_mode ) {
				Permalink_Manager_URI_Functions::save_all_uris();
			}

			$output = array( 'updated' => $updated_array, 'updated_count' => $updated_slugs_count );
			wp_reset_postdata();
		}

		return ( ! empty( $output ) ) ? $output : false;
	}

	/**
	 * Save the custom permalinks in "Bulk URI Editor" tool
	 *
	 * @return array|false
	 */
	static public function update_all_permalinks() {
		// Setup needed variables
		$updated_slugs_count = 0;
		$updated_array       = array();

		$new_uris = isset( $_POST['uri'] ) ? $_POST['uri'] : array();

		// Double check if the slugs and ids are stored in arrays
		if ( ! is_array( $new_uris ) ) {
			$new_uris = explode( ',', $new_uris );
		}

		if ( ! empty( $new_uris ) ) {
			foreach ( $new_uris as $id => $new_uri ) {
				// Remove prefix from field name to obtain term id
				$term_id = filter_var( str_replace( 'tax-', '', $id ), FILTER_SANITIZE_NUMBER_INT );

				$this_term = get_term( $term_id );
				$old_uri   = Permalink_Manager_URI_Functions::get_single_uri( $term_id, false, true, true );
				$new_uri   = ( empty( $new_uri ) ) ? null : $new_uri; // If the string is empty, convert it to null to force the default permalink

				$final_uri = self::save_uri( $this_term, $new_uri, false, false, false );

				if ( $final_uri ) {
					$updated_array[] = array( 'item_title' => $this_term->name, 'ID' => $term_id, 'old_uri' => $old_uri, 'new_uri' => $final_uri, 'tax' => $this_term->taxonomy );
					$updated_slugs_count ++;
				}
			}

			// Save all custom permalinks
			Permalink_Manager_URI_Functions::save_all_uris();

			$output = array( 'updated' => $updated_array, 'updated_count' => $updated_slugs_count );
		}

		return ( ! empty( $output ) ) ? $output : false;
	}

	/**
	 * Allow to edit URIs from "New Term" & "Edit Term" admin pages
	 *
	 * @param WP_Term $term
	 */
	public function edit_uri_box( $term = '' ) {
		// Check if the term is excluded
		if ( empty( $term ) || Permalink_Manager_Helper_Functions::is_term_excluded( $term ) ) {
			return;
		}

		// Stop the hook (if needed)
		if ( ! empty( $term->taxonomy ) ) {
			$show_uri_editor = apply_filters( "permalink_manager_show_uri_editor_term", true, $term, $term->taxonomy );

			if ( ! $show_uri_editor ) {
				return;
			}
		}

		$label       = __( "Custom URI", "permalink-manager" );
		$description = __( "Clear/leave the field empty to use the default permalink.", "permalink-manager" );

		// A. New term
		if ( empty( $term->term_id ) ) {
			$html = "<div class=\"form-field\">";
			$html .= sprintf( "<label for=\"term_meta[uri]\">%s</label>", $label );
			$html .= "<input type=\"text\" name=\"custom_uri\" id=\"custom_uri\" value=\"\">";
			$html .= sprintf( "<p class=\"description\">%s</p>", $description );
			$html .= "</div>";

			// Append nonce field
			$html .= wp_nonce_field( 'permalink-manager-edit-uri-box', 'permalink-manager-nonce', true, false );
		} // B. Edit term
		else {
			$html = "<tr id=\"permalink-manager\" class=\"form-field permalink-manager-edit-term permalink-manager\">";
			$html .= sprintf( "<th scope=\"row\"><label for=\"custom_uri\">%s</label></th>", $label );
			$html .= "<td><div>";
			$html .= Permalink_Manager_UI_Elements::display_uri_box( $term );
			$html .= "</div></td>";
			$html .= "</tr>";
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
	}

	/**
	 * Add "Custom permalink" input field to "Quick Edit" form
	 *
	 * @param array $columns
	 *
	 * @return array mixed
	 */
	function quick_edit_column( $columns ) {
		return ( is_array( $columns ) ) ? array_merge( $columns, array( 'permalink-manager-col' => __( 'Custom permalink', 'permalink-manager' ) ) ) : $columns;
	}

	/**
	 * Display the URI of the current term in the "Custom permalink" column
	 *
	 * @param string $content The column content.
	 * @param string $column_name The name of the column to display. In this case, we named our column permalink-manager-col.
	 * @param int $term_id The ID of the term.
	 *
	 * @return string
	 */
	function quick_edit_column_content( $content, $column_name, $term_id ) {
		global $permalink_manager_uris, $permalink_manager_options;

		if ( $column_name == "permalink-manager-col" ) {
			$auto_update_val = get_term_meta( $term_id, "auto_update_uri", true );
			$disabled        = ( ! empty( $auto_update_val ) ) ? $auto_update_val : $permalink_manager_options["general"]["auto_update_uris"];

			$uri = ( ! empty( $permalink_manager_uris["tax-{$term_id}"] ) ) ? self::get_term_uri( $term_id ) : self::get_term_uri( $term_id, true );

			$content = sprintf( '<span class="permalink-manager-col-uri" data-disabled="%s">%s</span>', intval( $disabled ), $uri );
		}

		return $content;
	}

	/**
	 * Update URI from "Edit Term" admin page / Set the custom permalink for new term item
	 *
	 * @param int $term_id Term ID.
	 * @param int $tt_term_id Term taxonomy ID.
	 */
	function update_term_uri( $term_id, $tt_term_id ) {
		global $wp_current_filter;

		// Term ID must be defined
		if ( empty( $term_id ) ) {
			return;
		}

		// Validate the nonce (if the permalink editor is displayed)
		if ( isset( $_POST['custom_uri'] ) && ( ! isset( $_POST['permalink-manager-nonce'] ) || ! wp_verify_nonce( $_POST['permalink-manager-nonce'], 'permalink-manager-edit-uri-box' ) ) ) {
			return;
		} // If term was added via "Edit Post" page, use the default URI
		else if ( ! empty( $wp_current_filter[0] ) && strpos( $wp_current_filter[0], 'wp_ajax_add' ) !== false && empty( $_POST['custom_uri'] ) ) {
			$is_new_term = true;
		} else if ( ! empty( $wp_current_filter[0] ) && strpos( $wp_current_filter[0], 'create_' ) !== false ) {
			$is_new_term = true;
		} else {
			$is_new_term = false;
		}

		// Get auto-update URI setting
		if ( isset( $_POST["auto_update_uri"] ) ) {
			$auto_update_mode = intval( $_POST["auto_update_uri"] );
		} else {
			$auto_update_mode = false;
		}

		// Check if the URI is provided in the input field
		if ( ! empty( $_POST['custom_uri'] ) && empty( $is_new_term ) && empty( $_POST['post_ID'] ) ) {
			$new_uri = Permalink_Manager_Helper_Functions::sanitize_title( $_POST['custom_uri'] );
		} else {
			$new_uri = '';
		}

		self::save_uri( $term_id, $new_uri, $is_new_term, $auto_update_mode );
	}

	/**
	 * Save the custom permalink
	 *
	 * @param int|WP_Term $term
	 * @param string $new_uri
	 * @param bool $is_new_term
	 * @param int|bool $auto_update_mode
	 *
	 * @return bool
	 */
	static function save_uri( $term, $new_uri = '', $is_new_term = false, $auto_update_mode = false, $db_save = true ) {
		global $permalink_manager_options;

		// Get the term object
		if ( is_numeric( $term ) ) {
			$term_object = get_term( $term );
		} else if ( is_a( $term, 'WP_Term' ) ) {
			$term_object = $term;
		} else {
			return false;
		}

		$term_id = $term_object->term_id;

		// Check if the term is allowed
		if ( empty( $term_object->taxonomy ) || Permalink_Manager_Helper_Functions::is_term_excluded( $term_object ) || empty( $term_id ) ) {
			return false;
		}

		// Manage 'Auto-update URI' settings
		if ( ! empty( $auto_update_mode ) ) {
			$auto_update_uri = $auto_update_mode;
			update_term_meta( $term_id, "auto_update_uri", $auto_update_mode );
		} else if ( $auto_update_mode === 0 ) {
			$auto_update_uri = $permalink_manager_options['general']['auto_update_uris'];
			delete_term_meta( $term_id, "auto_update_uri" );
		} else {
			$auto_update_uri = get_term_meta( $term_id, "auto_update_uri", true );
			$auto_update_uri = ( ! empty( $auto_update_uri ) ) ? $auto_update_uri : $permalink_manager_options['general']['auto_update_uris'];
		}

		// Get default & native & user-submitted URIs
		$native_uri  = self::get_default_term_uri( $term_object, true );
		$default_uri = self::get_default_term_uri( $term_object );
		$old_uri     = self::get_term_uri( $term_id, false, true );

		if ( ! empty( $new_uri ) && $auto_update_uri != 1 ) {
			$new_uri = Permalink_Manager_Helper_Functions::sanitize_title( $new_uri, true );
		} else if ( $is_new_term || $auto_update_uri == 1 || ( empty( $new_uri ) && empty( $old_uri ) ) || is_null( $new_uri ) ) {
			$new_uri = $default_uri;
		} else {
			$new_uri = '';
		}

		if ( $is_new_term ) {
			$allow_save = apply_filters( 'permalink_manager_allow_new_term_uri', true, $term_object );
		} else {
			$allow_save = apply_filters( 'permalink_manager_allow_update_term_uri', true, $term_object );
		}

		$new_uri = apply_filters( 'permalink_manager_pre_update_term_uri', $new_uri, $term_id, $old_uri, $native_uri, $default_uri );

		// The update URI process is stopped by the hook above or disabled in "Auto-update" settings
		if ( ! $allow_save || ( ! empty( $auto_update_uri ) && $auto_update_uri == 2 ) ) {
			return false;
		}

		//  Save the URI only if $new_uri variable is set
		if ( ! empty( $new_uri ) && $new_uri !== $old_uri ) {
			Permalink_Manager_URI_Functions::save_single_uri( $term_id, $new_uri, true, $db_save );
			$uri_saved = true;
		} // The $new_uri variable is empty or no change is detected
		else {
			$uri_saved = false;
		}

		do_action( 'permalink_manager_updated_term_uri', $term_id, $new_uri, $old_uri, $native_uri, $default_uri, $uri_saved );

		return ( $uri_saved ) ? $new_uri : false;
	}

	/**
	 * Remove URI from options array after term is moved to the trash
	 *
	 * @param int $term_id
	 */
	function remove_term_uri( $term_id ) {
		Permalink_Manager_URI_Functions::remove_single_uri( $term_id, true, true );
	}

}
