const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { resolve } = require('path');
const CopyPlugin = require('copy-webpack-plugin');
const path = require('path');
const WebpackAssetsManifest = require('webpack-assets-manifest');
const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const CaseSensitivePathsPlugin = require('case-sensitive-paths-webpack-plugin');

module.exports = {
	...defaultConfig,
	devServer: {
		...defaultConfig.devServer,
		host: process.env.WP_DEVHOST || 'wordpress.test',
	},
	plugins: [
		...defaultConfig.plugins,
		new CaseSensitivePathsPlugin(),
		new CleanWebpackPlugin(),
		new WebpackAssetsManifest({
			output: path.resolve(process.cwd(), 'public/build/manifest.json'),
			publicPath: true,
			writeToDisk: true,
		}),
		new MiniCSSExtractPlugin({ filename: '[name]-[chunkhash].css' }),
	],
	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve.alias,
			'@settings': resolve(__dirname, 'src/Settings'),
			'@products': resolve(__dirname, 'src/Products'),
			'@licenses': resolve(__dirname, 'src/Licenses'),
			'@api': resolve(__dirname, 'src/Api'),
		},
	},
	entry: {
		'licensehub-settings': './src/Settings/index.js',
		'licensehub-products': './src/Products/index.js',
		'licensehub-licenses': './src/Licenses/index.js',
		'licensehub-api': './src/Api/index.js',
	},
	output: {
		filename: '[name]-[chunkhash].js',
		path: resolve(process.cwd(), 'public/build'),
	},
};