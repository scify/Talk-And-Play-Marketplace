@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/user-management-page.css') }}">
@endpush

@section('content')
    <div class="container" id="user-management-page">
        <!-- most popular tag section -->
        <div class="row">
            <div class="col text-left">
                <h4>Manage users</h4>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <table class="table table-hover table-striped" style="text-align: left;">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="text-center">Administrator</th>
                        <th class="text-center">Operation</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($viewModel->users as $index => $user)
                        <tr>
                            <td class="align-middle">
                                <form id="user-form-{{ $user->id }}"
                                      action="{{ route('administration.users.update', $user->id) }}"
                                      method="POST">
                                    {{ $index + 1 }}
                                    @method('PUT')
                                    @csrf
                                </form>
                            </td>
                            <td class="align-middle">
                                <input form="user-form-{{ $user->id }}" class="form-control w-100"
                                       type="text" name="name"
                                       placeholder="Name" value="{{ $user->name }}" required/>
                            </td>
                            <td class="align-middle">
                                <input form="user-form-{{ $user->id }}" class="form-control w-100"
                                       type="email" name="email"
                                       placeholder="Email" value="{{ $user->email }}" required/>
                            </td>
                            <td class="text-center align-middle">
                                <input form="user-form-{{ $user->id }}" type="checkbox"
                                       class="form-check-input m-0 d-inline-block position-relative"
                                       value="1"
                                       {{ $viewModel->userIsAdmin($user) ? ' checked ' : '' }} name="admin">
                            </td>
                            <td class="text-center align-middle">
                                <button form="user-form-{{ $user->id }}" type="submit"
                                        class="btn btn-primary">Save
                                </button>
                                <button data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        class="btn btn-danger drop-account">Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card w-75">
                    <div class="card-body">
                        <h4 class="card-title">Create new user</h4>
                        <form id="new-user-form" action="{{ route('administration.users.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="newUserName">Name:</label>
                                <input class="form-control w-50" id="newUserName" type="text" name="name"
                                       value="{{ old('name') != '' ? old('name') : ''}}"
                                       placeholder="Jane Doe" required/>
                            </div>
                            <div class="mb-3">
                                <label for="newUserEmail">Email:</label>
                                <input form="new-user-form"
                                       class="form-control w-50" id="newUserEmail" type="email" name="email"
                                       value="{{ old('email') != '' ? old('email') : ''}}"
                                       placeholder="janedoe@example.com"
                                       required
                                       autocomplete="email"/>
                            </div>
                            <div class="mb-3">
                                <label for="newUserPassword">Password:</label>
                                <input form="new-user-form"
                                       class="form-control w-50" id="newUserPassword" type="password"
                                       name="password" placeholder="" required/>
                            </div>
                            <div class="mb-3">
                                <input form="new-user-form"
                                       type="checkbox" name="admin" class="form-check-input mr-2"
                                       id="newUserAdmin">
                                <label class="form-check-label" for="newUserAdmin">Make user administrator</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    <div class="modal fade" id="dropUserAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="margin-top: 10%">
            <div class="modal-content">
                <form id="deleteUserForm" method="POST" action="{{ route('administration.users.destroy', 0) }}">
                    @method('DELETE')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Are you sure you would like to delete this account? <span
                                id="user-name"></span></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-danger"><b>Warning: </b>
                            <br>This account will be deleted </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Save changes</button>
                    </div>
                </form>
            </div><!--.modal-content-->
        </div><!--.modal-dialog-->
    </div><!--.modal-->
@endpush
@push('scripts')
    <script src="{{ mix('dist/js/user-management.js') }}"></script>
@endpush
