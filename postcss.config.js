const semver = require('semver');
const requiredNodeVersion = require('./package').engines.node;

if (!semver.satisfies(process.version, requiredNodeVersion)) {
	console.log(
		`Please switch to node version ${requiredNodeVersion} to build. You're currently on ${process.version}. Use FNM or NVM to manage node versions and auto switching.`,
	);
	process.exit(1);
}

module.exports = ({ mode }) => ({
	ident: 'postcss',
	sourceMap: mode !== 'production',
	plugins: [
		require('postcss-import'),
		require('tailwindcss/nesting'),
		require('tailwindcss'),
		(css) =>
			css.walkRules((rule) => {
				// Removes top level TW styles like *::before {}
				rule.selector.startsWith('*') && rule.remove();
			}),
		// See: https://github.com/WordPress/gutenberg/blob/trunk/packages/postcss-plugins-preset/lib/index.js
		require('autoprefixer')({ grid: true }),
		mode === 'production' &&
			// See: https://github.com/WordPress/gutenberg/blob/trunk/packages/scripts/config/webpack.config.js#L68
			require('cssnano')({
				preset: [
					'default',
					{
						discardComments: {
							removeAll: true,
						},
					},
				],
			}),
		require('postcss-safe-important'),
	],
});
