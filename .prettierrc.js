module.exports = {
	trailingComma: 'all',
	tabWidth: 2,
	useTabs: true,
	semi: true,
	singleQuote: true,
	bracketSameLine: true,
	plugins: ['@trivago/prettier-plugin-sort-imports'],
	importOrder: [
		'^@wordpress/(.*)$',
		'<THIRD_PARTY_MODULES>',
		'^@library/(.*)$',
		'^@launch/(.*)$',
		'^@assist/(.*)$',
		'^@draft/(.*)$',
		'^@help-center/(.*)$',
		'^[./]',
	],
	overrides: [
		{
			files: ['**/*.css', '**/*.html'],
			options: {
				singleQuote: false,
			},
		},
	],
};