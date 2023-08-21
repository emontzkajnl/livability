import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: () => {
		return (
			<div className="jci-block-placeholder">
				<p>One Hundred List Placeholder</p>
			</div>
		);
	},
	save: () => {
		return null;
	},
} );
