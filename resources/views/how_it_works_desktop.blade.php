@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/home.css') }}">
@endpush

@section('content')

    <div class="container">
        <div class="row align-items-center my-5">
            <div class="col-md-12">
                <h1>{!! __('messages.how_it_works') !!} - Desktop app</h1>
            </div>
            <div class="col-lg-8 col-md-10">
                <h6 class="description mt-4">
                    {!! __('messages.talk_and_play_description') !!}
                </h6>
            </div>
        </div>
        <div class="row my-5">
            <div class="col">
                <a class="btn btn-lg btn-primary" href="https://docs.google.com/document/d/11nGgpyyTvYeUpiUGW2PW9w5tdtqkAjxI4I2tt0YwvL0/edit"
                   target="_blank">{!! __('messages.installation_instructions_linux_btn') !!}<i class="fas fa-external-link-alt ms-2"></i></a>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-12 mb-4">
                <h2>Video Tutorials</h2>
            </div>
            <div class="col-12">
                <h4>
                    {!! __('messages.watch_tutorials_desktop') !!}
                </h4>
            </div>
        </div>
        <div class="row my-5">
            <div class="col-md-6 col-sm-12 mx-auto">
                <h5 class="mb-3">{!! __('messages.desktop_tutorial_title_1') !!}</h5>
                <iframe width="600" height="400" src="{!! __('messages.desktop_tutorial_url_1') !!}"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
            <div class="col-md-6 col-sm-12 mx-auto">
                <h5 class="mb-3">{!! __('messages.desktop_tutorial_title_2') !!}</h5>
                <iframe width="600" height="400" src="{!! __('messages.desktop_tutorial_url_2') !!}"
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
