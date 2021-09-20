<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserRoleLkpTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UserRolesTableSeeder::class);
        $this->call(ResourceStatusLkpTableSeeder::class);
        $this->call(ContentLanguageLkpSeeder::class);
        $this->call(ResourceTypeLkpTableSeeder::class);
        $this->call(ProduceAPITokenForDefaultAdmin::class);
    }
}
