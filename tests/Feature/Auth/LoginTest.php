<?php

use App\Models\User;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('login page loads successfully', function () {
    get('/login')
        ->assertStatus(200)
        ->assertSee('Login');
});

test('user can login with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ])->assertRedirect('/');

    $this->assertAuthenticated();
});

test('user cannot login with incorrect password', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    post('/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});
