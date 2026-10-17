import js from "@eslint/js";
import pluginVue from "eslint-plugin-vue";
import globals from "globals";

export default [
	{
		ignores: ["public/**", "vendor/**", "node_modules/**", "storage/**"],
	},
	js.configs.recommended,
	...pluginVue.configs["flat/vue2-essential"],
	{
		languageOptions: {
			ecmaVersion: "latest",
			sourceType: "module",
			globals: {
				...globals.browser,
				...globals.jquery,
				_: "readonly",
				axios: "readonly",
			},
		},
		rules: {
			"vue/no-use-v-if-with-v-for": "off",
			indent: ["error", "tab"],
			"linebreak-style": ["error", "unix"],
			quotes: ["error", "double"],
			semi: ["error", "always"],
		},
	},
	{
		files: ["vite.config.js", "eslint.config.js"],
		languageOptions: {
			globals: globals.node,
		},
	},
];
