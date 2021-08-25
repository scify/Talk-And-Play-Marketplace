@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/communication-cards-create-edit.css') }}">
@endpush

@section('content')
    <div class="container">
        @include('resources_packages.resources-packages-list', ['routePrefix' => 'game_resources'])
    </div>
@endsection
@push('scripts')
    <script src="{{ mix('dist/js/create-edit-resource.js') }}"></script>
@endpush
