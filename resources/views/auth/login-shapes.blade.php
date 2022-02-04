@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/login-page-shapes.css') }}">
@endpush

@section('content')
    <main>
        <div class="login-page-shapes d-flex " style="text-align:center" >
            <form method="POST" action="{{ route('shapes.request-login-token') }}" class="content mt-5">
                <img loading="lazy" src="{{ asset('img/shapes_logo.png') }}" height="120px" alt="Shapes logo">
                @csrf
                <h2 class="text-center mb-5 mt-5 shapes-message">{{  __('auth.login_btn')}}</h2>
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('auth.email_label') }}</label>
                    <input type="email" name='email' class="form-control @error('email') is-invalid @enderror" placeholder="{{  __('auth.type_mail')}}"
                           id="email" aria-describedby="emailHelp" required autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="password" class="form-label">{{ __('auth.password_label') }}</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{  __('auth.type_password')}}"
                           id="password" required autocomplete="current-password" name="password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                {{--            <p class="text-center mt-5 ">Με την σύνδεσής σας αποδέχεστε τους <a href="#">όρους χρήσης</a>.</p>--}}
                <button type="submit" class="btn btn-primary mb-5 mt-3 shapes-btn"> {{ __('auth.login_btn') }}</button>
                @if (Route::has('password.request'))
                    <a class="btn btn-link shapes-message" style="float:left; font-size: small" href="https://kubernetes.pasiphae.eu/shapes/asapa/auth/password/recovery">
                        {{ __('auth.forgot_password_link') }}
                    </a>
                @endif
                <div class="container" style="text-align: right">
                <p>
                    <a  href="{{ route('shapes.register-shapes') }}">
                        {{ __('auth.register_btn')}} with SHAPES
                    </a>
                </p>
                </div>


                {{--                <p class="text-center shapes-message">  {{ __('auth.no_account') }}<a href="{{ route('register') }} ">{{ __('auth.register_here') }}</a></p>--}}
            </form>
        </div>
    </main>
@endsection


