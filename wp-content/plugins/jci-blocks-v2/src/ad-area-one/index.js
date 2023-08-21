import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';

registerBlockType( metadata.name, {
	deprecated: [{
		save: () => {
			return (
				<div className="wp-block-jci-blocks-ad-area-one jci-block-placeholder">
					<p>Ad Area One Placeholder</p>
				</div>
			);
		}
	}],
	edit: () => {
		return (
			<div className="jci-block-placeholder">
				<p>Ad Area One Placeholder</p>
			</div>
		);
	},
	save: () => {
		return null;
	},
} );
