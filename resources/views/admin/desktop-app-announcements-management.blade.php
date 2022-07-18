@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/desktop-app-announcements-management-page.css') }}">
@endpush
@push('modals')

    <div class="modal fade" id="createNewDesktopAppAnnouncementModal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <form id="createAnnouncmentForm" method="POST"
                      action="{{ route('administration.desktop_app_announcements.store') }}">
                    @method('POST')
                    @include('admin.desktop-app-announcement-form-contents', ['action' => 'create'])
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div><!--.modal-content-->
        </div><!--.modal-dialog-->
    </div><!--.modal-->

    <div class="modal fade" id="updateDesktopAppAnnouncementModal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <form id="updateAnnouncementForm" method="POST"
                      action="{{ route('administration.desktop_app_announcements.update', 0) }}">
                    @method('PUT')
                    @include('admin.desktop-app-announcement-form-contents', ['action' => 'update'])
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div><!--.modal-content-->
        </div><!--.modal-dialog-->
    </div><!--.modal-->


    <div class="modal fade" id="dropDesktopAppAnnouncementModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="margin-top: 10%">
            <div class="modal-content">
                <form id="deleteAnnouncementForm" method="POST"
                      action="{{ route('administration.desktop_app_announcements.destroy', 0) }}">
                    @method('DELETE')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Are you sure you would like to delete this announcement?<br><span
                                id="announcement-title"></span></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-danger"><b>Warning: </b>
                            <br>This announcement will be deactivated and deleted. </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div><!--.modal-content-->
        </div><!--.modal-dialog-->
    </div><!--.modal-->



    <div class="modal fade" id="activateDesktopAppAnnouncementModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="margin-top: 10%">
            <div class="modal-content">
                <form id="activateAnnouncementForm" method="POST"
                      action="{{ route('administration.desktop_app_announcements.activate', 0) }}">
                    @method('PUT')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Are you sure you would like to activate this announcement?<br><span
                                id="announcement-title"></span></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-danger"><b>Warning: </b>
                            <br>All previous activate announcements will automatically be deactivated </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Activate</button>
                    </div>
                </form>
            </div><!--.modal-content-->
        </div><!--.modal-dialog-->
    </div><!--.modal-->


    <div class="modal fade" id="deactivateDesktopAppAnnouncementModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="margin-top: 10%">
            <div class="modal-content">
                <form id="deactivateAnnouncementForm" method="POST"
                      action="{{ route('administration.desktop_app_announcements.deactivate', 0) }}">
                    @method('PUT')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Are you sure you would like to deactivate this announcement?<br><span
                                id="announcement-title"></span></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-danger"><b>Warning: </b>
                            <br>This announcement will be deactivated </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Deactivate</button>
                    </div>
                </form>
            </div><!--.modal-content-->
        </div><!--.modal-dialog-->
    </div><!--.modal-->

@endpush
@section('content')

    <div class="container py-5" id="desktop-app-announcements-page">
        <!-- most popular tag section -->
        <div class="row py-2">
            <div class="col text-left">
                <h4>Manage Desktop App Announcements</h4>
            </div>
        </div>
        <div class="row py-5">
            <div class="col text-left">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#createNewDesktopAppAnnouncementModal"
                >Create new announcement
                </button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <table class="table table-hover table-striped" style="text-align: left;">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Severity</th>
                        <th>Created</th>
                        <th class="text-center">Operation</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($announcements as $index => $announcement)
                        <tr class="announcement">
                            <td class="align-middle">
                                {{ $index + 1 }}
                            </td>
                            <td><p class="align-left">
                                <p>{{ $announcement->default_title }}</p>
                            </td>
                            <td><p class="align-center">
                                <p>{{ $announcement->severity }}</p>
                            </td>
                            <td><p class="align-middle">
                                <p>{{ $announcement->created_at }}</p>
                            </td>
                            <td class="text-center align-middle">
                                <button
                                    data-announcement-id="{{ $announcement->id }}"
                                    class="btn btn-primary update-announcement">Update
                                </button>
                                <button data-announcement-id="{{ $announcement->id }}"
                                        data-announcement-title="{{ $announcement->default_title }}"
                                        class="btn btn-danger drop-announcement">Delete
                                </button>
                                @if($announcement->status == 1)
                                    <button data-announcement-id="{{ $announcement->id }}"
                                            data-announcement-title="{{ $announcement->default_title }}"
                                            class="btn  btn-outline-danger btn-status deactivate-announcement "> Deactivate
                                    </button>
                                @else
                                    <button data-announcement-id="{{ $announcement->id }}"
                                            data-announcement-title="{{ $announcement->default_title }}"
                                            class="btn btn-outline-success btn-status activate-announcement"> Activate
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script>
        window.announcements = @json($announcements);
        window.languages = @json($languages);
    </script>
    <script src="{{ mix('dist/js/desktop-app-announcements-management.js') }}"></script>
@endpush
