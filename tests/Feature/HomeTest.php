<?php

use function Pest\Laravel\get;

test('home page loads successfully', function () {
    get('/')
        ->assertStatus(200);
});

test('home page contains expected title', function () {
    get('/')
        ->assertSee('<h1>Talk & Play marketplace</h1>', false);
});
