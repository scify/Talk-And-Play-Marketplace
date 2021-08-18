@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/communication-cards.css') }}">
@endpush
@section('content')
    <section id="game-cards-content" class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-xs-6">
                    <a href="{{route('game_resources.create',['type_id'=>$gameCategoriesLkp::SIMILAR])}}" class="btn btn-primary">{{trans("messages.find_similar_tagline")}}</a>
                </div>
                <div class="col-xs-6">
                    <a href="{{route('game_resources.create',['type_id'=>$gameCategoriesLkp::TIME])}}" class="btn btn-primary">{{trans("messages.find_time_tagline")}}</a>
                </div>
                <div class="col-xs-6">
                    <a href="{{route('game_resources.create',['type_id'=>$gameCategoriesLkp::RESPONSE])}}" class="btn btn-primary">{{trans("messages.find_response_tagline")}}</a>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
@endpush
