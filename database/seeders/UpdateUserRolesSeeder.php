<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'description' => 'Full control. Only for the business owner or IT head.',
                'access_level' => 'Global',
                'permission_type' => 'all',
                'permissions' => json_encode([]),
            ],
            [
                'name' => 'Sales Manager',
                'description' => 'Can see all team leads, edit quotas, and reassign deals.',
                'access_level' => 'Departmental',
                'permission_type' => 'custom',
                'permissions' => json_encode([]),
            ],
            [
                'name' => 'Sales Rep',
                'description' => 'Can only see/edit leads they "Own." Cannot export data.',
                'access_level' => 'Individual',
                'permission_type' => 'custom',
                'permissions' => json_encode([]),
            ],
            [
                'name' => 'Finance/Accounts',
                'description' => 'Read-only for deals; Full-access for Invoices/Payments.',
                'access_level' => 'Functional',
                'permission_type' => 'custom',
                'permissions' => json_encode([]),
            ],
            [
                'name' => 'Viewer/Auditor',
                'description' => 'Can see reports and dashboards but cannot change any data.',
                'access_level' => 'Read-Only',
                'permission_type' => 'custom',
                'permissions' => json_encode([]),
            ],
            [
                'name' => 'Tech Engineer',
                'description' => 'Manage service tickets and technical implementations.',
                'access_level' => 'Technical',
                'permission_type' => 'custom',
                'permissions' => json_encode([]),
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']], 
                $role
            );
        }
    }
}
