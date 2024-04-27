const colors = require('tailwindcss/colors');
delete colors['lightBlue'];
delete colors['warmGray'];
delete colors['trueGray'];
delete colors['coolGray'];
delete colors['blueGray'];

module.exports = {
	content: ['./src/**/*.{html,js,jsx}'],
	theme: {
		screens: {
			xxs: '280px',
			xs: '480px',
			sm: '600px',
			md: '783px', // Admin Bar goes big
			lg: '961px', // Sidebar auto folds
			xl: '1152px',
			'2xl': '1345px',
			'3xl': '1600px',
			'4xl': '1920px',
		},
		order: {},
		extend: {
			colors: {
				...colors,
				tadaBlue: '#142A64',
				tadaLighterBlue: '#1e3d8f',
				tadaGray: '#d9d2d2',
				tadaBlack: '#1c1c1b',
			},
			boxShadow: {
				'inner-sm':
					'inset 0 0 0 1px rgba(0,0,0,0.1),0 3px 15px -3px rgba(0,0,0,0.035),0 0 1px rgba(0,0,0,.025)',
				'inner-md':
					'inset 0 0 0 1px rgba(0,0,0,0.2),0 3px 15px -3px rgba(0,0,0,0.025),0 0 1px rgba(0,0,0,.02)',
				modal:
					' 0 0 0 1px rgba(0,0,0,0.1),0 3px 15px -3px rgba(0,0,0,0.035),0 0 1px rgba(0,0,0,.05)',
				button: '0 0 0 1px var(--ext-design-main,#3959e9)',
				toggle: '0 0 0 2px #fff, 0 0 0 4px var(--ext-design-main,#3959e9)',
				surface: '0 5px 20px 10px rgba(0, 0, 0, 0.03)',
				'2xl-flipped': '0px -25px 50px -12px #00000025',
			},
			maxWidth: {
				'8xl': '98rem',
				'1/4': '25%',
				'1/2': '50%',
				'3/4': '75%',
				52: '13rem',
				72: '18rem',
			},
			minWidth: {
				20: '5rem',
				30: '7.5rem',
				40: '10rem',
				48: '12rem',
				60: '15rem',
				sm: '7rem',
				md: '30rem',
			},
			width: {
				'40vw': '40vw',
			},
			minHeight: {
				16: '4rem',
				20: '5rem',
				40: '10rem',
				48: '12rem',
				60: '15rem',
				half: '50vh',
			},
			maxHeight: {
				half: '50vh',
			},
			fontSize: {
				xss: '11px',
				'3xl': ['2rem', '2.5rem'],
			},
			fontFamily: {
				kanit: ['Kanit', 'sans-serif'],
				poppins: ['Poppins', 'sans-serif'],
			},
			zIndex: {
				high: '99999',
				'max-1': '2147483646',
				max: '2147483647', // Highest the browser allows - don't block WP re-auth modal though
			},
			lineHeight: {
				'extra-tight': '0.5',
			},
		},
	},
};
