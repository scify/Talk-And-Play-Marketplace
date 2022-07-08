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
            if (announcement.severity)
                modalEl.find("#update_severity").val(announcement.severity);
            if (announcement.type)
                modalEl.find("#update_type").val(announcement.type);
            for (let i = 0; i < window.languages.length; i++) {
                const translation = getAnnouncementTranslationForLang(announcement, window.languages[i].id);
                modalEl.find("#update_title_" + window.languages[i].id).val(translation.title);
                if (translation.message)
                    modalEl.find("#update_message_" + window.languages[i].id).val(translation.message);
                if (translation.link)
                    modalEl.find("#update_link_" + window.languages[i].id).val(translation.link);
            }
            const modal = new Modal(document.getElementById("updateDesktopAppAnnouncementModal"));
            modal.show();
        });
    };

    let getAnnouncement = function (id) {
        for (let i = 0; i < window.announcements.length; i++) {
            if (window.announcements[i].id === id)
                return window.announcements[i];
        }
        throw new Error("announcement with id: " + id + " not found.");
    };

    let getAnnouncementTranslationForLang = function (announcement, langId) {
        for (let i = 0; i < announcement.translations.length; i++) {
            if (announcement.translations[i].lang_id === langId)
                return announcement.translations[i];
        }
        throw new Error("announcement translation with lang id: " + langId + " not found for translation with id: " + announcement.id + ".");
    };

    let init = function () {
        dropAnnouncementHandler();
        updateAnnouncementHandler();
    };
})();
