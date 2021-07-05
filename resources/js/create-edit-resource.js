import {Modal} from 'bootstrap';
(function() {
    $(document).ready(function() {
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


    let listenForSoundChanges = function() {
        $('#sound_file').on("change", function($event){
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

    let listenForEditCardClick = function() {
        $('.editCardBtn').on("click",function () {

            let card = $(this).parents('.card');
            let card_title= card.children('.card-title').children('p').css(
                {"color": "green", "border": "2px solid green"}
            );
            let card_img= card.children('.card-img-top').attr('src');
            let card_audio= card.children('.card-body').children('audio').children('source').attr('src');
            let card_id = card.children('input').attr('value');

            console.log(card_title.html());
            console.log(card_img);
            console.log(card_audio);
            console.log(card_id);

            let modal = document.getElementById('newCardModal');

            // 1. Traverse the card elemnt to get id, name, image, audio --- DONE
            // 2. populate the form elements of the modal (the id will go in a hidden input) todo
            // 3. Open the modal programmatically via Javascript (#.modal) Prwta pernaw ta pedia kai meta kanw trigger na anoiksei
            // 4. the modal form should have the update card url, with the selected card id (/communication-resources/100/update) . This entire url should be set via Javascript
        });
    }


    // data attr in $this


    let listenForModalImageChanges = function(){
        $('#modal_upload_img').on("change", function($event){
            $('#modal_url').hide('fast');
            let url = document.getElementById('modal_url');
            url.src = URL.createObjectURL($event.target.files[0]);
            url.onload = function() {
                URL.revokeObjectURL(url.src) // free memory
            }
            $('#modal_url').show('slow');
        });
    }


    let listenForImageChanges = function(){
        $('#upload_img').on("change", function($event){
            $('#url').hide('fast');
            let url = document.getElementById('url');
            url.src = URL.createObjectURL($event.target.files[0]);
            url.onload = function() {
                URL.revokeObjectURL(url.src) // free memory
             }
            $('#url').show('slow');
        });
    }

    let scrollToButton = function(){
        let newCardButton = $("#newCardBtn");
        if(newCardButton.length) {
            $('html, body').animate({
                scrollTop: newCardButton.offset().top
            }, 2000);
            if (newCardButton.is(':visible')) {
                let form = document.getElementById('md-form');
                $(form).css('background-color', 'rgba(128, 128, 128, 0.1)');
            }
        }
    }

    /*
    let passValuesModal = function(){
        $("#newCardModal").on('click',
            (function () {

                var parentId = document.getElementById('communicationCardId').value;
                $(".modal-body #parentId").val( parentId );
                var existCondition = setInterval(function() { //make sure that parentId has been passed before continuing
                    if ($('#parentId').length ) {
                        clearInterval(existCondition);
                    }
                }, 100); // check every 100ms
            }));
    };
    */



    let init = function() {
        listenForImageChanges();
        listenForSoundChanges();
        listenForModalSoundChanges();
        listenForModalImageChanges();
        scrollToButton();
        listenForEditCardClick();
    };

})();


