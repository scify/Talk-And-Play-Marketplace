<script>
    window.Laravel = {!! json_encode([
	'csrfToken' => csrf_token(),
	'baseUrl' => url('/'),
	'routes' => collect(\Route::getRoutes())->mapWithKeys(function ($route) { return [$route->getName() => $route->uri()]; })
]) !!};
</script>
<script src="{{ route('translations') }}"></script>
@vite('resources/js/app.js')
@include('common.analytics')
@if(config('app.userway_id'))
    <script>(function (d) {
            var s = d.createElement("script");
            s.setAttribute("data-account", "{{config('app.userway_id')}}");
            s.setAttribute("src", "https://cdn.userway.org/widget.js");
            (d.body || d.head).appendChild(s);
        })(document)</script>
    <noscript>Please ensure Javascript is enabled for purposes of <a href="https://userway.org">website
            accessibility</a></noscript>
@endif
@stack('scripts')


