@extends('layouts.app')

@section('content')
    <div class="container py-5" id="user-management-page">
        <!-- most popular tag section -->
        <div class="row mb-3">
            <div class="col text-left">
                <h1>Platform Statistics</h1>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col">
                <h2 class="mb-3">General Statistics</h2>
                <ul class="list-group">
                    @foreach($viewModel->generalPlatformStatistics[0] as $name => $stat)
                    <li class="list-group-item">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-4 col-md-5 col-sm-6">
                                    {{ $name }}:
                                </div>
                                <div class="col-lg-8 col-md-7 col-sm-6">
                                    <span class="badge bg-primary">{{ $stat }}</span>
                                </div>
                            </div>
                        </div>

                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col">
                <h2 class="mb-3">Statistics Per Card Package Category</h2>
                <ul class="list-group">
                    @foreach($viewModel->resourcePackagesPerTypeStatistics as $resourcePackagesTypeStats)
                        <li class="list-group-item">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-4 col-md-5 col-sm-6">
                                        {{ $resourcePackagesTypeStats->name }}:
                                    </div>
                                    <div class="col-lg-8 col-md-7 col-sm-6">
                                        <span class="badge bg-primary">{{ $resourcePackagesTypeStats->num }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col">
                <h2 class="mb-3">Top Card Creators</h2>
                @foreach($viewModel->resourcesPerUserStatistics as $resourcesPerUserStats)
                    <li class="list-group-item">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-4 col-md-5 col-sm-6">
                                    {{ $resourcesPerUserStats->user_name }}:
                                </div>
                                <div class="col-lg-8 col-md-7 col-sm-6">
                                    <span class="badge bg-primary">{{ $resourcesPerUserStats->resources_num }}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </div>
        </div>
        <div class="row mb-5">
            <div class="col">
                <h2 class="mb-3">Top Card Packages Creators</h2>
                @foreach($viewModel->resourcesPackagesPerUserStatistics as $resourcesPackagesPerUserStats)
                    <li class="list-group-item">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-4 col-md-5 col-sm-6">
                                    {{ $resourcesPackagesPerUserStats->user_name }}:
                                </div>
                                <div class="col-lg-8 col-md-7 col-sm-6">
                                    <span class="badge bg-primary">{{ $resourcesPackagesPerUserStats->resources_packages_num }}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </div>
        </div>
    </div>
@endsection
