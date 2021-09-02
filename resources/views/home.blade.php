
@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/home.css') }}">
@endpush

@section('content')
    <section id="intro-carousel-row">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col h-100">
                    <div class="main-carousel h-100" id="landing-page-intro-carousel">
                        <div class="carousel-cell">
                            <div class="container h-100">
                                <div class="row align-items-center h-100">
                                    <div class="col-6">
                                        <h1>Talk & Play marketplace</h1>
                                        <p class="description mt-4">
                                            {!! __('messages.home_intro_title') !!}
                                        </p>
                                        <p class="mt-4 see-more fw-bold">
                                            {!! __('messages.home_intro_read_more') !!} <a href="#">Talk & Play app</a>
                                        </p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <img
                                            loading="lazy"
                                            src="{{ asset('img/home/' . \Illuminate\Support\Facades\App::getLocale() . '/marketplace_intro.png') }}"
                                            class="w-100" alt="Marketplace intro">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-cell">
                            <div class="container h-100">
                                <div class="row align-items-center h-100">
                                    <div class="col-6">
                                        <h1>Talk & Play app</h1>
                                        <p class="description mt-4">
                                            {!! __('messages.home_app_intro_description') !!}
                                        </p>
                                        <a href="#" class="mt-4 btn btn-primary">
                                            {!! __('messages.download_the_app') !!}
                                        </a>
                                    </div>
                                    <div class="col-6 text-end">
                                        <img
                                            loading="lazy"
                                            src="{{ asset('img/home/' . \Illuminate\Support\Facades\App::getLocale() . '/app_intro.png') }}"
                                            class="w-100" alt="Communication cards">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3 mb-5">
                <div class="col text-center">
                    <p class="m-0"><b>{!! __('messages.see_the') !!} <a
                                href="#communication-cards">{!! __('messages.card_categories') !!}</a></b></p>
                </div>
            </div>
        </div>
    </section>
    <section id="communication-cards" class="mt-5">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h1>{!! __('messages.communication_cards_title') !!}</h1>
                                <p class="description mt-4">
                                    {!! __('messages.communication_cards_description') !!}
                                </p>
                                <a class="mt-4 btn btn-secondary"
                                   href="{{route('communication_resources.index')}}">{!! __('messages.see_the_communication_cards') !!}</a>
                            </div>
                            <div class="col-6 text-end">
                                <img
                                    loading="lazy"
                                    src="{{ asset('img/home/' . \Illuminate\Support\Facades\App::getLocale() . '/communication_cards.png') }}"
                                    class="w-100" alt="Communication cards">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="game-cards">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-6 text-end">
                                <img
                                    loading="lazy"
                                    src="{{ asset('img/home/' . \Illuminate\Support\Facades\App::getLocale() . '/game_cards.png') }}"
                                    class="w-100" alt="Game cards">
                            </div>
                            <div class="col-6">
                                <h1>{!! __('messages.game_cards_title') !!}</h1>
                                <p class="description mt-4">
                                    {!! __('messages.game_cards_description') !!}
                                </p>
                                <a class="mt-4 btn btn-third" href="{{route('game_resources.index')}}">{!! __('messages.see_the_game_cards') !!}</a>
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
