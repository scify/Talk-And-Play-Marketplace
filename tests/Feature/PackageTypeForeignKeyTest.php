<?php

use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;
use Illuminate\Database\QueryException;

function packageOfType(int $typeId): ResourcesPackage {
    $creator = memberUser();
    $cover = Resource::create(['name' => 'Cover', 'creator_user_id' => $creator->id]);

    return ResourcesPackage::create([
        'lang_id' => 1,
        'type_id' => $typeId,
        'card_id' => $cover->id,
        'status_id' => ResourceStatusesLkp::CREATED_PENDING_APPROVAL,
        'creator_user_id' => $creator->id,
    ]);
}

test('a package type does not depend on which resource ids exist', function () {
    // type_id used to reference resources.id, so type 4 needed a resource with id 4.
    expect(Resource::count())->toBe(0)
        ->and(packageOfType(ResourceTypesLkp::SIMILAR_GAME)->exists)->toBeTrue();
});

test('an unknown package type is refused', function () {
    expect(fn () => packageOfType(99))->toThrow(QueryException::class);
});

test('the type key migration stops before any change when a package has an unknown type', function () {
    $migration = require database_path('migrations/2026_10_19_000000_point_resources_package_type_id_at_resource_types.php');
    $migration->down();
    // With the old key, type 77 only needs a resource with id 77.
    $creator = memberUser();
    $cover = Resource::forceCreate(['id' => 77, 'name' => 'Cover', 'creator_user_id' => $creator->id]);
    ResourcesPackage::create([
        'lang_id' => 1, 'type_id' => 77, 'card_id' => $cover->id,
        'status_id' => ResourceStatusesLkp::CREATED_PENDING_APPROVAL, 'creator_user_id' => $creator->id,
    ]);

    expect(fn () => $migration->up())->toThrow(RuntimeException::class, 'unknown type_id');
});
