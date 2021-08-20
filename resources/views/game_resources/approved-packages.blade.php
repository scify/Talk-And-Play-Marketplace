@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/communication-cards-create-edit.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="row">
            @foreach($viewModel->parentResources as $resource)
                <div class="col-md-4 col-sm-12">
                    <div class="card w-100 mb-5">
                        <input type="hidden" value={{$resource->id}}>
                        <img src="{{asset("storage/".$resource->img_path)}}" class="card-img-top"
                             style="width:auto;height:200px;">
                        <div class="dropdown-container rounder" style="background-color: white;">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle actions-btn" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu" id="dropdown-menu">
                                    <li><a class="dropdown-item viewPackageBtn" href="{{route('game_resources.show_package',$resource->id)}}"><i
                                                class="far fa-edit me-2"></i>{{__("messages.open")}}</a></li>
                                    <li><a class="dropdown-item downloadPackagedBtn" href="{{route('game_resources.download_package',$resource->id)}}"><i
                                                class="fas fa-file-download me-2"></i>{{__("messages.download")}}</a>
                                    </li>
                                    {{--                                            TODO prevent scrolling cancel (event propagation?) --}}
                                </ul>
                            </div>
                        </div>
                        <div class="card-title">
                            <p> {{ $resource->name }} </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ mix('dist/js/create-edit-resource.js') }}"></script>
@endpush
