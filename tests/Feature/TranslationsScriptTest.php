<?php

use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\get;

test('translations script is served as javascript', function () {
    get('/js/translations.js')
        ->assertOk()
        ->assertHeader('Content-Type', 'text/javascript; charset=UTF-8')
        ->assertSee('window.i18n = {', false);
});

test('translations script keeps the application cache', function () {
    // The login rate limiter lives in the same cache; a flush would reset it.
    Cache::put('probe', 'kept');

    get('/js/translations.js')->assertOk();

    expect(Cache::get('probe'))->toBe('kept');
});
