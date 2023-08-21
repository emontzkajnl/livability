import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import metadata from './block.json';

registerBlockType( metadata.name, {
	attributes: {
		header: {
			type: 'string',
			source: 'html',
			selector: 'h2',
		},
	},
	edit: ( { attributes, setAttributes } ) => {
		const blockProps = useBlockProps();

		return (
			<RichText
				{ ...blockProps }
				tagName="h2"
				value={ attributes.header }
				onChange={ ( header ) => setAttributes( { header } ) }
				placeholder={ __( 'Post Title...' ) }
			/>
		);
	},
	save: ( { attributes } ) => {
		const blockProps = useBlockProps.save();
		return (
			<RichText.Content
				{ ...blockProps }
				tagName="h2"
				className="green-line"
				value={ attributes.header }
			/>
		);
	},
} );
