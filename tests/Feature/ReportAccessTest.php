<?php

use App\Models\Reports;
use App\Notifications\ReportResponseNotice;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

beforeEach(function () {
    Notification::fake();
    $this->reporter = memberUser();
    $this->report = Reports::create([
        'package_id' => createPackage(memberUser())->id,
        'reporting_user_id' => $this->reporter->id,
        'reason' => 'spam',
        'comment' => 'confidential report comment',
    ]);
});

test('the old public report endpoints are gone', function () {
    getJson('/api/resources/user-reports?type_ids=1')->assertNotFound();
    postJson('/api/resources/respond', ['reporting_user_id' => $this->reporter->id, 'response' => 'x'])->assertNotFound();
});

test('guest cannot read reports', function () {
    get('/resources/user-reports?type_ids=1')->assertRedirect('/login');
});

test('member cannot read reports', function () {
    actingAs(memberUser())->getJson('/resources/user-reports?type_ids=1')->assertForbidden();
});

test('admin reads reports without the reporter\'s private fields', function () {
    $response = actingAs(adminUser())->getJson('/resources/user-reports?type_ids=1')->assertOk();

    expect($response->json('0.reportData.comment'))->toBe('confidential report comment')
        ->and(array_keys($response->json('0.creator')))->toEqualCanonicalizing(['id', 'name']);
});

test('member cannot send a response to a reporter', function () {
    actingAs(memberUser())->post("/resources/respond/{$this->report->id}", ['response' => 'phishing link'])->assertForbidden();

    Notification::assertNothingSent();
});

test('guest cannot send a response to a reporter', function () {
    post("/resources/respond/{$this->report->id}", ['response' => 'phishing link'])->assertRedirect('/login');

    Notification::assertNothingSent();
});

test('admin response goes to the user who filed the report', function () {
    $someoneElse = memberUser();

    actingAs(adminUser())->post("/resources/respond/{$this->report->id}", [
        'response' => 'We removed the package.',
        'reporting_user_id' => $someoneElse->id,
    ])->assertRedirect();

    Notification::assertSentTo($this->reporter, ReportResponseNotice::class);
    Notification::assertNotSentTo($someoneElse, ReportResponseNotice::class);
});
