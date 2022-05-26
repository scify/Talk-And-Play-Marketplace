import Flickity from "flickity";

(function () {
    let carousel;
    $(document).ready(function () {
        init();
    });

    // data attr in $this
    let initCarousel = function () {
        carousel = new Flickity("#landing-page-intro-carousel", {
            // options
            cellAlign: "left",
            contain: true,
            draggable: true,
            freeScroll: false,
            prevNextButtons: false,
            wrapAround: true,
            setGallerySize: false
        });

        $("body").on("click", "#tnp-app-btn", function (e) {
            carousel.next();
            e.stopPropagation();
        });
    };

    let init = function () {
        initCarousel();
    };
})();
