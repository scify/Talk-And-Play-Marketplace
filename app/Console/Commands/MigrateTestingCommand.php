<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateTestingCommand extends Command
{
    protected $signature = 'migrate:testing';
    protected $description = 'Run migrations for testing with SQLite compatibility';

    public function handle()
    {
        // Disable foreign key constraints
        DB::statement('PRAGMA foreign_keys = OFF');

        // Drop all existing tables
        $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');
        foreach ($tables as $table) {
            if ($table->name === 'sqlite_sequence') {
                continue;
            }
            Schema::dropIfExists($table->name);
        }

        // Create users table with all columns in correct order
        Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('hashed_email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('shapes_auth_token')->nullable();
            $table->boolean('logout')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create other necessary tables here...
        // Add any other tables your tests need

        // Re-enable foreign key constraints
        DB::statement('PRAGMA foreign_keys = ON');

        $this->info('Testing database migrated successfully.');
    }
}
