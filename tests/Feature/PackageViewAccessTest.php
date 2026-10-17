<?php

use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;

use function Pest\Laravel\actingAs;

const PENDING = ResourceStatusesLkp::CREATED_PENDING_APPROVAL;

test('member cannot open the edit page of another member\'s package', function () {
    $package = createPackage(memberUser());

    actingAs(memberUser())->get("/communication-resources/{$package->id}/edit")->assertForbidden();
});

test('member cannot open the edit page of another member\'s game package', function () {
    $package = createPackage(memberUser(), PENDING, ResourceTypesLkp::RESPONSE_GAME);

    actingAs(memberUser())->get("/game-cards/{$package->id}/edit")->assertForbidden();
});

test('creator and admin can open the edit page', function () {
    $creator = memberUser();
    $package = createPackage($creator, PENDING);

    actingAs($creator)->get("/communication-resources/{$package->id}/edit")->assertOk();
    actingAs(adminUser())->get("/communication-resources/{$package->id}/edit")->assertOk();
});

test('member cannot download another member\'s pending package', function () {
    $package = createPackage(memberUser(), PENDING);

    actingAs(memberUser())->get("/communication-cards/download/package/{$package->id}")->assertForbidden();
});

test('member cannot download another member\'s pending game package', function () {
    $package = createPackage(memberUser(), PENDING, ResourceTypesLkp::RESPONSE_GAME);

    actingAs(memberUser())->get("/game-cards/download/package/{$package->id}")->assertForbidden();
});

test('member cannot clone another member\'s pending package', function () {
    $package = createPackage(memberUser(), PENDING);

    actingAs(memberUser())->get("/resources/clone_package/{$package->id}")->assertForbidden();
});
