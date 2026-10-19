<?php

test('tests generate URLs for http://localhost, whatever APP_URL the .env sets', function () {
    // A DDEV .env sets an https APP_URL; tests must not depend on it.
    expect(url('/'))->toBe('http://localhost')
        ->and(route('content-guidelines'))->toBe('http://localhost/content-guidelines');
});
