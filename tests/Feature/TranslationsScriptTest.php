<?php

use function Pest\Laravel\get;

test('translations script is served as javascript', function () {
    get('/js/translations.js')
        ->assertOk()
        ->assertHeader('Content-Type', 'text/javascript; charset=UTF-8')
        ->assertSee('window.i18n = {', false);
});
