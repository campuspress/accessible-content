const path = require('path');

const fname = ( name, ext ) => {
	const infix = 'production' === process.env.NODE_ENV
		? '.min' : '';
	return name + infix + ext;
};

const js = {
	entry: './assets/src/js/index.js',
	output: {
		path: path.resolve(__dirname, 'assets'),
		filename: fname( 'js/campus-a11y', '.js' )
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader'
				} 
			}
		]
	}
};
const admin_js = {
	entry: './assets/src/js/admin.js',
	output: {
		path: path.resolve(__dirname, 'assets'),
		filename: fname( 'js/admin', '.js' )
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader'
				} 
			}
		]
	}
};
const css = {
	entry: './assets/src/sass/index.scss',
	output: {
		path: path.resolve(__dirname, 'assets'),
	},
	module: {
		rules: [
			{
				test: /\.scss$/,
				exclude: /node_modules/,
				use: [
					{
						loader: 'file-loader',
						options: {
							outputPath: 'css/',
							name: fname( 'campus-a11y', '.css' )
						}
					},
					'postcss-loader',
					'sass-loader'
				]
			},
		]
	},
};
const admin_css = {
	entry: './assets/src/sass/admin.scss',
	output: {
		path: path.resolve(__dirname, 'assets'),
	},
	module: {
		rules: [
			{
				test: /\.scss$/,
				exclude: /node_modules/,
				use: [
					{
						loader: 'file-loader',
						options: {
							outputPath: 'css/',
							name: fname( 'admin', '.css' )
						}
					},
					'postcss-loader',
					'sass-loader'
				]
			},
		]
	},
};

module.exports = [ js, admin_js, css, admin_css ];
