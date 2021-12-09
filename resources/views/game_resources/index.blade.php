@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/resources-packages-index.css') }}">
@endpush
@section('content')
    <section id="intro" class="pt-5 mt-5">
        <div class="container">
            <div class="row mb-3">
                <div class="col text-center">
                    <h1>{!! __('messages.game_cards') !!}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <p class="mb-1">{!! __('messages.communication_cards_page_intro') !!}</p>
                    <a class="link-info" href="#">{!! __('messages.communication_cards_page_intro_link') !!}</a>
                </div>
            </div>
        </div>
    </section>
    <section id="resources-steps" class="pt-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <span class="title-number">1</span>   {{__('messages.game_cards_tutorial_step_1_title')}}
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo">
                                <div class="accordion-body">
                                    {{__('messages.game_cards_tutorial_step_1_description')}}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                    <span class="title-number">2</span> {{__('messages.communication_cards_tutorial_step_2_title')}}
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse show"
                                 aria-labelledby="headingThree">
                                <div class="accordion-body">
                                    {!! __('messages.communication_cards_tutorial_step_2_description')!!}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFour" aria-expanded="false"
                                        aria-controls="collapseFour">
                                    <span class="title-number">3</span> {{__('messages.game_cards_tutorial_step_3_title')}}
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse show"
                                 aria-labelledby="headingFour">
                                <div class="accordion-body">
                                    {!! __('messages.game_cards_tutorial_step_3_description')!!}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFive" aria-expanded="false"
                                        aria-controls="collapseFive">
                                    <span class="title-number">5</span>{{__('messages.game_cards_tutorial_step_4_title')}}
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse show"
                                 aria-labelledby="headingFive">
                                <div class="accordion-body">
                                    {!! __('messages.game_cards_tutorial_step_4_description')!!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="resources-content" class="mt-5">
        <div class="container">
            <div class="row mt-4">
                <div class="col">
                    <button
                        data-bs-toggle="modal" data-bs-target="#createGamePackageModal"
                        class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i>
                        {{ __('messages.create_new_game_package') }}
                    </button>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col text-center">
                    <h5 class="hint mb-1">{{ __('messages.see_all_cards_title') }}</h5>
                    <i class="hint hint-arrow fas fa-arrow-down"></i>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <resources-packages-with-filters
                        :resources-packages-types='@json($viewModel->resourceTypesLkp)'
                        :resources-packages-route="'{{ route('game_resources.get') }}'"
                        :user='@json($user)'
                        :packages-type="'GAME'"
                        :resources-packages-statuses='@json($viewModel->resourcesPackagesStatuses)'
                        :is-admin="'{{$viewModel->isAdmin}}'"
                        :approve-packages="{{0}}">
                    </resources-packages-with-filters>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="createGamePackageModal" tabindex="-1" aria-labelledby="createGamePackageModal"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createGamePackageModalLabel">
                        {{ __('messages.create_new_game_package_modal_title') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container pt-3 pb-5">
                        <div class="row mb-4">
                            <h5>{{ __('messages.create_new_game_package_modal_body_text') }}</h5>
                        </div>
                        <div class="row">
                            @foreach($viewModel->resourceTypesLkp as $resourceTypeLkp)
                                <div class="col-md-4 col-sm-12">
                                    <a href="{{route('game_resources.create', ['type_id' => $resourceTypeLkp->id])}}"
                                       class="btn btn-primary w-100">{{ $resourceTypeLkp->name }}</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
