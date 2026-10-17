<?php

use App\Models\Resource\ResourcesPackageRating;

use function Pest\Laravel\actingAs;

test('a rating is stored for the logged-in user, not the user id in the request', function () {
    $package = createPackage(memberUser());
    $voter = memberUser();
    $victim = memberUser();

    actingAs($voter)->post('/resources-package/user-rating', [
        'user_id' => $victim->id,
        'resources_package_id' => $package->id,
        'rating' => 5,
    ])->assertSuccessful();

    expect(ResourcesPackageRating::where('voter_user_id', $voter->id)->value('rating'))->toBe(5)
        ->and(ResourcesPackageRating::where('voter_user_id', $victim->id)->exists())->toBeFalse();
});

test('a user reads only their own rating', function () {
    $package = createPackage(memberUser());
    $other = memberUser();
    ResourcesPackageRating::create(['resources_package_id' => $package->id, 'voter_user_id' => $other->id, 'rating' => 2]);

    $response = actingAs(memberUser())->getJson("/resources-package/user-rating?resources_package_id={$package->id}&user_id={$other->id}");

    // No rating of the logged-in user exists, so the response is empty.
    expect($response->assertOk()->getContent())->not->toContain('"voter_user_id":' . $other->id);
});
