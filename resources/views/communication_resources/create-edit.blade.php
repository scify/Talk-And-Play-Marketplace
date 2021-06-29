@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/communication-cards-create-edit.css') }}">
@endpush

@section('content')
    <form id="md-form" enctype="multipart/form-data" role="form" method="POST"
          action="{{ $viewModel->isEditMode() ? route('communication_resources.update', $viewModel->resource->id) : route('communication_resources.store') }}">
    @if($viewModel->isEditMode())
        @method('PUT')
    @endif

    <!--form class="md-form" action="{{route('communication_resources.store')}}" method="POST" enctype="multipart/form-data"-->
        {{ csrf_field() }}
        <div class="container rounded py-4" style="border:1px solid grey">
            <div class="mx-3">
                <b>Νέο Πακέτο</b>
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
                    <label for="category_lang" class="form-label">Γλώσσα</label>
                    <select class="form-select" aria-label="category_lang" name="lang">
                        @foreach ($viewModel->languages as $lang){
                        <option value="{{$lang->id}}"> {{$lang->name}} </option>
                        @endforeach
                    </select>
                    <!--<input type="radio" class="form-control" id="category_lang"> -->
                </div>
                <div class="mb-3">
                    <label for="upload_img" class="form-label">Ανέβασε εικόνα <span
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
                        <img src={{asset('storage/resources/img/happiness.png')}} style="display:none" id="url" class="mt-3"
                             height="200px"/>
                    @endif


                </div>
                @error('image')
                <div class="alert alert-danger">{{$message}}</div>
                @enderror
                <div class="mb-3">
                    <label for="sound_file" class="form-label">Ανέβασε επεξηγηματικό αρχείο ήχου (mp3) <span
                            style="color:#ff0000">*</span></label>
                    <div class="file-field px-5">
                        <a class="btn-floating purple-gradient mt-0 float-left">
                            <input type="button" class="btn btn-third" id="loadFileXml" value="+"
                                   onclick="document.getElementById('sound_file').click();"/>
                            <input type="file" accept=".mp3" class="btn btn-third  @error('sound') is-invalid @enderror"
                                   style="display:none;"
                                   name="sound" id="sound_file">
                        </a>
                    </div>
                    @if($viewModel->isEditMode())
                        <audio id="player" controls class="mt-3">
                            <source src={{asset("storage/".$viewModel->resource->audio_path)}} id="mp3_src"
                                    type="audio/mpeg">
                        </audio>
                    @else
                        <audio id="player" controls style="display:none" class="mt-3">
                            <source src={{asset('storage/resources/audio/happiness.mp3')}} id="mp3_src"
                                    type="audio/mpeg">
                        </audio>
                    @endif
                </div>
                @error('sound')
                <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>

            <hr/>
            <div class="d-flex justify-content-end">
                <!--<input class="btn btn-outline-primary" type="reset" value="Ακύρωση">-->
                <a class="btn btn-outline-primary" href="{{route('communication_resources.index')}}">
                    Ακύρωση
                </a>

                <input class="btn btn-primary ms-4" type="submit" value="Αποθήκευση Κάρτας">
            </div>
        </div>

    </form>
    <!--input type="hidden" name="communicationCardId" id="communicationCardId" value={{$viewModel->resource->id}}-->
    @if($viewModel->isEditMode())
        <div class="mt-5" align="center">
            <button type="button"  id="newCardId" class="btn btn-primary mt-5 btn-block" data-bs-toggle="modal" data-bs-target="#newCardModal">
                Προσθήκη Νέας Κάρτας
            </button>
        </div>
    @endif


    @push('modals')

    <!-- Modal -->
    <div class="modal fade" id="newCardModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title w-100" id="ModalLabel">Προσθήκη Νέας Κάρτας</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">



                    <form id="md-modal-form" enctype="multipart/form-data" role="form" method="POST"
                          action="{{route('communication_resources.store')}}">
                        {{ csrf_field() }}


                        <div class="container-sm px-5">

                            <input type="text" name="parentId" id="parentId" value='{{$viewModel->resource->id}}'/>

                            <!-- Content here -->
                            <div class="mb-3">
                                <label for="category_name" class="form-label">Όνομα <span
                                        style="color:#ff0000">*</span></label>
                                <input type="text" class="form-control" id="modal_category_name"
                                       name="name"
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="category_lang" class="form-label">Γλώσσα</label>
                                <select class="form-select" aria-label="category_lang" name="lang">
                                    @foreach ($viewModel->languages as $lang){
                                    <option value="{{$lang->id}}"> {{$lang->name}} </option>
                                    @endforeach
                                </select>
                                <!--<input type="radio" class="form-control" id="category_lang"> -->
                            </div>
                            <div class="mb-3">
                                <label for="modal_upload_img" class="form-label">Ανέβασε εικόνα <span
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
                                <img src={{asset('storage/resources/img/happiness.png')}} style="display:none"
                                     id="modal_url" class="mt-3"
                                     height="200px"/>

                            </div>
                            @error('image')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                            <div class="mb-3">
                                <label for="modal_sound_file" class="form-label">Ανέβασε επεξηγηματικό αρχείο ήχου (mp3)
                                    <span
                                        style="color:#ff0000">*</span></label>
                                <div class="file-field px-5">
                                    <a class="btn-floating purple-gradient mt-0 float-left">
                                        <input type="button" class="btn btn-third" id="ModalLoadFileXml" value="+"
                                               onclick="document.getElementById('modal_sound_file').click();"/>
                                        <input type="file" accept=".mp3"
                                               class="btn btn-third  @error('sound') is-invalid @enderror"
                                               style="display:none;"
                                               name="sound" id="modal_sound_file">
                                    </a>
                                </div>
                                <audio id="modal_player" controls style="display:none" class="mt-3">
                                    <source src={{asset('storage/resources/audio/happiness.mp3')}} id="modal_mp3_src"
                                            type="audio/mpeg">
                                </audio>
                            </div>
                            @error('sound')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <!--<input class="btn btn-outline-primary" type="reset" value="Ακύρωση">-->
                            <a class="btn btn-outline-primary" data-bs-dismiss="modal">
                                Ακύρωση
                            </a>
                            <input class="btn btn-primary ms-4" type="submit"  id="submitModal" value="Αποθήκευση Κάρτας">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    @endpush

@endsection
@push('scripts')
    <script src="{{ mix('dist/js/create-edit-resource.js') }}"></script>
@endpush
