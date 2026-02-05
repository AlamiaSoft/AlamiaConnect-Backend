<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Webkul\User\Models\Group;
use Webkul\User\Models\Role;
use Webkul\User\Models\User;

class KtdRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $managerPermissions = [
            'dashboard', 'leads', 'leads.create', 'leads.view', 'leads.edit', 'leads.delete', 'leads.export', 'quotes',
            'quotes.create', 'quotes.edit', 'quotes.print', 'quotes.delete', 'mail', 'mail.inbox',
            'mail.draft', 'mail.outbox', 'mail.sent', 'mail.trash', 'mail.compose', 'mail.view',
            'mail.edit', 'mail.delete', 'activities', 'activities.create', 'activities.edit',
            'activities.delete', 'contacts', 'contacts.persons', 'contacts.persons.create',
            'contacts.persons.edit', 'contacts.persons.delete', 'contacts.persons.view',
            'contacts.organizations', 'contacts.organizations.create', 'contacts.organizations.edit',
            'contacts.organizations.delete', 'products', 'products.create', 'products.edit',
            'products.delete', 'products.view', 'settings', 'settings.other_settings',
            'settings.other_settings.tags', 'settings.other_settings.tags.create',
            'settings.other_settings.tags.edit', 'settings.other_settings.tags.delete',
            'settings.data_transfer', 'settings.data_transfer.imports',
            'settings.data_transfer.imports.create', 'settings.data_transfer.imports.edit',
            'settings.data_transfer.imports.delete', 'settings.data_transfer.imports.import',
        ];

        $executivePermissions = array_diff($managerPermissions, ['leads.delete', 'leads.export', 'quotes.delete']);

        $roles = [
            [
                'name'            => 'CEO/Executive',
                'description'     => 'KTD CEO and Executive team with full access.',
                'permission_type' => 'all',
                'access_level'    => 'Global',
                'permissions'     => [],
            ],
            [
                'name'            => 'Regional Sales Manager',
                'description'     => 'Manages sales operations in a specific region. Can see group data.',
                'permission_type' => 'custom',
                'access_level'    => 'Group',
                'permissions'     => $managerPermissions,
            ],
            [
                'name'            => 'Regional Sales Executive',
                'description'     => 'Handles daily sales activities. Can see own data.',
                'permission_type' => 'custom',
                'access_level'    => 'Individual',
                'permissions'     => $executivePermissions,
            ],
            [
                'name'            => 'Service Engineer',
                'description'     => 'Handles technical specifications and maintenance.',
                'permission_type' => 'custom',
                'access_level'    => 'Individual',
                'permissions'     => $executivePermissions,
            ],
            [
                'name'            => 'Support Staff',
                'description'     => 'Assists with documentation and logistical support.',
                'permission_type' => 'custom',
                'access_level'    => 'Individual',
                'permissions'     => $executivePermissions,
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::updateOrCreate(
                ['name' => $roleData['name']],
                [
                    'description'     => $roleData['description'],
                    'permission_type' => $roleData['permission_type'],
                    'access_level'    => $roleData['access_level'],
                    'permissions'     => $roleData['permissions'],
                ]
            );

            // Access levels mapping to Krayin view_permission
            $viewPermissionMap = [
                'Global'     => 'global',
                'Group'      => 'group',
                'Individual' => 'individual',
            ];

            // Create test user for each role across all regions where applicable
            $regions = Group::whereIn('name', ['Karachi_HQ', 'Lahore_RO', 'Islamabad_RO'])->get();

            if ($roleData['access_level'] == 'Global') {
                $email = strtolower(str_replace(['/', ' '], '.', $roleData['name'])).'@alamiaconnect.com';
                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'            => 'Test '.$roleData['name'],
                        'password'        => Hash::make('password123'),
                        'role_id'         => $role->id,
                        'status'          => 1,
                        'view_permission' => $viewPermissionMap[$roleData['access_level']],
                    ]
                );
                // CEO belongs to all groups for visibility if needed, or none if global
                $user->groups()->sync($regions->pluck('id'));
            } else {
                foreach ($regions as $region) {
                    $regionSuffix = explode('_', $region->name)[0];
                    $email = strtolower(str_replace(['/', ' '], '.', $roleData['name'])).'.'.strtolower($regionSuffix).'@alamiaconnect.com';

                    $user = User::updateOrCreate(
                        ['email' => $email],
                        [
                            'name'            => "Test {$roleData['name']} ({$regionSuffix})",
                            'password'        => Hash::make('password123'),
                            'role_id'         => $role->id,
                            'status'          => 1,
                            'view_permission' => $viewPermissionMap[$roleData['access_level']],
                        ]
                    );
                    $user->groups()->sync([$region->id]);
                }
            }
        }
    }
}
