<?php

use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;

function profileUser(bool $admin = false): User {
    $user = User::factory()->create();
    $roleManager = app(UserRoleManager::class);
    $admin ? $roleManager->assignAdminUserRoleTo($user) : $roleManager->assignRegisteredUserRoleTo($user);

    return $user->refresh();
}

function isAdmin(User $user): bool {
    return app(UserRoleManager::class)->userHasAdminRole($user->refresh());
}

test('guest cannot update a profile', function () {
    $user = profileUser();

    put("/users/update/{$user->id}", ['name' => 'x', 'email' => 'x@example.com'])->assertRedirect('/login');
});

test('member cannot update another user', function () {
    $attacker = profileUser();
    $victim = profileUser();

    actingAs($attacker)
        ->put("/users/update/{$victim->id}", ['name' => 'Taken', 'email' => 'attacker@example.com'])
        ->assertForbidden();

    expect($victim->refresh()->email)->not->toBe('attacker@example.com');
});

test('member cannot make themselves admin', function () {
    $member = profileUser();

    actingAs($member)->put("/users/update/{$member->id}", [
        'name' => $member->name,
        'email' => $member->email,
        'admin' => 1,
    ]);

    expect(isAdmin($member))->toBeFalse();
});

test('admin keeps the admin role after saving their own profile', function () {
    $admin = profileUser(admin: true);

    actingAs($admin)
        ->put("/users/update/{$admin->id}", ['name' => 'New name', 'email' => $admin->email])
        ->assertRedirect();

    expect(isAdmin($admin))->toBeTrue()
        ->and($admin->refresh()->name)->toBe('New name');
});

test('member can change their own name, email and password', function () {
    $member = profileUser();

    actingAs($member)
        ->put("/users/update/{$member->id}", [
            'name' => 'Renamed',
            'email' => 'renamed@example.com',
            'password' => 'New-password-123',
            'password_confirmation' => 'New-password-123',
        ])
        ->assertSessionHasNoErrors();

    $member->refresh();
    expect($member->name)->toBe('Renamed')
        ->and($member->email)->toBe('renamed@example.com')
        ->and(Hash::check('New-password-123', $member->password))->toBeTrue();
});

test('an empty password field keeps the password', function () {
    $member = profileUser();
    $hash = $member->password;

    actingAs($member)->put("/users/update/{$member->id}", [
        'name' => $member->name,
        'email' => $member->email,
        'password' => '',
    ]);

    expect($member->refresh()->password)->toBe($hash);
});
