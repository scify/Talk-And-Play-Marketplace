import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue2";

// Port 5179 is exposed in .ddev/config.yaml (web_extra_exposed_ports).
const ddevServer = process.env.IS_DDEV_PROJECT === "true"
	? {
		host: "0.0.0.0",
		port: 5179,
		strictPort: true,
		origin: `${process.env.DDEV_PRIMARY_URL_WITHOUT_PORT}:5179`,
		cors: { origin: process.env.DDEV_PRIMARY_URL },
	}
	: {};

export default defineConfig({
	plugins: [
		laravel({
			input: [
				"resources/js/app.js",
				"resources/js/admin/user-management.js",
				"resources/js/admin/desktop-app-announcements-management.js",
				"resources/js/home.js",
				"resources/js/create-edit-resource.js",
				"resources/sass/app.scss",
				"resources/sass/user-management-page.scss",
				"resources/sass/desktop-app-announcements-management-page.scss",
				"resources/sass/footer.scss",
				"resources/sass/home.scss",
				"resources/sass/resources-packages-index.scss",
				"resources/sass/communication-cards-create-edit.scss",
				"resources/sass/content-guidelines.scss",
				"resources/sass/profile-page.scss",
				"resources/sass/login-page.scss",
			],
			refresh: true,
		}),
		vue({
			template: {
				transformAssetUrls: {
					base: null,
					includeAbsolute: false,
				},
			},
		}),
	],
	resolve: {
		// The Blade layout uses in-DOM Vue templates, so the build must include the template compiler.
		alias: [{ find: /^vue$/, replacement: "vue/dist/vue.esm.js" }],
	},
	server: ddevServer,
	build: {
		sourcemap: true,
	},
	css: {
		preprocessorOptions: {
			scss: {
				quietDeps: true,
				silenceDeprecations: ["import"],
			},
		},
	},
});
