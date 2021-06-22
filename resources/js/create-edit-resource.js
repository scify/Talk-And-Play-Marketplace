
(function() {
    $(document).ready(function() {
        init();
    });


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
    let init = function() {
        listenForImageChanges();
        listenForSoundChanges();
    };

})();


