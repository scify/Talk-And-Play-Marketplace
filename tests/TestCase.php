<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase {
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the SQLite testing database exists
        $dbPath = database_path('testing.sqlite');
        if (!File::exists($dbPath)) {
            File::put($dbPath, '');
        }

        // Drop all tables and run migrations fresh
        if (Schema::hasTable('users')) {
            $this->artisan('migrate:fresh', ['--force' => true, '--seed' => true, '--database' => 'sqlite_testing']);
        } else {
            $this->artisan('migrate', ['--force' => true, '--seed' => true, '--database' => 'sqlite_testing']);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
