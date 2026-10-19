<?php

use function Pest\Laravel\get;

function packageVersion(): string {
    return json_decode(file_get_contents(base_path('package.json')), true, flags: JSON_THROW_ON_ERROR)['version'];
}

test('the app version comes from package.json', function () {
    expect(config('app.version'))->toBe(packageVersion());
});

test('the footer shows the app version', function () {
    get('/')->assertOk()->assertSee('version <b>' . packageVersion() . '</b>', false);
});
