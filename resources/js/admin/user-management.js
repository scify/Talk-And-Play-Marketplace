import { Modal } from 'bootstrap';

(function() {
    $(document).ready(function() {
        init();
    });

    // data attr in $this
    let dropAccountHandler = function() {
        $("body").on("click", ".drop-account", function(e) {
            e.stopPropagation();
            e.preventDefault();
            const userId = $(this).data("userId");
            const userName = $(this).data("userName");
            const modalEl = $("#dropUserAccountModal");
            modalEl.find("#user-name").html(userName);
            let url = modalEl.find('#deleteUserForm').attr('action');
            url = url.substr(0, url.lastIndexOf('/'));
            modalEl.find('#deleteUserForm').attr('action', url + '/' + userId);
            const modal = new Modal(document.getElementById('dropUserAccountModal'));
            modal.show();
        });
    };

    let init = function() {
        dropAccountHandler();
    };
})();
