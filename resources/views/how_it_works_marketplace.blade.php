@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/home.css') }}">
@endpush

@section('content')

    <div class="container">
        <div class="row align-items-center my-5">
            <div class="col-md-12">
                <h1>{!! __('messages.how_it_works') !!} - Marketplace</h1>
            </div>
            <div class="col-lg-8 col-md-10">
                <h6 class="description mt-4">
                    {!! __('messages.talk_and_play_description') !!}
                </h6>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-12 mb-4">
                <h2>Video Tutorials</h2>
            </div>
            <div class="col-12">
                <h4>
                    {!! __('messages.watch_tutorials') !!}
                </h4>
            </div>
        </div>
        <div class="row my-5">
            <div class="col-6 mx-auto">
                <iframe width="700" height="500" src="{!! __('messages.marketplace_tutorial_url') !!}"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
        </div>
        <div class="row my-5">
            <div class="col-lg-5 col-md-6 col-sm-11 mx-auto">
                @include('common.funding')
            </div>
        </div>
    </div>

@endsection
