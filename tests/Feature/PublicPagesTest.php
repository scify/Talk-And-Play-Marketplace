<?php

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

test('guest can open public page', function (string $path) {
    get($path)->assertOk();
})->with([
    'home' => '/',
    'communication cards' => '/communication-cards',
    'game cards' => '/game-cards',
    'content guidelines' => '/content-guidelines',
    'how it works (marketplace)' => '/how-it-works-marketplace',
    'how it works (desktop)' => '/how-it-works-desktop',
    'login' => '/login',
    'register' => '/register',
    'translations script' => '/js/translations.js',
]);

test('guest can read lookup endpoint', function (string $path) {
    getJson($path)->assertOk();
})->with([
    'content languages' => '/api/content-languages',
    'communication packages' => '/api/communication-resources-packages?lang_id=1',
    'game packages' => '/api/game-resources-packages?lang_id=1',
]);
