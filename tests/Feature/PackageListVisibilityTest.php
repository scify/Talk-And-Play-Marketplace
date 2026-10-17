<?php

use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

const ALL_STATUSES = '1,2,3';

function listIds($response): array {
    return collect($response->json())->pluck('id')->sort()->values()->all();
}

beforeEach(function () {
    $this->creator = memberUser();
    $this->approved = createPackage($this->creator);
    $this->pending = createPackage($this->creator, ResourceStatusesLkp::CREATED_PENDING_APPROVAL);
    $this->rejected = createPackage($this->creator, ResourceStatusesLkp::REJECTED);
});

test('guest sees only approved packages, whatever statuses they ask for', function () {
    $response = getJson('/api/communication-resources-packages?lang_id=1&status_ids=' . ALL_STATUSES)->assertOk();

    expect(listIds($response))->toBe([$this->approved->id]);
});

test('guest cannot list another user\'s pending packages', function () {
    $response = getJson("/api/communication-resources-packages?lang_id=1&user_id_to_get_content={$this->creator->id}&status_ids=1")->assertOk();

    expect(listIds($response))->toBe([]);
});

test('member cannot list another user\'s pending packages', function () {
    $response = actingAs(memberUser())
        ->getJson("/api/communication-resources-packages?lang_id=1&user_id_to_get_content={$this->creator->id}&status_ids=" . ALL_STATUSES)
        ->assertOk();

    expect(listIds($response))->toBe([$this->approved->id]);
});

test('creator lists all of their own packages', function () {
    $response = actingAs($this->creator)
        ->getJson("/api/communication-resources-packages?lang_id=1&user_id_to_get_content={$this->creator->id}&status_ids=" . ALL_STATUSES)
        ->assertOk();

    expect(listIds($response))->toBe([$this->approved->id, $this->pending->id, $this->rejected->id]);
});

test('admin lists packages of any status', function () {
    $response = actingAs(adminUser())->getJson('/api/communication-resources-packages?lang_id=1&status_ids=1')->assertOk();

    expect(listIds($response))->toBe([$this->pending->id]);
});

test('game package list applies the same rule', function () {
    $game = createPackage($this->creator, ResourceStatusesLkp::CREATED_PENDING_APPROVAL, ResourceTypesLkp::RESPONSE_GAME);

    $response = getJson('/api/game-resources-packages?lang_id=1&type_ids=2,3,4&status_ids=' . ALL_STATUSES)->assertOk();

    expect(listIds($response))->not->toContain($game->id);
});

test('package lists show only the creator id and name', function () {
    $response = getJson('/api/communication-resources-packages?lang_id=1&status_ids=2')->assertOk();

    expect(array_keys($response->json('0.creator')))->toEqualCanonicalizing(['id', 'name']);
});
