const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { resolve } = require('path');
const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const CaseSensitivePathsPlugin = require('case-sensitive-paths-webpack-plugin');

module.exports = {
	...defaultConfig,
	plugins: [
		...defaultConfig.plugins,
		new CaseSensitivePathsPlugin(),
		new CleanWebpackPlugin(),
		new MiniCSSExtractPlugin({ filename: '[name].css' }),
	],
	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve.alias,
			'@global': resolve(__dirname, 'src/global'),
			'@settings': resolve(__dirname, 'src/Settings'),
			'@products': resolve(__dirname, 'src/Products'),
			'@licenses': resolve(__dirname, 'src/Licenses'),
			'@api': resolve(__dirname, 'src/Api'),
			'@releases': resolve(__dirname, 'src/Releases'),
		},
	},
	entry: {
		'licensehub-releases': './src/Releases/index.js',
		'licensehub-settings': './src/Settings/index.js',
		'licensehub-products': './src/Products/index.js',
		'licensehub-licenses': './src/Licenses/index.js',
		'licensehub-api': './src/Api/index.js',
		'licensehub-global': './src/global/index.js',
	},
	output: {
		filename: '[name].js',
		path: resolve(process.cwd(), 'public/build'),
	},
};
