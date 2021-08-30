@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/communication-cards-create-edit.css') }}">
@endpush

@section('content')
    <div class="row mt-5">
        <div class="col">
            <resources-packages-with-filters
                :resources-packages-route="'{{ route('communication_resources.get') }}'"
                :user-id-to-get-content="{{ $user_id }}">
            </resources-packages-with-filters>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ mix('dist/js/create-edit-resource.js') }}"></script>
@endpush
