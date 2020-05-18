module.exports = {
	"env": {
		"browser": true,
		"es6": true,
		"jquery": true
	},
	"extends": "eslint:recommended",
	"globals": {
		"Atomics": "readonly",
		"SharedArrayBuffer": "readonly",
		"wp": "readonly",
		"ajaxurl": "readonly",
		"tinymce": "readonly",
		"campus_a11y_insights": "readonly",
		"require": "readonly"
	},
	"ignorePatterns": [ "**/*.min.js" ],
	"parserOptions": {
		"ecmaVersion": 2018,
		"sourceType": "module"
	},
	"rules": {
		"no-extra-semi": 0,
		"no-extra-boolean-cast": 0,
		"no-unused-vars": 0
	}
};
