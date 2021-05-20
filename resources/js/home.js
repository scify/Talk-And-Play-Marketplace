import Flickity from 'flickity';

(function() {
    $(document).ready(function() {
        init();
    });

    // data attr in $this
    let initCarousel = function() {
        const carousel = new Flickity( '#landing-page-intro-carousel', {
            // options
            cellAlign: 'left',
            contain: true,
            draggable: true,
            freeScroll: false,
            prevNextButtons: false,
            wrapAround: true,
            setGallerySize: false
        });
    };

    let init = function() {
        initCarousel();
    };
})();
