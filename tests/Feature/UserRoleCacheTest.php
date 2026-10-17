<?php

use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Repository\User\UserRole\UserRolesLkp;
use Illuminate\Support\Facades\Cache;

test('cached role entries cannot make a member an admin', function () {
    $member = memberUser();
    $roleManager = app(UserRoleManager::class);
    $roleManager->userHasAdminRole($member);

    // The keys that the old role cache wrote and read.
    $key = 'user_' . $member->id . '_role_' . UserRolesLkp::ADMIN;
    Cache::forever($key, true);
    Cache::forever($key . $member->id, true);

    expect($roleManager->userHasAdminRole($member))->toBeFalse();
});

test('admin role checks follow the stored roles', function () {
    $user = memberUser();
    $roleManager = app(UserRoleManager::class);

    $roleManager->assignAdminUserRoleTo($user);
    expect($roleManager->userHasAdminRole($user->refresh()))->toBeTrue();

    $roleManager->removeAdminRoleFromUser($user);
    expect($roleManager->userHasAdminRole($user->refresh()))->toBeFalse();
});
