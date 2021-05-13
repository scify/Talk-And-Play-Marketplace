@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/user-management-page.css') }}">
@endpush

@section('content')
    <div class="container pt-5" id="user-management-page">
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
                                       type="text" name="userName"
                                       placeholder="Name" value="{{ $user->name }}" required/>
                            </td>
                            <td class="align-middle">
                                <input form="user-form-{{ $user->id }}" class="form-control w-100"
                                       type="email" name="userEmail"
                                       placeholder="Email" value="{{ $user->email }}" required/>
                            </td>
                            <td class="text-center align-middle">
                                <input form="user-form-{{ $user->id }}" type="checkbox"
                                       class="form-check-input m-0 d-inline-block position-relative"
                                       value="1"
                                       {{ $user->is_admin ? ' checked ' : '' }} name="userToggleAdmin">
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
            <div class="col text-left">
                <h4>Create new user</h4>
            </div>
        </div>
        <div class="graph-card mb-4">
            <div class="graph-card-body">
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <form id="new-user-form" action="{{ route('administration.users.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="newUserName">First and last name:</label>
                                    <input class="form-control w-50" id="newUserName" type="text" name="name"
                                           value="{{ old('name') != '' ? old('name') : ''}}"
                                           placeholder="Jane Doe" required/>
                                </div>
                                <div class="form-group">
                                    <label for="newUserEmail">Email:</label>
                                    <input form="new-user-form"
                                           class="form-control w-50" id="newUserEmail" type="email" name="email"
                                           value="{{ old('email') != '' ? old('email') : ''}}"
                                           placeholder="janedoe@example.com"
                                           required
                                           autocomplete="email"/>
                                </div>
                                <div class="form-group">
                                    <label for="newUserPassword">Password:</label>
                                    <input form="new-user-form"
                                           class="form-control w-50" id="newUserPassword" type="password"
                                           name="password" placeholder="" required/>
                                </div>
                                <div class="form-check text-left">
                                    <input form="new-user-form"
                                           type="checkbox" name="newUserAdmin" class="form-check-input"
                                           id="newUserAdmin">
                                    <label class="form-check-label" for="newUserAdmin">Make user administrator</label>
                                </div>
                                <br/>
                                <div class="text-left">
                                    <button type="submit" class="btn btn-primary" value="Create new user">Create new
                                        user
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    <div class="modal scale fade" id="dropUserAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="margin-top: 10%">
            <div class="modal-content">
                <form id="deleteUserForm" method="POST" action="{{ route('administration.users.destroy', 0) }}">
                    @method('DELETE')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Are you sure you would like to delete this account? <span
                                id="user-name"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p class="text-danger"><b>Warning: </b>
                            <br>This account will be deleted </p>
                    </div>
                    <div class="modal-footer">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-6 mx-auto text-center pl-0">
                                    <button type="button" class="btn btn-flat btn-default btn-sm" data-dismiss="modal">
                                        Cancel
                                    </button>
                                </div>
                                <div class="col-6 mx-auto text-center pr-0">
                                    <button type="submit" class="btn btn-flat btn-primary btn-danger btn-sm">Yes,
                                        Delete account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!--.modal-content-->
        </div><!--.modal-dialog-->
    </div><!--.modal-->
@endpush
