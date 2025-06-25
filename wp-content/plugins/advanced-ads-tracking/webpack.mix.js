/* eslint-disable import/no-extraneous-dependencies */
// webpack.mix.js

const mix = require('laravel-mix');
const { join } = require('path');
const packageData = require('./package.json');
require('./tools/laravel-mix/wp-pot');
require('mix-tailwindcss');

// Local config.
let localConfig = {};

try {
	localConfig = require('./webpack.mix.local');
} catch {}

// Webpack Config.
mix.webpackConfig({
	externals: {
		jquery: 'jQuery',
		lodash: 'lodash',
		moment: 'moment',

		// WordPress Packages.
		'@wordpress/api-fetch': 'wp.apiFetch',
	},
});

// Aliasing Paths.
mix.alias({
	'@root': join(__dirname, 'assets/src'),
});

// Browsersync
if (undefined !== localConfig.wpUrl && '' !== localConfig.wpUrl) {
	mix.browserSync({
		proxy: localConfig.wpUrl,
		ghostMode: false,
		notify: false,
		ui: false,
		open: true,
		online: false,
		files: ['assets/css/*.min.css', 'assets/js/*.js', '**/*.php'],
	});
}

/**
 * CSS Files
 */

mix.sass(
	'assets/scss/public-stats.scss',
	'assets/css/public-stats.css'
).tailwind();

mix.sass(
	'assets/scss/admin/wp-dashboard.scss',
	'assets/css/admin/wp-dashboard.css'
).tailwind();

mix.sass(
	'assets/scss/admin/screen-ads-listing.scss',
	'assets/css/admin/screen-ads-listing.css'
).tailwind();

/**
 * JavaScript Files
 */
mix.combine(
	[
		'assets/src/frontend/tracking-util.js',
		'assets/src/frontend/impressions.js',
		'assets/src/frontend/clicks.js',
		'assets/src/frontend/pro.js',
	],
	'assets/js/frontend/tracking.js'
);

mix.combine(
	[
		'assets/src/frontend/ga-instances.js',
		'assets/src/frontend/ga-tracking.js',
	],
	'assets/js/frontend/ga-tracking.js'
);

mix.js(
	'assets/src/frontend/public-stats.js',
	'assets/js/frontend/public-stats.js'
);

mix.js('assets/src/frontend/delayed.js', 'assets/js/frontend/delayed.js');

mix.js(
	'assets/src/admin/wp-dashboard/index.js',
	'assets/js/admin/wp-dashboard.js'
);

mix.js(
	'assets/src/admin/screen-ads-listing/index.js',
	'assets/js/admin/screen-ads-listing.js'
);

/**
 * WordPress translation
 */
if (process.argv.includes('wpPot')) {
	mix.wpPot({
		output: packageData.wpPot.output,
		file: packageData.wpPot.file,
		skipJS: true,
		domain: packageData.wpPot.domain,
	});
}
