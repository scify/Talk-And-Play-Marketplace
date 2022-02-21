@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/communication-cards-create-edit.css') }}">
@endpush

@section('content')




    <form id="md-form" enctype="multipart/form-data" role="form" method="POST"
          action="{{ $viewModel->isEditMode() ? route('resources.update', $viewModel->resource->id) : route('resources.store',['type_id'=>$viewModel->type_id]) }}">
        <input type="hidden" id="type_id" value='{{$viewModel->type_id}}'/>
    @if($viewModel->isEditMode())
        @method('PUT')
    @endif
    <!--form class="md-form" action="{{route('resources.store',['type_id'=>$viewModel->type_id])}}" method="POST" enctype="multipart/form-data"-->
        {{ csrf_field() }}
        <div class="container rounded py-4" style="border:1px solid grey">
            <div class="mx-3">
                @if ($game === "SIMILAR")
                    <b>{{trans("messages.new_package").' '.trans("messages.find_similar_tagline")}}</b>
                @elseif ($game === "TIME")
                    <b>{{trans("messages.new_package").' '.trans("messages.find_time_tagline")}}</b>
                @elseif ($game === "RESPONSE")
                    <b>{{trans("messages.new_package").' '.trans("messages.find_response_tagline")}}</b>
                @else
                    <b>{{trans("messages.new_package").$viewModel->type_id}}</b>
                @endif
            </div>
            <hr/>
            <div class="container-sm px-5">
                <!-- Content here -->
                <div class="mb-3">
                    <label for="category_name" class="form-label">Όνομα <span style="color:#ff0000">*</span></label>
                    <input type="text" class="form-control" id="category_name"
                           name="name"
                           required
                           value="{{ old('name') ? old('name') : $viewModel->resource->name }}">
                </div>
                <div class="mb-3">
                    <label for="category_lang" class="form-label">{{trans("messages.language")}}</label>
                    <select class="form-select" @if($viewModel->package->lang_id != null) disabled
                            @endif aria-label="category_lang" name="lang">
                        @foreach ($viewModel->languages as $lang){
                        @if($viewModel->package->lang_id === $lang->id)
                            <option selected> {{$lang->name}} </option>
                        @else
                            <option value="{{$lang->id}}"> {{$lang->name}} </option>
                        @endif

                        @endforeach
                    </select>
                    <!--<input type="radio" class="form-control" id="category_lang"> -->
                </div>
                <div class="text-center form-new__prototype-file" style="font-style: italic;">
                    <em> {!!__('messages.content-guideline-terms')!!}  <a target="_blank" href="{{route('content-guidelines')}}"> here. </a></em>
                </div>
                <div class="mb-3">
                    <label for="upload_img" class="form-label"> {{trans("messages.upload_img")}} <span
                            style="color:#ff0000">*</span></label>
                    <div class="file-field px-5">
                        <a class="btn-floating float-left">
                            <!--<i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>-->
                            <input type="button" class="btn btn-third" id="loadFileXml" value="+"
                                   onclick="document.getElementById('upload_img').click();"/>
                            <input type="file" accept="image/*"
                                   class="btn btn-third @error('image') is-invalid @enderror" style="display:none;"
                                   name="image" id="upload_img">

                        </a>

                    </div>
                    @if($viewModel->isEditMode())
                        <img src={{asset("storage/".$viewModel->resource->img_path)}} id="url" class="mt-3"
                             height="200px"/>
                    @else
                        <img src={{asset('/audio/happiness.png')}} style="display:none" id="url"
                             class="mt-3"
                             height="200px"/>
                    @endif


                </div>
                @error('image')
                <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>

            <hr/>
            <div class="d-flex justify-content-end">
                <!--<input class="btn btn-outline-primary" type="reset" value="Ακύρωση">-->
                <a class="btn btn-outline-primary" href="{{route('game_resources.index')}}">
                    {{trans("messages.cancel")}}
                </a>

                <input id="packageSubmitBtn" class="btn btn-primary ms-4" type="submit" value={{trans("messages.save_card")}}>
            </div>
        </div>

    </form>

    @if($viewModel->isEditMode())



        <div class="mt-5 mb-5" align="center">


            <button type="button" id="newCardBtn" class="btn btn-primary mt-5 btn-block
            @if($viewModel->ReachedMaximumCardLimit()) disabled
            @endif
            {{--                    data-bs-toggle="modal"--}}
            {{--                    data-bs-target="#newCardModal"--}}
                ">
                {{trans("messages.add_new_card")}}
            </button>
            @if($viewModel->ReachedMaximumCardLimit())
                <div class="alert alert-danger">{{trans("messages.reached_card_limit")}}</div>
            @endif

            @if(sizeof($viewModel->childrenCards)>0)
                <button type="button" id="saveBundleBtn" class="btn btn-primary mt-5 btn-block"
                    {{--                    data-bs-toggle="modal"--}}
                    {{--                    data-bs-target="#newCardModal"--}}
                >
                    {{trans("messages.submit_package")}}
                </button>
            @endif
        </div>

        @if(sizeof($viewModel->childrenCards)>0)
            <div class="container">
                <div class="row">
                    @foreach($viewModel->childrenCards as $i=>$child)
                        <div class="col-md-4 col-sm-12">
                            <div class="card w-100 mb-5">
                                <input type="hidden" value={{$child->id}}>
                                <img src="{{asset("storage/".$child->img_path)}}" class="card-img-top"
                                     style="width:auto;height:270px;margin: 0 auto;">
                                <div class="dropdown-container rounder" style="background-color: white;">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle actions-btn"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu" id="dropdown-menu">
                                            <li><a class="dropdown-item editCardBtn" href="#"><i
                                                        class="far fa-edit me-2"></i>{{trans("messages.edit")}}</a></li>
                                            <li><a class="dropdown-item deleteCardBtn" href="#"><i
                                                        class="fas fa-file-download me-2"></i>{{trans("messages.delete")}}
                                                </a></li>
                                            {{--                                            TODO prevent scrolling cancel (event propagation?) --}}
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-title" style="margin-bottom: 0;">
                                    <p> {{ $child->name }} </p>
                                    <button type="button" class="btn btn-success">{{$i+1}}</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        @endif

    @endif


@endsection
@push('modals')

    <!-- Modal -->
    <div class="modal fade" id="newCardModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-center" id='modalHeader'>
                    <h5 class="modal-title w-100" id="ModalLabel">{{trans("messages.add_new_card")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <form id="md-modal-form" enctype="multipart/form-data" role="form" method="POST"
                          action="{{route('resources.store',['type_id'=>$viewModel->type_id])}}">
                        {{ csrf_field() }}


                        <div class="container-sm px-5">

                            <input type="hidden" name="parentId" id="parentId" value='{{$viewModel->resource->id}}'/>
                            <input type="hidden" name="cardId" id="cardId" value=''/>
                            <!-- Content here -->
                            <div class="mb-3">
                                <label for="category_name" class="form-label">{{trans("messages.name")}} <span
                                        style="color:#ff0000">*</span></label>
                                <input type="text" class="form-control" id="modal_category_name"
                                       name="name"
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="category_lang" class="form-label">Γλώσσα</label>
                                <select class="form-select" disabled aria-label="category_lang" name="lang">
                                    @foreach ($viewModel->languages as $lang){
                                    @if($viewModel->package->lang_id === $lang->id)
                                        <option selected> {{$lang->name}} </option>
                                    @else
                                        <option value="{{$lang->id}}"> {{$lang->name}} </option>
                                    @endif
                                    @endforeach
                                </select>
                                <!--<input type="radio" class="form-control" id="category_lang"> -->
                            </div>
                            <div class="text-center form-new__prototype-file" style="font-style: italic;">
                                <em> {!!__('messages.content-guideline-terms')!!}</em>
                            </div>
                            <div class="mb-3">
                                <label for="modal_upload_img" class="form-label">{{trans("messages.upload_img")}} <span
                                        style="color:#ff0000">*</span></label>
                                <div class="file-field px-5">
                                    <a class="btn-floating float-left">
                                        <!--<i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>-->
                                        <input type="button" class="btn btn-third" id="ModalLoadFileXml" value="+"
                                               onclick="document.getElementById('modal_upload_img').click();"/>
                                        <input type="file" accept="image/*"
                                               class="btn btn-third @error('image') is-invalid @enderror"
                                               style="display:none;"
                                               name="image" id="modal_upload_img">
                                    </a>
                                </div>
                                <img src="" style="display:none"
                                     id="modal_url" class="mt-3"
                                     height="200px"/>

                            </div>
                            @error('image')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <!--<input class="btn btn-outline-primary" type="reset" value="Ακύρωση">-->
                            <a class="btn btn-outline-primary" data-bs-dismiss="modal">
                                {{trans("messages.cancel")}}
                            </a>
                            <input class="btn btn-primary ms-4" type="submit" id="submitModal"
                                   value="{{trans('messages.save_card')}}">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
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
                               value="{{trans('messages.delete_card')}}">
                        &nbsp;
                        &nbsp;
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


    <div class="modal fade" id="saveBundleModal" tabindex="-1" role="dialog" aria-labelledby="saveBundleModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-center" id='saveBundleModalHeader'>
                    <h5 class="modal-title w-100" id="saveBundleModalLabel">{{trans('messages.submit_package')}} </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span style="color:#ff0000">{{trans('messages.warning_submission')}}</span>
                    {{trans('messages.info_submission')}}
                </div>

                <div class="modal-footer">
                    <div class="d-flex justify-content-end">
                        <form id="md-save-bundle-form" enctype="multipart/form-data" role="form" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                            <a class="btn btn-outline-primary" data-bs-dismiss="modal">
                                {{trans('messages.cancel')}}
                            </a>
                            <input type="hidden" name="packageId" id="packageId" value='{{$viewModel->package['id']}}'/>
                            <input class="btn btn-primary ms-4" type="submit" id="saveBundleConfirmed"
                                   value="{{trans('messages.submit')}}">

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('scripts')
    <script src="{{ mix('dist/js/create-edit-resource.js') }}"></script>
@endpush
