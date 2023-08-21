import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: ( { attributes, setAttributes } ) => {
		const blockProps = useBlockProps();
		return (
			<RichText
				{ ...blockProps }
				tagName="h1"
				value={ attributes.content }
				onChange={ ( content ) => setAttributes( { content } ) }
				placeholder={ __( 'Post Title...' ) }
			/>
		);
	},
	save: () => {
		return null;
	},
} );
