<?php

use App\Models\Resource\ResourcesPackage;
use App\Repository\Resource\ResourceStatusesLkp;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;

beforeEach(fn () => Notification::fake());

function statusOf(ResourcesPackage $package): int {
    return $package->refresh()->status_id;
}

test('member cannot approve a package', function () {
    $creator = memberUser();
    $package = createPackage($creator, ResourceStatusesLkp::CREATED_PENDING_APPROVAL);

    actingAs($creator)->post("/resources/approve/{$package->id}", ['id' => $package->id])->assertForbidden();

    expect(statusOf($package))->toBe(ResourceStatusesLkp::CREATED_PENDING_APPROVAL);
});

test('member cannot reject a package', function () {
    $package = createPackage(memberUser());

    actingAs(memberUser())->post("/resources/reject/{$package->id}", [
        'id' => $package->id,
        'rejection_reason' => 'spam',
        'rejection_comment' => 'phishing text',
    ])->assertForbidden();

    expect(statusOf($package))->toBe(ResourceStatusesLkp::APPROVED);
    Notification::assertNothingSent();
});

test('admin approves the package in the route, not the id in the body', function () {
    $routed = createPackage(memberUser(), ResourceStatusesLkp::CREATED_PENDING_APPROVAL);
    $other = createPackage(memberUser(), ResourceStatusesLkp::CREATED_PENDING_APPROVAL);

    actingAs(adminUser())->post("/resources/approve/{$routed->id}", ['id' => $other->id])->assertRedirect();

    expect(statusOf($routed))->toBe(ResourceStatusesLkp::APPROVED)
        ->and(statusOf($other))->toBe(ResourceStatusesLkp::CREATED_PENDING_APPROVAL);
});

test('admin rejects the package in the route, not the id in the body', function () {
    $routed = createPackage(memberUser(), ResourceStatusesLkp::CREATED_PENDING_APPROVAL);
    $other = createPackage(memberUser(), ResourceStatusesLkp::CREATED_PENDING_APPROVAL);

    actingAs(adminUser())->post("/resources/reject/{$routed->id}", [
        'id' => $other->id,
        'rejection_reason' => 'quality',
        'rejection_comment' => 'please improve the images',
    ])->assertRedirect();

    expect(statusOf($routed))->toBe(ResourceStatusesLkp::REJECTED)
        ->and(statusOf($other))->toBe(ResourceStatusesLkp::CREATED_PENDING_APPROVAL);
});
