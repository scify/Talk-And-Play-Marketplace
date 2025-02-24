<?php

use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('admin users can access admin routes', function () {
    $user = User::factory()->create();
    $roleManager = app(UserRoleManager::class);
    $roleManager->assignAdminUserRoleTo($user);

    // Refresh the user to ensure the role is applied
    $user->refresh();

    actingAs($user)
        ->get('/test-sentry/test')
        ->assertStatus(500); // This route throws an exception, but should be accessible
});

test('regular users cannot access admin routes', function () {
    $user = User::factory()->create();
    $roleManager = app(UserRoleManager::class);
    $roleManager->assignRegisteredUserRoleTo($user);

    // Refresh the user to ensure the role is applied
    $user->refresh();

    actingAs($user)
        ->get('/test-sentry/test')
        ->assertStatus(403);
});
