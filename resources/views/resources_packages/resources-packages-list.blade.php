<div class="row">
    @foreach($viewModel->resourcesPackages as $resourcesPackage)
        <div class="col-md-4 col-sm-12">
            <div class="card w-100 mb-5">
                <input type="hidden" value={{$resourcesPackage->id}}>
                <img src="{{asset("storage/".$resourcesPackage->coverResource->img_path)}}"
                     class="card-img-top"
                     style="width:auto;height:200px;">
                <div class="dropdown-container rounder" style="background-color: white;">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle actions-btn" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu" id="dropdown-menu">
                            <li><a class="dropdown-item viewPackageBtn"
                                   href="{{route($routePrefix . '.show_package',$resourcesPackage->id)}}"><i
                                        class="far fa-edit me-2"></i>{{__("messages.open")}}</a></li>
                            <li><a class="dropdown-item downloadPackagedBtn"
                                   href="{{route($routePrefix . '.download_package',$resourcesPackage->id)}}"><i
                                        class="fas fa-file-download me-2"></i>{{__("messages.download")}}</a>
                            </li>
                            {{--                                            TODO prevent scrolling cancel (event propagation?) --}}
                        </ul>
                    </div>
                </div>
                <div class="card-title">
                    <p> {{ $resourcesPackage->coverResource->name }} </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
