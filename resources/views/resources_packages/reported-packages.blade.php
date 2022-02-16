@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/resources-packages-index.css') }}">
@endpush

@section('content')
    <div class="container rounded py-5" style="border:1px solid grey;margin:auto;width:60%">
        <div class="row my-4">
            <div class="col">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col text-center">
                            <h2>{{trans("messages.communication_cards")}}</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div>
                                <resources-packages-with-filters
                                    :resources-packages-types='@json(["1"])'
                                    :resources-packages-route="'{{ route('communication_resources.get') }}'"
                                    :user='@json($user)'
                                    :packages-type="'COMMUNICATION'"
                                    :is-admin="'{{$viewModel->isAdmin}}'"
                                    :approve-packages="{{true}}"
                                    :reports-route="'{{ route('resources.user-reports.get') }}'"
                                >
                                </resources-packages-with-filters>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-5">
            <div class="col">
                <hr style="height:12px;border-width:0;color:gray;background-color:red">
            </div>
        </div>
        <div class="row my-4">
            <div class="col">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col text-center">
                            <h2>{{trans("messages.game_cards")}}</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <resources-packages-with-filters
                                :resources-packages-types='@json($viewModel->resourceTypesLkp)'
                                :resources-packages-route="'{{ route('game_resources.get') }}'"
                                :user='@json($user)'
                                :packages-type="'GAME'"
                                :is-admin="'{{$viewModel->isAdmin}}'"
                                :approve-packages="{{true}}"
                                :reports-route="'{{ route('resources.user-reports.get') }}'"
                            >
                            </resources-packages-with-filters>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
         aria-labelledby="deleteConfirmationModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-center" id='deleteModalHeader'>
                    <h5 class="modal-title w-100" id="deleteModalLabel">{{trans("messages.delete_card")}} </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span style="color:#ff0000">{{trans("messages.warning_deletion")}}</span>
                </div>
                <div class="d-flex justify-content-end">
                    <!--<input class="btn btn-outline-primary" type="reset" value="Ακύρωση">-->
                    <form id="md-delete-form" enctype="multipart/form-data" role="form" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <input class="btn btn-primary ms-4" type="submit" id="deletionConfirmed"
                               value="{{trans('messages.reject_card')}}">
                        &nbsp;
                        &nbsp
                        <a class="btn btn-outline-primary" data-bs-dismiss="modal">
                            {{trans('messages.delete')}}
                        </a>
                    </form>

                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ mix('dist/js/create-edit-resource.js') }}"></script>
@endpush
