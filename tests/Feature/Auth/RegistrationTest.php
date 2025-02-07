<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\assertDatabaseHas;

test('registration page loads successfully', function () {
    $this->get('/register')
        ->assertStatus(200)
        ->assertSee('Register');
});

test('new users can register and login', function () {
    $userData = [
        'name' => 'New Test User',
        'email' => 'new-test-user@example.com',
        'password' => 'Password&123',
        'password_confirmation' => 'Password&123',
    ];

    $response = post('/register', $userData);

    $response->assertRedirect('/');

    // Check user exists in database
    $user = User::where('email', $userData['email'])->first();
    expect($user)->not->toBeNull();

    // Verify the user can login with these credentials
    $loginResponse = post('/login', [
        'email' => $userData['email'],
        'password' => $userData['password'],
    ]);

    $loginResponse->assertRedirect('/');
    expect(auth()->check())->toBeTrue();
});

test('registration requires valid email', function () {
    post('/register', [
        'name' => 'Test User',
        'email' => 'not-an-email',
        'password' => 'Password&123',
        'password_confirmation' => 'Password&123',
    ])->assertSessionHasErrors(['email']);
});


test('registration requires correct password', function () {
    post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'short',
        'password_confirmation' => 'short',
    ])->assertSessionHasErrors(['password']);
});



