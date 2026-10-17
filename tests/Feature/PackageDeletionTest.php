<?php

use App\Models\Resource\ResourcesPackage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

test('guest cannot delete a package', function () {
    $package = createPackage(memberUser());

    delete("/resources/delete/package/{$package->id}")->assertRedirect('/login');

    expect(ResourcesPackage::find($package->id))->not->toBeNull();
});

test('member cannot delete another member\'s package', function () {
    $package = createPackage(memberUser());

    actingAs(memberUser())->delete("/resources/delete/package/{$package->id}")->assertForbidden();

    expect(ResourcesPackage::find($package->id))->not->toBeNull();
});

test('creator can delete their own package', function () {
    $creator = memberUser();
    $package = createPackage($creator);

    actingAs($creator)->delete("/resources/delete/package/{$package->id}")->assertRedirect();

    expect(ResourcesPackage::find($package->id))->toBeNull();
});

test('admin can delete any package', function () {
    $package = createPackage(memberUser());

    actingAs(adminUser())->delete("/resources/delete/package/{$package->id}")->assertRedirect();

    expect(ResourcesPackage::find($package->id))->toBeNull();
});

test('a package cannot be deleted with GET', function () {
    $creator = memberUser();
    $package = createPackage($creator);

    actingAs($creator)->get("/resources/delete/package/{$package->id}")->assertMethodNotAllowed();

    expect(ResourcesPackage::find($package->id))->not->toBeNull();
});

test('deleting an unknown package answers 404', function () {
    actingAs(adminUser())->delete('/resources/delete/package/999999')->assertNotFound();
});
