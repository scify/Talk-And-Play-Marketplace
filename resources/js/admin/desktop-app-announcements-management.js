import {Modal} from "bootstrap";

(function () {
    $(document).ready(function () {
        init();
    });

    let dropAnnouncementHandler = function () {
        $("body").on("click", ".drop-announcement", function (e) {
            e.stopPropagation();
            e.preventDefault();
            const annId = $(this).data("announcementId");
            const annTitle = $(this).data("announcementTitle");
            const modalEl = $("#dropDesktopAppAnnouncementModal");
            modalEl.find("#announcement-title").html(annTitle);
            let url = modalEl.find("#deleteAnnouncementForm").attr("action");
            url = url.substr(0, url.lastIndexOf("/"));
            modalEl.find("#deleteAnnouncementForm").attr("action", url + "/" + annId);
            const modal = new Modal(document.getElementById("dropDesktopAppAnnouncementModal"));
            modal.show();
        });
    };

    let updateAnnouncementHandler = function () {
        $("body").on("click", ".update-announcement", function (e) {
            e.stopPropagation();
            e.preventDefault();
            const modalEl = $("#updateDesktopAppAnnouncementModal");
            let url = modalEl.find("#updateAnnouncementForm").attr("action");
            url = url.substr(0, url.lastIndexOf("/"));
            const announcement = getAnnouncement($(this).data("announcementId"));
            modalEl.find("#updateAnnouncementForm").attr("action", url + "/" + announcement.id);
            modalEl.find("#update_default_title").val(announcement.default_title);


            modalEl.find("#update_min_version").val(announcement.min_version);
            modalEl.find("#update_max_version").val(announcement.max_version);
            if (announcement.severity)
                modalEl.find("#update_severity").val(announcement.severity);
            if (announcement.type)
                modalEl.find("#update_type").val(announcement.type);
            for (const element of window.languages) {
                const translation = getAnnouncementTranslationForLang(announcement, element.id);
                modalEl.find("#update_title_" + element.id).val(translation.title);
                if (translation.message)
                    modalEl.find("#update_message_" + element.id).val(translation.message);
                if (translation.link)
                    modalEl.find("#update_link_" + element.id).val(translation.link);
            }
            const modal = new Modal(document.getElementById("updateDesktopAppAnnouncementModal"));
            modal.show();
        });
    };


    let activateAnnouncementHandler = function activateAnnouncementHandler() {
        $("body").on("click", ".activate-announcement", function (e) {
            e.stopPropagation();
            e.preventDefault();
            let annId = $(this).data("announcementId");
            let annTitle = $(this).data("announcementTitle");
            let modalEl = $("#activateDesktopAppAnnouncementModal");
            modalEl.find("#announcement-title").html(annTitle);
            let url = modalEl.find("#activateAnnouncementForm").attr("action");
            url = url.substr(0, url.lastIndexOf("/"));
            modalEl.find("#activateAnnouncementForm").attr("action", url + "/" + annId);
            const modal = new Modal(document.getElementById("activateDesktopAppAnnouncementModal"));
            modal.show();
        });
    };

    let deactivateAnnouncementHandler = function deactivateAnnouncementHandler() {
        $("body").on("click", ".deactivate-announcement", function (e) {
            e.stopPropagation();
            e.preventDefault();
            let annId = $(this).data("announcementId");
            let annTitle = $(this).data("announcementTitle");
            let modalEl = $("#deactivateDesktopAppAnnouncementModal");
            modalEl.find("#announcement-title").html(annTitle);
            let url = modalEl.find("#deactivateAnnouncementForm").attr("action");
            url = url.substr(0, url.lastIndexOf("/"));
            modalEl.find("#deactivateAnnouncementForm").attr("action", url + "/" + annId);
            const modal = new Modal(document.getElementById("deactivateDesktopAppAnnouncementModal"));
            modal.show();
        });
    };

    let getAnnouncement = function (id) {
        for (const element of window.announcements) {
            if (element.id === id)
                return element;
        }
        throw new Error("announcement with id: " + id + " not found.");
    };

    let getAnnouncementTranslationForLang = function (announcement, langId) {
        for (const element of announcement.translations) {
            if (element.lang_id === langId)
                return element;
        }
        throw new Error("announcement translation with lang id: " + langId + " not found for translation with id: " + announcement.id + ".");
    };

    //
    // let adjustMaxVersionRange = function(){
    //     $("body").on("click", ".set-min-version", function (e) {
    //         e.stopPropagation();
    //         e.preventDefault();
    //         let modalEl = $("#updateDesktopAppAnnouncementModal");
    //         // let url = modalEl.find("#updateAnnouncementForm").attr("action");
    //         // url = url.substr(0, url.lastIndexOf("/"));
    //         let url = modalEl.find("#set_min_version").val();
    //         console.log(url);
    //         // console.log(minVersionValue);
    //     });
    //
    // };

    let init = function () {
        // adjustMaxVersionRange();
        dropAnnouncementHandler();
        updateAnnouncementHandler();
        activateAnnouncementHandler();
        deactivateAnnouncementHandler();
    };
})();
