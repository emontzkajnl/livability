<?php

namespace ACP\Column\Post;

use AC;
use ACP\Sorting;

class Revisions extends AC\Column
    implements AC\Column\AjaxValue, Sorting\Sortable
{

    public function __construct()
    {
        $this->set_type('column-revisions');
        $this->set_label(__('Revisions', 'codepress-admin-columns'));
    }

    public function get_value($post_id)
    {
        $value = $this->get_raw_value($post_id);

        if ( ! $value) {
            return $this->get_empty_char();
        }

        return ac_helper()->html->get_ajax_modal_link(
            sprintf(_n('%s revision', '%s revisions', $value, 'codepress-admin-columns'), $value),
            [
                'title' => __('Revisions', 'codepress-admin-columns') . ': ' . get_the_title($post_id),
                'edit_link' => get_edit_post_link($post_id),
                'id' => $post_id,
            ]
        );
    }

    public function get_raw_value($post_id)
    {
        $revisions = wp_get_post_revisions($post_id);

        return count($revisions);
    }

    public function get_ajax_value($post_id)
    {
        $result = [];

        foreach (wp_get_post_revisions($post_id) as $revision) {
            $result[] = '<div class="acp-row-revision">' . wp_post_revision_title_expanded($revision) . '</div>';
        }

        return implode('', $result);
    }

    public function is_valid()
    {
        return post_type_supports($this->get_post_type(), 'revisions');
    }

    public function sorting()
    {
        return new Sorting\Model\Post\Revisions();
    }

}