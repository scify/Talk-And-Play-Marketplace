@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/login-page-shapes.css') }}">
@endpush

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center py-5">
            <div class="col-md-8 pt-5">
                <div class="card">
                    <div class="card-header">{{ __('auth.register_btn') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('shapes.request-create-user') }}">
                            @csrf
                            <div class="form-group row mb-4">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('auth.email_label') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('auth.password_label') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('auth.confirm_password_label') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>


                            <div class="form-group row mt-2 mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button id="register_btn" type="submit" class="btn btn-primary">
                                        {{ __('auth.register_btn') }}
                                    </button>
                                </div>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="btn btn-link shapes-message" href="https://kubernetes.pasiphae.eu/shapes/asapa/auth/password/recovery">
                                    {{ __('auth.forgot_password_link') }}
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ mix('dist/js/create-edit-resource.js') }}"></script>
@endpush
