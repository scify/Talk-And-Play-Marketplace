<?php

use function Pest\Laravel\get;

test('home page loads successfully', function () {
    get('/')
        ->assertStatus(200);
});

test('home page contains expected title', function () {
    get('/')
        ->assertSee('Talk & Play Marketplace');
});
