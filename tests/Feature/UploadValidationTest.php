<?php

use App\BusinessLogicLayer\Resource\ResourceFileManager;
use App\Models\Resource\Resource;
use App\Repository\Resource\ResourceTypesLkp;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\actingAs;

test('a new card rejects a sound file that is not mp3', function () {
    $creator = memberUser();
    $package = createPackage($creator);

    actingAs($creator)->post('/resources', [
        'name' => 'New card',
        'type_id' => ResourceTypesLkp::COMMUNICATION,
        'parentId' => $package->card_id,
        'image' => UploadedFile::fake()->image('card.png', 600, 600)->size(50),
        // Large enough for the size rule, so that only the type check can reject it.
        'sound' => UploadedFile::fake()->createWithContent('sound.html', '<script>alert(1)</script><!--' . str_repeat('x', 20 * 1024) . '-->'),
    ])->assertSessionHasErrors('sound');

    expect(Resource::where('name', 'New card')->exists())->toBeFalse();
});

test('a game card update rejects an image that is not jpg or png', function () {
    $creator = memberUser();
    $package = createPackage($creator, typeId: ResourceTypesLkp::RESPONSE_GAME);
    $card = Resource::where('resource_parent_id', $package->card_id)->firstOrFail();

    actingAs($creator)->put("/game-cards/{$card->id}", [
        'name' => 'Renamed card',
        // Large enough for the size rule, so that only the type check can reject it.
        'image' => UploadedFile::fake()->createWithContent('image.svg', '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><!--' . str_repeat('x', 20 * 1024) . '--></svg>'),
    ])->assertSessionHasErrors('image');
});

test('a stored file gets the extension of its content, not of its client name', function () {
    // A real upload: fake files derive their MIME type from the name, real ones from the content.
    $path = tempnam(sys_get_temp_dir(), 'upload');
    imagepng(imagecreatetruecolor(10, 10), $path);
    $upload = new UploadedFile($path, 'evil.php', null, null, true);

    $name = (new ResourceFileManager)->getNormalizedResourceName($upload, 7);
    unlink($path);

    expect($name)->toEndWith('.png');
});
