@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/home.css') }}">
@endpush

@section('content')

    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col">
                <p class="description mt-4">
                    {!! __('messages.talk_and_play_description') !!}
                </p>
                {!! __('messages.watch_tutorials') !!}
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-6">
                <h3>Talk & Play - Marketplace</h3>
                <hr>
                <iframe width="100%" onload="this.height=screen.height / 3;" src="https://www.youtube.com/embed/lcCb6r2XBDk"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    Talk & Play Marketplace Demo
                </iframe>
            </div>
            <div class="col-6">
                <h3>Talk & Play - Desktop</h3>
                <hr>
                <iframe width="100%" onload="this.height=screen.height / 3;" src="https://www.youtube.com/embed/EYCNIRM586s"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    Personalize Talk & Play
                </iframe>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ mix('dist/js/home.js') }}"></script>
@endpush
