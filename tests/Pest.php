<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Models\User;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(
    TestCase::class,
    RefreshDatabase::class,
)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

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

function createPackage(User $creator, int $statusId = ResourceStatusesLkp::APPROVED, int $typeId = ResourceTypesLkp::COMMUNICATION): ResourcesPackage {
    $cover = Resource::create(['name' => 'Test package', 'creator_user_id' => $creator->id]);
    Resource::create(['name' => 'Test card', 'creator_user_id' => $creator->id, 'resource_parent_id' => $cover->id]);

    return ResourcesPackage::create([
        'lang_id' => 1,
        'type_id' => $typeId,
        'card_id' => $cover->id,
        'status_id' => $statusId,
        'creator_user_id' => $creator->id,
    ]);
}
