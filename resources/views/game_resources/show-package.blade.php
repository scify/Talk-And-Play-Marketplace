@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/communication-cards-create-edit.css') }}">
@endpush

@section('content')

    {{ csrf_field() }}
    <div class="container rounded py-4" style="border:1px solid grey">
        <div class="mx-3">
            <b>Προβολή Πακέτου</b>
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
            <div class="mb-3">
                <label for="upload_img" class="form-label"> Εικόνα <span
                        style="color:#ff0000">*</span></label>
                <div class="file-field px-5">
                </div>
                @if($viewModel->isEditMode())
                    <img src={{asset("storage/".$viewModel->resource->img_path)}} id="url" class="mt-3"
                         height="200px"/>
                @else
                    <img src={{asset('storage/resources/img/happiness.png')}} style="display:none" id="url"
                         class="mt-3"
                         height="200px"/>
                @endif


            </div>
            @error('image')
            <div class="alert alert-danger">{{$message}}</div>
            @enderror

        <hr/>
    </div>


    @if(sizeof($viewModel->childrenCards)>0)
        <div class="container">
            <div class="row">
                @foreach($viewModel->childrenCards as $child)
                    <div class="col-md-4 col-sm-12 mb-3">
                        <div class="card w-100 mb-5">
                            <input type="hidden" value={{$child->id}}>
                            <img src="{{asset("storage/".$child->img_path)}}" class="card-img-top"
                                 style="width:auto;height:200px;">
                            <div class="card-title">
                                <p> {{ $child->name }} </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @endif


@endsection
@push('scripts')
    <script src="{{ mix('dist/js/create-edit-resource.js') }}"></script>
@endpush
