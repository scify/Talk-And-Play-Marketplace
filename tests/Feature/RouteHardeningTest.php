<?php

use Illuminate\Support\Facades\Route;

use function Pest\Laravel\actingAs;

function routeExists(string $method, string $uri): bool {
    return collect(Route::getRoutes()->getRoutes())
        ->contains(fn ($route) => in_array($method, $route->methods(), true) && $route->uri() === $uri);
}

test('debug routes are gone', function (string $path) {
    actingAs(adminUser())->get($path)->assertNotFound();
})->with([
    'sentry test' => '/test-sentry/message',
    'email test' => '/test-email/admin@example.com',
    'admin email test' => '/administration/test-email/admin@example.com',
]);

test('routes without a controller method or a backing feature are not registered', function (string $method, string $uri) {
    expect(routeExists($method, $uri))->toBeFalse();
})->with([
    ['PUT', 'communication-resources/{communication_resource}'],
    ['DELETE', 'communication-resources/{communication_resource}'],
    ['DELETE', 'game-cards/{game_card}'],
    ['POST', 'game-cards'],
    ['GET', 'game-cards/show/package/{id}'],
    ['PUT', 'resources/update_resource/{id}/{type_id}'],
    ['PUT', 'user/profile-information'],
    ['POST', 'user/two-factor-authentication'],
]);

test('member cannot open moderation pages', function (string $path) {
    actingAs(memberUser())->get($path)->assertForbidden();
})->with([
    'reported packages' => '/resources/reported-packages',
    'pending packages' => '/resources/approve_pending_packages',
]);

test('admin can open moderation pages', function (string $path) {
    actingAs(adminUser())->get($path)->assertOk();
})->with([
    'reported packages' => '/resources/reported-packages',
    'pending packages' => '/resources/approve_pending_packages',
]);
