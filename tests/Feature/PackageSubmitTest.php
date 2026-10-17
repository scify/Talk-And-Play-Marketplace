<?php

use App\Notifications\AdminNotice;
use App\Repository\Resource\ResourceStatusesLkp;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;

beforeEach(fn () => Notification::fake());

test('member cannot submit another member\'s package', function () {
    $package = createPackage(memberUser());

    actingAs(memberUser())->put("/resources/submit/{$package->id}")->assertForbidden();

    expect($package->refresh()->status_id)->toBe(ResourceStatusesLkp::APPROVED);
    Notification::assertNothingSent();
});

test('creator submits their package for approval', function () {
    $creator = memberUser();
    $admin = adminUser();
    $package = createPackage($creator, ResourceStatusesLkp::REJECTED);

    actingAs($creator)->put("/resources/submit/{$package->id}")->assertRedirect();

    expect($package->refresh()->status_id)->toBe(ResourceStatusesLkp::CREATED_PENDING_APPROVAL);
    Notification::assertSentTo($admin, AdminNotice::class);
});
