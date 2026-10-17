<?php

use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

dataset('administration pages', [
    'users' => '/administration/users',
    'desktop app announcements' => '/administration/desktop_app_announcements',
    'platform statistics' => '/administration/platform-statistics',
]);

dataset('member pages', [
    'create communication package' => '/communication-resources/create',
    'create game package' => '/game-cards/create?type_id=2',
    'my packages' => '/my-packages',
]);

function memberUser(): User {
    $user = User::factory()->create();
    app(UserRoleManager::class)->assignRegisteredUserRoleTo($user);

    return $user->refresh();
}

function adminUser(): User {
    $user = User::factory()->create();
    app(UserRoleManager::class)->assignAdminUserRoleTo($user);

    return $user->refresh();
}

test('guest is redirected to login from administration page', function (string $path) {
    get($path)->assertRedirect('/login');
})->with('administration pages');

test('guest is redirected to login from member page', function (string $path) {
    get($path)->assertRedirect('/login');
})->with('member pages');

test('member cannot open administration page', function (string $path) {
    actingAs(memberUser())->get($path)->assertForbidden();
})->with('administration pages');

test('admin can open administration page', function (string $path) {
    actingAs(adminUser())->get($path)->assertOk();
})->with('administration pages');

test('member can open member page', function (string $path) {
    actingAs(memberUser())->get($path)->assertOk();
})->with('member pages');
