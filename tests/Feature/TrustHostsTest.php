<?php

use Illuminate\Http\Request;

use function Pest\Laravel\get;

beforeEach(function () {
    // TrustHosts is inactive in the local and testing environments.
    $this->app['env'] = 'production';
    config(['app.url' => 'https://marketplace.example.org']);
});

afterEach(function () {
    // Trusted hosts are static on the Symfony request, so reset them for the next tests.
    Request::setTrustedHosts([]);
});

test('a foreign host is rejected', function () {
    get('https://evil.example/content-guidelines')->assertBadRequest();
});

test('the application host is accepted', function () {
    get('https://marketplace.example.org/content-guidelines')->assertOk();
});

test('a subdomain of the application host is accepted', function () {
    get('https://www.marketplace.example.org/content-guidelines')->assertOk();
});
