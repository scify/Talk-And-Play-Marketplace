@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/communication-cards-create-edit.css') }}">
@endpush

@section('content')
    <div class="container rounded py-4" align="center" style="border:1px solid grey;margin:auto;width:60%">
        <div class="mx-3">
            <b>{{trans("messages.communication_cards")}}</b>
        </div>
        <div class="row mt-5">
            <resources-packages-with-filters
                :resources-packages-route="'{{ route('communication_resources.get') }}'"
                :user="{{ $user }}"
                :user-id-to-get-content="{{$viewModel->user_id_to_get_content  }}"
                :packages-type="'COMMUNICATION'">
            </resources-packages-with-filters>
        </div>
        <hr>
        <div class="mx-3">
            <b>{{trans("messages.game_cards")}}</b>
        </div>
        <div class="row mt-5">
            <div class="col">
                <resources-packages-with-filters
                    :resources-packages-types='@json($viewModel->resourceTypesLkp)'
                    :resources-packages-route="'{{ route('game_resources.get') }}'"
                    :user="{{ $user }}"
                    :user-id-to-get-content="{{$viewModel->user_id_to_get_content  }}"
                    :packages-type="'GAME'">
                </resources-packages-with-filters>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ mix('dist/js/create-edit-resource.js') }}"></script>
@endpush
