@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/content-guidelines.css') }}">
@endpush

@section('content')
    <section id="intro" class="pt-5 mt-5">
        <div class="container">
            <div style="text-align:left; top:250px; left: 240px; width:880px; height: 88px; font-size: 50px; color: black; font-family: 'Open Sans Extrabold'">
                    Instructions for content creators.
            </div>
            <div>
                <p class="mb-1">Talk & Play Marketplace is an online application implemented and available
                for free by the non-profit SciFY so that everyone can create and share their own content
                for the Talk & Play application.</p>
                <p>The content of Talk & Play Marketplace is mainly aimed at people without mental health
                problems, but who have communication problems due to motor or speech difficulties
                (e.g. tracheostomy combined with motor issues), as well as people with aphasia (e.g. due to brain damage)
                .</p>
            </div>
        </div>
    </section>
    <section id="resources-steps" class="pt-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header" style="
top: 537px;
left:  40px;
width: 1440px;
height: 63px;">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    <span class="title-number">1</span>  <span style="font-size: 22px; font-family: 'Open Sans',sans-serif; color:var(--content-light-blue)">  Content-creation - Communication Cards</span>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                                <div class="accordion-body">
                                    <p>
                                        The communication cards are intended to facilitate the patient/end-user in communicating with their relatives.
                                    </p>
                                    <p><b>
                                        When seeing a card, the patient must be able to understand immediately what it shows so that they can decide if it covers their need.
                                        </b>
                                    <p>
                                        An example is a patient that is thirsty and wants to ask for a glass of water. This need can be covered by creating a card in Talk & Play Marketplace, showing the image of a glass of water and accompanied by an audio file that contains the phrases <b>“I want water”</b> or <b>“thirsty!”</b>.
                                    </p>
                                    <p>
                                        Many Communication Cards together create a Card Package.
                                    </p>
                                   </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header" >
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                    <span class="title-number" >2</span>  <span style="font-size: 22px; font-family: 'Open Sans',sans-serif; color:var(--content-light-blue)"> Content-creation - Game Cards </span>
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse show"
                                 aria-labelledby="headingTwo">
                                <div class="accordion-body">
                                    <p>Game cards aim to entertain the user/patient, with games that keep their mind alert and allow them to train creatively.</p>

                                    <p><span style="color:var(--content-green)!important; font-weight: bold">Talk & Play Marketplace</span> includes games in 3 categories:</p>


                                    <p><b>2.1 Stimulus - Reaction</b></p>
                                    <p>The goal here is for the user to press a button on the keyboard and see something happen on the screen as a result. This way they combine the stimulus (pressing a button) with the reaction (something happening on the screen).</p>
                                    <p>The images that make up the games of this category must be simple and attractive to the end-user.</p>

                                    <p style="margin-top: 12px"><b>2.2 Time Sequence</b></p>
                                    <p style="font-size: 18px">In the games of this category, the user sees a number of images that they have to put in order according to what happened first. Let’s see an example:</p>
                                    <img src={{asset("img/time-sequence-example.png")}}  alt=""; style="top: 1345px;left: 278px; width: 661px; height: 160px; ">
                                    <p style="font-size: 18px; font-style:italic; color: var(--content-orange); font-family: 'Open Sans',sans-serif">In the game above we see the life course of a flower. The user has to place the last image first, the second-to-last second, and so on.</p>

                                    <p style="margin-top: 12px"><b>2.3 Find the Similar</b></p>
                                    <p style="font-size: 18px">In this game, the user has to click on the image that is similar to the image at the lower part of the screen. Let’s see an example:</p>
                                    <img src={{asset("img/find-similar-example.png")}}  alt=""; style="top: 1631px;left: 275px; width: 580px; height: 259px; ">
                                    <p style="font-size: 18px; font-style:italic; color: var(--content-orange); font-family: 'Open Sans',sans-serif">In this game, the user has to click on the image that is similar to the image at the lower part of the screen. Let’s see an example:</p>

                                </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                    <span class="title-number">3</span> <span style="font-size: 22px; font-family: 'Open Sans',sans-serif; color:var(--content-light-blue)"> Notes for finding, editing, and uploading content for the communication/game cards</span>
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse show"
                                 aria-labelledby="headingThree">
                                <div class="accordion-body">
                                    <p><b>4.1 Images</b></p>
                                    <p>The image on the card must show in the clearer way possible the content of the card, in order to cover the user’s need. In the example with the glass of water, we must select an image that meets all the following criteria:</p>
                                    <p>The images that make up the games of this category must be simple and attractive to the end-user.</p>
                                    <p><span class="list-number">1</span> <span style="font-family: 'Open Sans',sans-serif;">It must show a glass of water, but no other objects that may confuse the user and their caregiver.</span></p>
                                    <p><span class="list-number">2</span> <span style="font-family: 'Open Sans',sans-serif;">Its dimensions must be approximately 500 by 500 pixels.</span></p>
                                    <p><span class="list-number">3</span> <span style="font-family: 'Open Sans',sans-serif;">It mustn’t be larger than 2 megabytes.</span></p>
                                    <p><span class="list-number">4</span> <span style="font-family: 'Open Sans',sans-serif;">It must be a .png, .jpg, or .jpeg file.</span></p>
                                    <p><span class="list-number">5</span> <span style="font-family: 'Open Sans',sans-serif;"> It must be able to be used freely, with the appropriate copyright.</span></p>

                                    <p>To crop, reduce or change the type of images we have on our computer or that we have downloaded from the Internet, we can use an online tool, such as <span style="color:var(--content-green); font-family: 'Open Sans Extrabold'"> Online Image Converter.</span>
                                    <p>To find images with copyright that allow their use, we can use the following websites (indicative):                                     or download a tool such as <span style="color:var(--content-green); font-family: 'Open Sans Extrabold'">Pixabay / Unsplash / Pexels / Shutterstock</span></p>

                                    <p style="margin-top:75px"><b>4.2 Sounds</b></p>
                                    <p>The sound that may accompany the card must express in the clearer way possible the content of the card, in order to cover the user’s need. In the example with the glass of water, we must record or select an audio file that meets all the following criteria:</p>

                                    <p><span class="list-number">1</span> <span style="font-family: 'Open Sans',sans-serif;"> The phrase "Glass of water" or "Thirst" should be clearly heard, without noise or other sounds that may confuse the user and their caregiver. </span></p>
                                    <p><span class="list-number">2</span> <span style="font-family: 'Open Sans',sans-serif;"> It mustn’t be larger than 2 megabytes</span></p>
                                    <p><span class="list-number">3</span> <span style="font-family: 'Open Sans',sans-serif;">It must be an .mp3 file </span></p>
                                    <p><span class="list-number">4</span> <span style="font-family: 'Open Sans',sans-serif;">It must be able to be used freely, with the appropriate copyright. </span></p>
                                    <p><span class="list-number">5</span> <span style="font-family: 'Open Sans',sans-serif;">If it is a word/sentence, it must be in the language of the user.</span></p>


                                    <p>To crop, edit or change an audio file we have on our computer, our mobile phone or that we have downloaded from the Internet, we can use an online tool, such as <span style="color:var(--content-green); font-family: 'Open Sans Extrabold'">   Online Mp3 Cutter</span> , or download a tool such as <span style="color:var(--content-green); font-family: 'Open Sans Extrabold'"> Audacity</span>.
                                    <p>  To find audio files with copyright that allow their use, we can use the following websites:  <span style="color:var(--content-green); font-family: 'Open Sans Extrabold',sans-serif">Freesound.org / Pixabay </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{ mix('dist/js/home.js') }}"></script>
@endpush
