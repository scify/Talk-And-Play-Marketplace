@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/home.css') }}">
@endpush

@section('content')

    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-md-12">
                <p class="description mt-4">
                    {!! __('messages.talk_and_play_description') !!}
                </p>
            </div>
        </div>

        <div class="row align-items-top" >
            <div class="col-md-4" style="text-align: center">
                <h4>Video Tutorials</h4>
                <p>
                    {!! __('messages.watch_tutorials') !!}
                </p>
                <div class="row">
                    <iframe width="300" height="200" src="https://www.youtube.com/embed/ruTuW06JWVI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="row" style="margin-top:50pt">
                    <iframe width="300" height="200" src="https://www.youtube.com/embed/EYCNIRM586s" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>                </div>

            </div>

            <div class="col-md-2"></div>

            <div class="col-md-2">
                <h4 style="text-align: center">Funding</h4>
                <div class="row align-items-center">


                    <p style="font-size:10pt;">
                        This project has received funding from the European Union's Horizon 2020 research and innovation programme under grant agreement No. 857159.
                    </p>
                    <div class="col-6">
                        <img src={{asset("img/shapes_logo.png")}} height="50px" alt="shapes logo" style="margin-left:auto;margin-right:auto;display:block">
                    </div>
                    <div class="col-6">
                        <img src={{asset("img/eu_logo.jpg")}}  alt="EU-logo"  style="width:auto; height:40px; margin-left:auto;margin-right:auto;display:block" alt="">
                    </div>
                </div>
            </div>

            <div class="col-md-2"></div>

            <div class="col-md-2" style="text-align: center">
                <h4 style="text-align: center">Learn More</h4>
                <ul>
                    <li style="font-size: 12pt!important;"><a href="{{route('content-guidelines')}}"> {{__('messages.content-guidelines')}} </a></li>
                    <li style="font-size: 12pt!important;"><a href="{{app()->getLocale() .'/privacy-policy'}}"  target="_blank">{!! __('messages.privacy-policy') !!}</a></li>
                    <li style="font-size: 12pt!important;"><a href="{{'/terms-of-use' }}">{!! __('messages.terms-of-use') !!}</a></li>
                </ul>
            </div>
        </div>

{{--        <div class="row align-items-center"  style="margin-top:50pt">--}}

{{--            <div class="col-md-9"></div>--}}

{{--            <div class="col-md-3" style="text-align: center">--}}

{{--                <div class="row align-items-center mb-5">--}}
{{--                    <h4> Funding </h4>--}}


{{--                    <p style="font-size:10pt;">--}}
{{--                        This project has received funding from the European Union's Horizon 2020 research and innovation programme under grant agreement No. 857159.--}}
{{--                    </p>--}}
{{--                    <div class="col-6">--}}
{{--                        <img src={{asset("img/shapes_logo.png")}} height="50px" alt="shapes logo" style="margin-left:auto;margin-right:auto;display:block">--}}
{{--                    </div>--}}
{{--                    <div class="col-6">--}}
{{--                        <img src={{asset("img/eu_logo.jpg")}}  alt="EU-logo"  style="width:auto; height:40px; margin-left:auto;margin-right:auto;display:block" alt="">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>


@endsection
