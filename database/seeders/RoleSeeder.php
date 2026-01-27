<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\User\Models\Role;
use Webkul\User\Models\Admin; // User model is actually Admin in Webkul packages often, but let's check config. 
// Actually DatabaseSeeder uses App\Models\User. Let's stick to what's used there or check config.
// The DatabaseSeeder used App\Models\User.

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create Root Role (Hidden)
        $rootRole = Role::updateOrCreate(
            ['name' => 'Root'],
            [
                'description'     => 'Root access (Hidden)',
                'permission_type' => 'all',
                'permissions'     => [],
            ]
        );

        // 2. Create Administrator Role
        $adminRole = Role::updateOrCreate(
            ['name' => 'Administrator'],
            [
                'description'     => 'Administrator with all permissions',
                'permission_type' => 'all',
                'permissions'     => [],
            ]
        );

        // 3. Create Manager Role
        Role::updateOrCreate(
            ['name' => 'Manager'],
            [
                'description'     => 'Manager with supervised access',
                'permission_type' => 'custom',
                'permissions'     => ['leads', 'contacts', 'mail', 'activities'], // Example permissions
            ]
        );

        // 4. Create Sales Agent Role
        Role::updateOrCreate(
            ['name' => 'Sales Agent'],
            [
                'description'     => 'Sales Agent with limited access',
                'permission_type' => 'custom',
                'permissions'     => ['leads.create', 'leads.edit', 'activities'], // Example permissions
            ]
        );

        // Assign 'amr.shah@gmail.com' to Root
        $user = \App\Models\User::where('email', 'amr.shah@gmail.com')->first();
        
        if ($user) {
            $user->role_id = $rootRole->id;
            $user->save();
        } else {
             // Create if not exists (though DatabaseSeeder creates it, but maybe with wrong email/role)
             \App\Models\User::create([
                'name'     => 'Amr Shah',
                'email'    => 'amr.shah@gmail.com',
                'password' => bcrypt('password'), // strict password policy might differ
                'status'   => 1,
                'role_id'  => $rootRole->id,
            ]);
        }
    }
}
