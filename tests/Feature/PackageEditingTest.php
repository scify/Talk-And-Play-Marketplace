<?php

use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Notifications\AdminNotice;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;

function childCardOf(ResourcesPackage $package): Resource {
    return Resource::where('resource_parent_id', $package->card_id)->firstOrFail();
}

function renameCard(): array {
    return ['name' => 'Renamed card', 'type_id' => ResourceTypesLkp::COMMUNICATION];
}

test('member cannot update another member\'s card', function () {
    $package = createPackage(memberUser());
    $card = childCardOf($package);

    actingAs(memberUser())->put("/resources/{$card->id}", renameCard())->assertForbidden();

    expect($card->refresh()->name)->toBe('Test card');
});

test('member cannot update another member\'s package', function () {
    $package = createPackage(memberUser());

    actingAs(memberUser())->put("/resources/update_resource_package/{$package->id}", [
        'name' => 'Taken over',
        'lang' => 1,
        'type_id' => ResourceTypesLkp::COMMUNICATION,
        'accept-guideline-terms' => 'on',
        'accept-privacy-terms' => 'on',
    ])->assertForbidden();

    expect(Resource::find($package->card_id)->name)->toBe('Test package');
});

test('member cannot delete another member\'s card', function () {
    $card = childCardOf(createPackage(memberUser()));

    actingAs(memberUser())->delete("/resources/{$card->id}")->assertForbidden();

    expect(Resource::find($card->id))->not->toBeNull();
});

test('member cannot update another member\'s game card', function () {
    $package = createPackage(memberUser(), typeId: ResourceTypesLkp::RESPONSE_GAME);
    $card = childCardOf($package);

    actingAs(memberUser())->put("/game-cards/{$card->id}", ['name' => 'Renamed card'])->assertForbidden();

    expect($card->refresh()->name)->toBe('Test card');
});

test('member cannot add a card to another member\'s package', function () {
    $package = createPackage(memberUser());

    actingAs(memberUser())->post('/resources', [
        'name' => 'Injected card',
        'type_id' => ResourceTypesLkp::COMMUNICATION,
        'parentId' => $package->card_id,
    ])->assertForbidden();

    expect(Resource::where('name', 'Injected card')->exists())->toBeFalse();
});

test('creator edit keeps the creator and sends an approved package back to pending', function () {
    $creator = memberUser();
    $package = createPackage($creator);
    $card = childCardOf($package);

    actingAs($creator)->put("/resources/{$card->id}", renameCard())->assertRedirect();

    expect($card->refresh()->name)->toBe('Renamed card')
        ->and($card->creator_user_id)->toBe($creator->id)
        ->and($package->refresh()->status_id)->toBe(ResourceStatusesLkp::CREATED_PENDING_APPROVAL);
});

test('admin edit keeps the creator and the approved status', function () {
    $creator = memberUser();
    $package = createPackage($creator);
    $card = childCardOf($package);

    actingAs(adminUser())->put("/resources/{$card->id}", renameCard())->assertRedirect();

    expect($card->refresh()->name)->toBe('Renamed card')
        ->and($card->creator_user_id)->toBe($creator->id)
        ->and($package->refresh()->status_id)->toBe(ResourceStatusesLkp::APPROVED);
});

test('member cannot add a card to another member\'s package through the communication form', function () {
    $package = createPackage(memberUser());

    actingAs(memberUser())->post('/communication-resources', [
        'name' => 'Injected card',
        'parentId' => $package->card_id,
    ])->assertForbidden();

    expect(Resource::where('name', 'Injected card')->exists())->toBeFalse();
});

test('a card added by the creator through the communication form sends an approved package back to pending', function () {
    $creator = memberUser();
    $package = createPackage($creator);

    actingAs($creator)->post('/communication-resources', [
        'name' => 'New card',
        'parentId' => $package->card_id,
        'image' => UploadedFile::fake()->image('card.png', 600, 600)->size(50),
        'sound' => UploadedFile::fake()->create('card.mp3', 50, 'audio/mpeg'),
    ])->assertRedirect();

    expect(Resource::where('name', 'New card')->value('resource_parent_id'))->toBe($package->card_id)
        ->and($package->refresh()->status_id)->toBe(ResourceStatusesLkp::CREATED_PENDING_APPROVAL);
});

test('admins are told when a member\'s edit sends an approved package back to pending', function () {
    Notification::fake();
    $admin = adminUser();
    $creator = memberUser();
    $card = childCardOf(createPackage($creator));

    actingAs($creator)->put("/resources/{$card->id}", renameCard())->assertRedirect();

    Notification::assertSentTo($admin, AdminNotice::class);
});

test('an admin edit sends no approval request', function () {
    Notification::fake();
    $card = childCardOf(createPackage(memberUser()));

    actingAs(adminUser())->put("/resources/{$card->id}", renameCard())->assertRedirect();

    Notification::assertNothingSent();
});

test('a failing admin notice does not fail a saved edit', function (string $route) {
    Notification::shouldReceive('send')->andThrow(new RuntimeException('SMTP down'));
    $creator = memberUser();
    $package = createPackage($creator);
    $card = childCardOf($package);

    $response = $route === 'update'
        ? actingAs($creator)->put("/resources/{$card->id}", renameCard())
        : actingAs($creator)->post('/communication-resources', [
            'name' => 'New card',
            'parentId' => $package->card_id,
            'image' => UploadedFile::fake()->image('card.png', 600, 600)->size(50),
            'sound' => UploadedFile::fake()->create('card.mp3', 50, 'audio/mpeg'),
        ]);

    $response->assertRedirect()->assertSessionMissing('flash_message_failure');
    expect($package->refresh()->status_id)->toBe(ResourceStatusesLkp::CREATED_PENDING_APPROVAL);
})->with(['update', 'add card']);
