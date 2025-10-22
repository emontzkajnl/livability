import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';
import { BlockControls } from '@wordpress/block-editor';
import { ToolbarGroup, DropdownMenu } from '@wordpress/components';
import { shortcode as shortcodeIcon } from '@wordpress/icons';

// Retrieve the merge tags passed from PHP via wp_localize_script
const mergeTags = window.dynamicDataMergeTags.tags;

const withMergeTagToolbar = createHigherOrderComponent((BlockEdit) => {
    return (props) => {
        // Only apply this functionality to the core Paragraph block
        if (props.name !== 'core/paragraph') {
            return <BlockEdit {...props} />;
        }

        const { attributes, setAttributes, isSelected } = props;

        // Function to construct and insert the shortcode string
        const insertMergeTag = (tag) => {
            const { value } = tag;
            const shortcode = `[liv_data field="${value}"]`;
            const content = attributes.content;
            
            setAttributes({
                content: content ? `${content} ${shortcode}` : shortcode,
            });
        };

        return (
            <Fragment>
                <BlockEdit {...props} />
                {isSelected && (
                    <BlockControls group="other">
                        <ToolbarGroup>
                            <DropdownMenu
                                icon={shortcodeIcon}
                                label={__('Insert Dynamic Data', 'gddm')}
                                controls={mergeTags.map((tag) => ({
                                    title: tag.label,
                                    onClick: () => insertMergeTag(tag),
                                }))}
                            />
                        </ToolbarGroup>
                    </BlockControls>
                )}
            </Fragment>
        );
    };
}, 'withMergeTagToolbar');

addFilter(
    'editor.BlockEdit',
    'my-plugin/with-merge-tag-toolbar',
    withMergeTagToolbar
);