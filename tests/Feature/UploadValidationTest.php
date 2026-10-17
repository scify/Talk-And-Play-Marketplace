<?php

use App\BusinessLogicLayer\Resource\ResourceFileManager;
use App\Models\Resource\Resource;
use App\Repository\Resource\ResourceTypesLkp;
use Illuminate\Http\Request;
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

function htmlUpload(string $name): UploadedFile {
    return UploadedFile::fake()->createWithContent($name, '<script>alert(1)</script><!--' . str_repeat('x', 20 * 1024) . '-->');
}

test('a game card rejects a sound file that is not mp3', function () {
    $creator = memberUser();
    $package = createPackage($creator, typeId: ResourceTypesLkp::RESPONSE_GAME);

    actingAs($creator)->post('/resources', [
        'name' => 'Game card',
        'type_id' => ResourceTypesLkp::RESPONSE_GAME,
        'parentId' => $package->card_id,
        'image' => UploadedFile::fake()->image('card.png', 600, 600)->size(50),
        'sound' => htmlUpload('sound.html'),
    ])->assertSessionHasErrors('sound');

    expect(Resource::where('name', 'Game card')->exists())->toBeFalse();
});

test('a game card update rejects a sound file that is not mp3', function (string $method, string $uri) {
    $creator = memberUser();
    $package = createPackage($creator, typeId: ResourceTypesLkp::RESPONSE_GAME);
    $card = Resource::where('resource_parent_id', $package->card_id)->firstOrFail();

    actingAs($creator)->call($method, str_replace('{card}', $card->id, $uri), [
        'name' => 'Renamed card',
        'type_id' => ResourceTypesLkp::RESPONSE_GAME,
    ], [], ['sound' => htmlUpload('sound.html')])->assertSessionHasErrors('sound');
})->with([
    'resources update' => ['PUT', '/resources/{card}'],
    'game card update' => ['PUT', '/game-cards/{card}'],
]);

test('the file manager stores only mp3 content as sound', function () {
    // A real upload with HTML content and an mp3 name: the content decides.
    $path = tempnam(sys_get_temp_dir(), 'upload');
    file_put_contents($path, '<html><body><script>alert(1)</script></body></html>');
    $request = Request::create('/', 'POST', [], [], ['sound' => new UploadedFile($path, 'sound.mp3', null, null, true)]);

    try {
        expect(fn () => (new ResourceFileManager)->saveAudio(7, $request))->toThrow(InvalidArgumentException::class);
    } finally {
        unlink($path);
    }
});
