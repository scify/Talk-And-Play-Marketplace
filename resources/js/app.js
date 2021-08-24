import 'bootstrap';
import $ from 'jquery';
import Vue from 'vue';

require('./bootstrap');
window.$ = window.jQuery = $;
window.route = require('./backend-route');
import store from './store/store';

import getLodash from "lodash/get";
import eachRightLodash from "lodash/eachRight";
import replaceLodash from "lodash/replace";


window.translate = function (string, args) {
    let value = getLodash(window.i18n, string);

    eachRightLodash(args, (paramVal, paramKey) => {
        value = replaceLodash(value, `:${paramKey}`, paramVal);
    });
    return value;
}

Vue.prototype.trans = (string, args) => {
    return window.translate(string, args);
};

Vue.component('modal', require('./vue-components/common/ModalComponent').default);
Vue.component('resources-packages-with-filters', require('./vue-components/resources/ResourcesPackagesWithFilters').default);
Vue.component('resource-package', require('./vue-components/resources/ResourcesPackage').default);

const app = new Vue({
    el: '#app',
    store: store
});
(function ($) {

    let init = function () {
        closeDismissableAlerts();
    };

    let closeDismissableAlerts = function () {
        setTimeout(function () {
            /*Close any flash message after some time*/
            $(".alert-dismissible").fadeTo(4000, 500).slideUp(500, function () {
                $(".alert-dismissible").alert('close');
            });
        }, 3000);
    };

    $(function () {
        $(document).ready(function () {
            init();
        })
    });

})(window.jQuery);

