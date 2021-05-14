import 'bootstrap';
import $ from 'jquery';
window.$ = window.jQuery = $;

(function ($) {

    let init = function() {
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
