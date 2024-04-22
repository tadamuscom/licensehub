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
		},
	},
	entry: {
		'migratemonkey-settings': './src/Settings/index.js',
	},
	output: {
		filename: '[name]-[chunkhash].js',
		path: resolve(process.cwd(), 'public/build'),
	},
};
