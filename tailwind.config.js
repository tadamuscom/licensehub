const colors = require('tailwindcss/colors');
delete colors['lightBlue'];
delete colors['warmGray'];
delete colors['trueGray'];
delete colors['coolGray'];
delete colors['blueGray'];

module.exports = {
	content: ['./src/**/*.{html,js,jsx}'],
	theme: {
		extend: {
			colors: {
				...colors,
				tadaBlue: '#142A64',
				tadaLighterBlue: '#1e3d8f',
				tadaGray: '#d9d2d2',
				tadaBlack: '#1c1c1b',
			},
		},
	},
};
