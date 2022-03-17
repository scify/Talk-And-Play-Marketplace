@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{mix('dist/css/terms-of-use.css')}}">
@endpush
@section('content')

    <div id="terms-of-use-page" class="container pt-4 mb-2" >
        <div class="mb-5">
            <div class="row">
                <div class="col-12 mx-auto mb-3 text-center">
                    <h1>{!! __('messages.terms-of-use') !!}</h1>
                </div>
            </div>
            <p>{!! __('messages.terms-of-use-prologue') !!}</p>
            <div class="row">
                <div class="col mx-auto">
                    <div class="container-fluid">
                        <div class="row">
                                <ul>
                                    <li>{!! __('messages.term-of-use-1') !!}</li>
                                    <li>{!! __('messages.term-of-use-2') !!}</li>
                                    <li>{!! __('messages.term-of-use-3') !!}</li>
                                    <li>{!! __('messages.term-of-use-4') !!}</li>
                                    <li>{!! __('messages.term-of-use-5') !!}</li>
                                    <li>{!! __('messages.term-of-use-6') !!}</li>
                                    <li>{!! __('messages.term-of-use-7') !!}</li>
                                    <li>{!! __('messages.term-of-use-8') !!}</li>
                                    <li>{!! __('messages.term-of-use-9') !!}</li>
                                    <li>{!! __('messages.term-of-use-10') !!}</li>
                                    <li>{!! __('messages.term-of-use-11') !!}</li>
                                </ul>
                            <div class="col-9"></div>
                            <div class="col-3">
                                <p>{!! __('messages.last-amendment') !!}: 11/04/2022</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
