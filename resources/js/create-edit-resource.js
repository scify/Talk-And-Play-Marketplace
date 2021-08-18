import {Modal} from 'bootstrap';

(function () {
    $(document).ready(function () {
        init();
    });

    var listenForModalSoundChanges = function listenForModalSoundChanges() {
        $('#modal_sound_file').on("change", function ($event) {
            $('#modal_player').hide('fast');
            $("#modal_mp3_src").attr("src", URL.createObjectURL($event.target.files[0]));
            var audio = $("#modal_player");
            audio[0].pause();
            audio[0].load();
            audio[0].play();
            audio[0].oncanplaythrough = audio[0].play();
            $('#modal_player').show('slow');
        });
    }; // data attr in $this


    let listenForSoundChanges = function () {
        $('#sound_file').on("change", function ($event) {
            $('#player').hide('fast');
            $("#mp3_src").attr("src", URL.createObjectURL($event.target.files[0]));
            let audio = $("#player");
            audio[0].pause();
            audio[0].load();
            audio[0].play();
            audio[0].oncanplaythrough = audio[0].play();
            $('#player').show('slow');
        });
    }

    let listenForNewCardClick = function () {
        $('#newCardBtn').on("click", function () {

            try {
                document.getElementById("ToDelete").remove();
            } catch ($e) {
            }
            try {
                document.getElementById("ModalLabel").remove();
            } catch ($e) {
            }

            let type_id =  $("#type_id").attr('value');


            const route = window.route('resources.store,[type_id=>\''.concat(type_id).concat('\'])'));

            $('#modal_player').hide();
            $('#md-modal-form').attr('action', route);
            $("#modalHeader").prepend('<h5 class="modal-title w-100" id="ModalLabel">Προσθήκη Νέας Κάρτας</h5>');
            $("#modal_mp3_src").attr('src', null);
            $("#modal_url").attr('src', null);
            $("#cardId").attr('value', null)
            $("#modal_category_name").attr('value', null)

            let modal = document.getElementById('newCardModal');
            new Modal(modal).show();
        });
    }

    let listenForDeleteCardClick = function() {
        $('.deleteCardBtn').on("click", function () {
            let modal = document.getElementById('deleteConfirmationModal');
            let card = $(this).parents('.card');
            let card_title = card.children('.card-title').children('p').css(
                {"color": "green", "border": "2px solid green"});
            console.log(card_title.html());
            let card_id = card.children('input').attr('value');

            const route = window.route('resources.destroy', card_id);
            $("#md-delete-form").attr('action',route)

            console.log(route);

            new Modal(modal).show();
        });
    }


        let listenForSaveBundleClick = function() {
            $('#saveBundleBtn').on("click", function () {
                let id = $("#packageId").attr('value');
                let modal = document.getElementById('saveBundleModal');
                const route = window.route('resources.approve',id);
                $("#md-save-bundle-form").attr('action',route)
                new Modal(modal).show();
            });
        }

        let listenForEditCardClick = function () {
        $('.editCardBtn').on("click", function () {


            document.getElementById("ModalLabel").remove();
            $("#modalHeader").prepend('<h5 class="modal-title w-100" id="ModalLabel">Επεξεργασία Κάρτας</h5>');
            $("#md-modal-form").append('<input id=\'ToDelete\' type="hidden" name="_method" value="PUT">')

            let card = $(this).parents('.card');

            let card_title = card.children('.card-title').children('p').css(
                {"color": "green", "border": "2px solid green"}
            );
            let card_img = card.children('.card-img-top').attr('src');
            let card_audio = card.children('.card-body').children('audio').children('source').attr('src');
            let card_id = card.children('input').attr('value');

            let type_id =  $("#type_id").attr('value');
            // const route = window.route('resources.update_resource,[\'id\' => '.concat(card_id).concat(', \'type_id\' => ').concat(type_id).concat(']'));
            const route = window.route('resources.update', card_id);

            //$('#md-modal-form').attr('method', 'PUT');
            $('#md-modal-form').attr('action', route);

            console.log(route);
            console.log(card_title.html());
            console.log(card_img);
            console.log(card_audio);
            console.log(card_id);

            //let modal_sound = document.getElementById('modal_mp3_src');
            $("#modal_mp3_src").attr('src', card_audio);

            //let modal_img = document.getElementById('modal_url');
            $("#modal_url").attr('src', card_img);

            //let modal_card_id = document.getElementById('cardId');
            $("#cardId").attr('value', card_id)
            $("#modal_category_name").attr('value', card_title.html())
            let modal = document.getElementById('newCardModal');
            new Modal(modal).show();
            $('#modal_url').show('slow');
            var audio = $("#modal_player");
            audio[0].pause();
            audio[0].load();
            audio[0].play();
            audio[0].oncanplaythrough = audio[0].play();
            $('#modal_player').show('slow');


            // 1. Traverse the card elemnt to get id, name, image, audio --- DONE
            // 2. populate the form elements of the modal (the id will go in a hidden input) -- DONE
            // 3. Open the modal programmatically via Javascript (#.modal) Prwta pernaw ta pedia kai meta kanw trigger na anoiksei -- DONE
            // 4. the modal form should have the update card url, with the selected card id (/communication-resources/100/update) . This entire url should be set via Javascript
        });
    }



    let listenForModalImageChanges = function () {
        $('#modal_upload_img').on("change", function ($event) {
            $('#modal_url').hide('fast');
            let url = document.getElementById('modal_url');
            url.src = URL.createObjectURL($event.target.files[0]);
            url.onload = function () {
                URL.revokeObjectURL(url.src) // free memory
            }
            $('#modal_url').show('slow');
        });
    }


    let listenForImageChanges = function () {
        $('#upload_img').on("change", function ($event) {
            $('#url').hide('fast');
            let url = document.getElementById('url');
            url.src = URL.createObjectURL($event.target.files[0]);
            url.onload = function () {
                URL.revokeObjectURL(url.src) // free memory
            }
            $('#url').show('slow');
        });
    }

    let scrollToButton = function () {
        let newCardButton = $("#newCardBtn");
        if (newCardButton.length) {
            $('html, body').animate({
                scrollTop: newCardButton.offset().top
            }, 2000);
            if (newCardButton.is(':visible')) {
                let form = document.getElementById('md-form');
                $(form).css('background-color', 'rgba(128, 128, 128, 0.1)');
            }
        }
    }


    let init = function () {
        listenForImageChanges();
        listenForSoundChanges();
        listenForModalSoundChanges();
        listenForModalImageChanges();
        scrollToButton();
        listenForEditCardClick();
        listenForNewCardClick();
        listenForDeleteCardClick();
        listenForSaveBundleClick();
    };

})();


