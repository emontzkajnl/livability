import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: () => {
		return (
			<div className="jci-block-placeholder">
				<p>Quick Facts Placeholder</p>
			</div>
		);
	},
	save: () => {
		return (
			<div className="wp-block-jci-blocks-quick-facts jci-block-placeholder">
				<p>Quick Facts Placeholder</p>
			</div>
		);
	},
} );
