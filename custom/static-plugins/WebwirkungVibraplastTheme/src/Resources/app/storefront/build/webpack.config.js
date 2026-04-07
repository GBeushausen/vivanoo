const { join, resolve } = require('path');

module.exports = () => {
	return {
		resolve: {
			alias: {
			
			}
		},
		module: {
			rules: [
				{
					test: /\.css$/i,
					use: ["style-loader", "css-loader"],
				},
			],
		},
	};
}