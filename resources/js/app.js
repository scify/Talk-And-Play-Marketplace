import "bootstrap";
import $ from "jquery";
import Vue from "vue";

require("./bootstrap");
window.$ = window.jQuery = $;
window.route = require("./backend-route");
import store from "./store/store";

import getLodash from "lodash/get";
import eachRightLodash from "lodash/eachRight";
import replaceLodash from "lodash/replace";

import * as Sentry from "@sentry/browser";

if(process.env.MIX_SENTRY_DSN_PUBLIC) {
	Sentry.init({
		dsn: process.env.MIX_SENTRY_DSN_PUBLIC,
	});
}

window.translate = function (string, args) {
	let value = getLodash(window.i18n, string);

	eachRightLodash(args, (paramVal, paramKey) => {
		value = replaceLodash(value, `:${paramKey}`, paramVal);
	});
	return value;
};

Vue.prototype.trans = (string, args) => {
	return window.translate(string, args);
};

// eslint-disable-next-line vue/multi-word-component-names
Vue.component("modal", require("./vue-components/common/ModalComponent").default);
// Vue.component('pending-resources-packages-with-filters', require('./vue-components/resources/PendingResourcesPackagesWithFilters').default);
Vue.component("resources-packages-with-filters", require("./vue-components/resources/ResourcesPackagesWithFilters").default);
Vue.component("resource-package", require("./vue-components/resources/ResourcesPackage").default);


new Vue({
	el: "#app",
	store: store
});
(function ($) {

	let init = function () {
		closeDismissableAlerts();
	};

	let closeDismissableAlerts = function () {
		setTimeout(function () {
			/*Close any flash message after some time*/
			$(".alert-dismissible").fadeTo(4000, 500).slideUp(500);
		}, 3000);
	};

	$(function () {
		$(document).ready(function () {
			init();
		});
	});

})(window.jQuery);

