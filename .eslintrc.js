module.exports = {
	env: {
		browser: true,
		es2021: true,
		jest: true,
		node: true,
	},
	extends: [
		'eslint:recommended',
		'plugin:react/recommended',
		'plugin:react-hooks/recommended',
		'prettier',
	],
	globals: {
		cy: false,
		context: false,
		Cypress: false,
		expect: false,
		assert: false,
	},
	parserOptions: {
		ecmaFeatures: { jsx: true },
		sourceType: 'module',
	},
	plugins: [
		'react',
		'prettier',
		'no-only-tests',
		'@rainforestqa/eslint-plugin',
		'custom-rules',
	],
	rules: {
		'require-await': 'error',
		quotes: ['error', 'single', { avoidEscape: true }],
		'comma-dangle': ['error', 'always-multiline'],
		'array-element-newline': ['error', 'consistent'],
		'no-constant-condition': ['error', { checkLoops: false }],
		'no-multi-spaces': ['error'],
		'no-unused-vars': ['error', { argsIgnorePattern: '^_' }],
		'space-in-parens': ['error', 'never'],
		'key-spacing': ['error', { afterColon: true }],
		'space-infix-ops': ['error'],
		'space-before-function-paren': [
			'error',
			{
				anonymous: 'always',
				named: 'never',
				asyncArrow: 'always',
			},
		],
		'no-only-tests/no-only-tests': 'warn',
		'react/react-in-jsx-scope': 'off',
		'quote-props': ['error', 'as-needed'],
		'react/prop-types': 0,
		'lines-around-comment': [
			'error',
			{
				beforeBlockComment: true,
				allowBlockStart: true,
			},
		],
		'@rainforestqa/no-dangerous-conditional-literals-in-jsx': 'error',
		// https://github.com/facebook/react/issues/11538
		'custom-rules/button-translation-needs-span': 'error',
	},
	settings: { react: { version: 'detect' } },
};
