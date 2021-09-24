
@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/home.css') }}">
@endpush

@section('content')

    <div class="container h-100">
        <div class="row h-100 align-items-center">

            <div class="row align-items-center h-100">

                <p class="description mt-4">
                    {!! __('messages.talk_and_play_description') !!}
                </p>
                {!! __('messages.watch_tutorials') !!}
            </div>


            <div id="wrapper">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/lcCb6r2XBDk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                    Talk & Play Marketplace Demo
                </iframe>
                &nbsp;
                &nbsp;
                &nbsp;
                <iframe width="560" height="315" src="https://www.youtube.com/embed/EYCNIRM586s" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                    Personalize Talk & Play
                </iframe>
                <div class="clear"></div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ mix('dist/js/home.js') }}"></script>
@endpush
