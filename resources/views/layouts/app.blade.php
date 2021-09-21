<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title_prefix', config('app.name')) @yield('title_postfix', '')</title>
    <meta name="description" content="{{ __('messages.meta_tags_description') }}">
    <meta name="keywords" content="talk and play,disability,rehabilitation,cerebral palsy">
    <meta name="og:title" property="og:title" content="{{ config('app.name') }}">
    <meta name="og:type" property="og:type" content="website">
    <meta name="og:url" property="og:url" content="{{ url()->current() }}">
    <meta name="og:image" property="og:image" content="{{ asset('img/advertisement-poster.png') }}">
    <meta name="og:description" property="og:description" content="{{ __('messages.meta_tags_description') }}">
    <meta name="og:site_name" property="og:site_name" content="Talk & Play - Marketplace">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Talk & Play - Marketplace"/>
    <meta name="twitter:description" content="{{ __('messages.meta_tags_description') }}"/>
    <meta name="twitter:image" content="{{ asset('img/advertisement-poster.png') }}"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('common.favicons')
    <link rel="stylesheet" href="{{ mix('dist/css/app.css') }}">
    @stack('css')
            <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition background-page @yield('body_class')">
<div id="app">
    @include('common.staging-indication')
    @include('common.navbar')
    @include('common.alerts')
    <main class="py-5 mb-5">
        <div id="main-content">
            @yield('content')
        </div>
    </main>
    @include('common.footer')
</div>
@include('common.footer-scripts')
@stack('modals')
</body>
</html>

