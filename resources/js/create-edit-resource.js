
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

        $('html, body').animate({
            scrollTop: $("#newCardId").offset().top
        }, 2000);
        if($("#newCardId").is(':visible')){
            let form = document.getElementById('md-form');
            $(form).css('background-color', 'rgba(128, 128, 128, 0.1)');
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
    };

})();


