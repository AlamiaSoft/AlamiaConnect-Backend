<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Sam\Installer\Database\Seeders\DatabaseSeeder as SamDatabaseSeeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SamDatabaseSeeder::class,
            RoleSeeder::class,
        ]);
        
        // Ensure legacy manual creation doesn't conflict or is removed if covered by RoleSeeder
    }
}
