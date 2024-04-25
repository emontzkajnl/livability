<?php
namespace sgpbrandom;

class Installer
{
	public static function install()
	{
	
	}

	public static function uninstall()
	{
		self::deleteCustomTerms(SG_POPUP_CATEGORY_TAXONOMY);
	}

	/**
	 * Delete Taxonomy by name
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy
	 *
	 * @return void
	 */
	public static function deleteCustomTerms($taxonomy)
	{
		global $wpdb;

		$customTermsQuery = 'SELECT t.name, t.term_id
			FROM '.$wpdb->terms . ' AS t
			INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt
			ON t.term_id = tt.term_id
			WHERE tt.taxonomy = "'.$taxonomy.'"';

		$terms = $wpdb->get_results($customTermsQuery);

		foreach ($terms as $term) {
			if (empty($term)) {
				continue;
			}
			wp_delete_term($term->term_id, $taxonomy);
		}
	}
}
