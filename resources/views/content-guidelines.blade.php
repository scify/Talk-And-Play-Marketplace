@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/content-guidelines.css') }}">
@endpush

@section('content')
    <section id="intro" class="pt-5 mt-5">
        <div class="container">
            <div style="text-align:left; top:250px; left: 240px; width:880px; height: 88px; font-size: 50px; color: black; font-family: 'Open Sans Extrabold'">
                {!! __('messages.guidelines_instructions_content_creators') !!}
            </div>
            <div>
                {!! __('messages.guidelines_marketplace_brief_intro') !!}
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
                                    <span class="title-number">1</span>  <span style="font-size: 22px; font-family: 'Open Sans',sans-serif; color:var(--content-light-blue)">   {!! __('messages.guidelines_content_creation_communication_cards') !!}</span>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                                <div class="accordion-body">
                                    <p>
                                        {!! __('messages.guidelines_communication_cards_intro') !!}
                                    </p>
                                    <p>
                                        <strong>
                                            {!! __('messages.guidelines_communication_cards_understanding') !!}
                                        </strong>
                                    <p>
                                        {!! __('messages.guidelines_communication_cards_example') !!}
                                    </p>
                                    <p>
                                        {!! __('messages.guidelines_communication_package') !!}
                                    </p>
                                   </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header" >
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                    <span class="title-number" >2</span>  <span style="font-size: 22px; font-family: 'Open Sans',sans-serif; color:var(--content-light-blue)"> {!! __('messages.guidelines_content_creation_game_cards') !!}</span>
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse show"
                                 aria-labelledby="headingTwo">
                                <div class="accordion-body">
                                    {!! __('messages.guidelines_game_cards') !!}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                    <span class="title-number">3</span> <span style="font-size: 22px; font-family: 'Open Sans',sans-serif; color:var(--content-light-blue)">  {!! __('messages.guidelines_uploading_content') !!}</span>
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse show"
                                 aria-labelledby="headingThree">
                                <div class="accordion-body">
                                    {!! __('messages.guidelines_image_sound_rules') !!}
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
