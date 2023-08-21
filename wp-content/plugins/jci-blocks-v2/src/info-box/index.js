import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { SelectControl, Button, TextControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import metadata from './block.json';

registerBlockType( metadata.name, {
	deprecated: [
		{
			save: ( { attributes } ) => {
				const blockProps = useBlockProps.save();
				const { text, name, position, icon, buttonText, buttonLink } =
					attributes;
		
				return (
					<div className={ `wp-block-jci-blocks-info-box ${icon}` }>
						<RichText.Content
						{ ...blockProps }
							tagName="p"
							className="info-box-quote"
							value={ text }
						/>
						<RichText.Content
						{ ...blockProps }
							tagName="p"
							className="info-box-name"
							value={ name }
						/>
						<RichText.Content
						{ ...blockProps }
							tagName="p"
							className="info-box-position"
							value={ position }
						/>
						{ buttonText && buttonLink && (
							<Button
								href={ buttonLink }
								target="_blank"
								text={ buttonText }
								className="info-box-button"
							/>
						) }
					</div>
				);
			},
		}
	],
	edit: ( {attributes, setAttributes }) => {
		console.log( 'attributes edit ', attributes );
		const blockProps = useBlockProps();
		const { icon, text, name, position, buttonText, buttonLink} = attributes;
		// const [icon] = attributes;
		// console.log( 'icon is ',icon);
		// const [ myIcon, setIcon ] = useState('quotes'); 
		return (
			<>
				<SelectControl
					label={ __( 'Select Icon', 'jci-blocks' ) }
					value={ icon }
					className={ "select-test" }
					onChange={ ( value ) => setAttributes( { icon: value } )}
					// onChange={ ( newIcon ) => setIcon( newIcon )}
					options={ [
						{ label: 'quotes', value: 'quotes' },
						{ label: 'active', value: 'active' },
						{ label: 'adventure', value: 'adventure' },
						{ label: 'city', value: 'city' },
						{ label: 'dollar', value: 'dollar' },
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
						{ label: 'sports', value: 'sports' },
						{ label: 'tourism', value: 'tourism' },
						{ label: 'transportation', value: 'transportation' },
					] }
				/>
				<RichText
				{ ...blockProps }
					tagName="p"
					className="info-box-quote"
					value={ text }
					onChange={ ( text ) => setAttributes( text ) }
					placeholder={ __( 'Quote...', 'jci_blocks' ) }
				/>
				<RichText
				{ ...blockProps }
					tagName="p"
					className="info-box-name"
					value={ name }
					onChange={ ( name ) => setAttributes( { name } ) }
					placeholder={ __( 'Name...', 'jci_blocks' ) }
				/>
				<RichText
				{ ...blockProps }
					tagName="p"
					className="info-box-position"
					value={ position }
					onChange={ ( position ) => setAttributes( { position } ) }
					placeholder={ __( 'Position...', 'jci_blocks' ) }
				/>
				<TextControl
					label="Button Text (optional)"
					value={ buttonText }
					onChange={ ( buttonText ) =>
						setAttributes( { buttonText } )
					}
				/>
				<TextControl
					label="Button Link (optional)"
					value={ buttonLink }
					onChange={ ( buttonLink ) =>
						setAttributes( { buttonLink } )
					}
				/>
			</>
		);
	},
	save: ( { attributes } ) => {
		const blockProps = useBlockProps.save();
		const { text, name, position, icon, buttonText, buttonLink } =
			attributes;

		return (
			<div className={ `wp-block-jci-blocks-info-box ${icon}` }>
				<RichText.Content
				{ ...blockProps }
					tagName="p"
					className="info-box-quote"
					value={ text }
				/>
				<RichText.Content
				{ ...blockProps }
					tagName="p"
					className="info-box-name"
					value={ name }
				/>
				<RichText.Content
				{ ...blockProps }
					tagName="p"
					className="info-box-position"
					value={ position }
				/>
				{/* { buttonText && buttonLink && (
					<Button
						href={ buttonLink }
						target="_blank"
						text={ buttonText }
						className="info-box-button"
					/>
				) } */}
			</div>
		);
	},
} );
