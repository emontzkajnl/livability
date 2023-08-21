import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { SelectControl, Button, TextControl } from '@wordpress/components';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: ( { attributes, setAttributes } ) => {
		const setIcon = event => {setAttributes( {icon:event.target.value})}
		return (
			<>
				<SelectControl
					label={ __( 'Select Icon', 'jci-blocks' ) }
					value={ attributes.icon }
					onChange={setIcon}
					// onChange={ ( value) => {
					// 	setAttributes( { icon:value } );
					// } }

					options={ [
						{ label: 'quotes', value: 'quotes' },
						{ label: 'active', value: 'active' },
						{ label: 'adventure', value: 'adventure' },
						{ label: 'city', value: 'city' },
						{ label: 'education', value: 'education' },
						{ label: 'food', value: 'food' },
						{ label: 'fun fact', value: 'fun-fact' },
						{ label: 'health', value: 'health' },
						{ label: 'link', value: 'link' },
						{ label: 'logo', value: 'logo' },
						{ label: 'love', value: 'love' },
						{ label: 'metro', value: 'metro' },
						{ label: 'music', value: 'music' },
						{ label: 'neighborhood', value: 'neighborhood' },
						{ label: 'nightlife', value: 'nightlife' },
						{ label: 'pets', value: 'pets' },
						{ label: 'question mark', value: 'question-mark' },
						{ label: 'tourism', value: 'tourism' },
						{ label: 'transportation', value: 'transportation' },
					] }
				/>
				<RichText
					tagName="p"
					className="info-box-quote"
					value={ attributes.text }
					onChange={ ( text ) => setAttributes( { text } ) }
					placeholder={ __( 'Quote...', 'jci_blocks' ) }
				/>
				<RichText
					tagName="p"
					className="info-box-name"
					value={ attributes.name }
					onChange={ ( name ) => setAttributes( { name } ) }
					placeholder={ __( 'Name...', 'jci_blocks' ) }
				/>
				<RichText
					tagName="p"
					className="info-box-position"
					value={ attributes.position }
					onChange={ ( position ) => setAttributes( { position } ) }
					placeholder={ __( 'Position...', 'jci_blocks' ) }
				/>
				<TextControl
					label="Button Text (optional)"
					value={ attributes.buttonText }
					onChange={ ( buttonText ) =>
						setAttributes( { buttonText } )
					}
				/>
				<TextControl
					label="Button Link (optional)"
					value={ attributes.buttonLink }
					onChange={ ( buttonLink ) =>
						setAttributes( { buttonLink } )
					}
				/>
			</>
		);
	},
	save: ( { attributes } ) => {
		// const blockProps = useBlockProps.save();
		const { text, name, position, icon, buttonText, buttonLink } =
			attributes;

		return (
			<div className={ icon, "wp-block-jci-blocks-info-box" }>
				<RichText.Content
					tagName="p"
					className="info-box-quote"
					value={ text }
				/>
				<RichText.Content
					tagName="p"
					className="info-box-name"
					value={ name }
				/>
				<RichText.Content
					tagName="p"
					className="info-box-position"
					value={ position }
				/>
				{ buttonText && buttonLink && (
					<Button
						href={ buttonLink }
						target="_blank"
						rel="noopener noreferrer"
						text={ buttonText }
						className="info-box-button"
					/>
				) }
			</div>
		);
	},
} );
