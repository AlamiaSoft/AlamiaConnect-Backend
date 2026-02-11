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
        $permissions = [
            "dashboard", "leads", "leads.create", "leads.view", "leads.edit", "leads.delete", "quotes",
            "quotes.create", "quotes.edit", "quotes.print", "quotes.delete", "mail", "mail.inbox",
            "mail.draft", "mail.outbox", "mail.sent", "mail.trash", "mail.compose", "mail.view",
            "mail.edit", "mail.delete", "activities", "activities.create", "activities.edit",
            "activities.delete", "contacts", "contacts.persons", "contacts.persons.create",
            "contacts.persons.edit", "contacts.persons.delete", "contacts.persons.view",
            "contacts.organizations", "contacts.organizations.create", "contacts.organizations.edit",
            "contacts.organizations.delete", "products", "products.create", "products.edit",
            "products.delete", "products.view", "settings", "settings.lead", "settings.lead.pipelines",
            "settings.lead.pipelines.create", "settings.lead.pipelines.edit", "settings.lead.pipelines.delete",
            "settings.lead.sources", "settings.lead.sources.create", "settings.lead.sources.edit",
            "settings.lead.sources.delete", "settings.lead.types", "settings.lead.types.create",
            "settings.lead.types.edit", "settings.lead.types.delete", "settings.other_settings",
            "settings.other_settings.web_forms", "settings.other_settings.web_forms.view",
            "settings.other_settings.web_forms.create", "settings.other_settings.web_forms.edit",
            "settings.other_settings.web_forms.delete", "settings.other_settings.tags",
            "settings.other_settings.tags.create", "settings.other_settings.tags.edit",
            "settings.other_settings.tags.delete", "settings.data_transfer", "settings.data_transfer.imports",
            "settings.data_transfer.imports.create", "settings.data_transfer.imports.edit",
            "settings.data_transfer.imports.delete", "settings.data_transfer.imports.import"
        ];

        $roles = [
            [
                'name'            => 'Root Admin',
                'description'     => 'System Root Administrator.',
                'permission_type' => 'all',
                'access_level'    => 'Global',
                'permissions'     => [],
            ],
            [
                'name'            => 'Director',
                'description'     => 'KTD Director/Owner with comprehensive system access.',
                'permission_type' => 'custom',
                'access_level'    => 'Global',
                'permissions'     => $permissions,
            ],
            [
                'name'            => 'HR & Admin',
                'description'     => 'Human Resources and Administrative management.',
                'permission_type' => 'custom',
                'access_level'    => 'Global',
                'permissions'     => $permissions,
            ],
            [
                'name'            => 'Accounts',
                'description'     => 'Financial and accounts management.',
                'permission_type' => 'custom',
                'access_level'    => 'Global',
                'permissions'     => $permissions,
            ],
            [
                'name'            => 'Sales Manager',
                'description'     => 'Sales management for a specific branch or region.',
                'permission_type' => 'custom',
                'access_level'    => 'Group',
                'permissions'     => $permissions,
            ],
            [
                'name'            => 'Sales executive',
                'description'     => 'Standard sales operations.',
                'permission_type' => 'custom',
                'access_level'    => 'Individual',
                'permissions'     => $permissions,
            ],
            [
                'name'            => 'Maintenance & Service Manager',
                'description'     => 'Manages technical service and maintenance teams.',
                'permission_type' => 'custom',
                'access_level'    => 'Group',
                'permissions'     => $permissions,
            ],
            [
                'name'            => 'Maintenace and Service Executive',
                'description'     => 'Technical service execution.',
                'permission_type' => 'custom',
                'access_level'    => 'Individual',
                'permissions'     => $permissions,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                [
                    'description'     => $roleData['description'],
                    'permission_type' => $roleData['permission_type'],
                    'access_level'    => $roleData['access_level'],
                    'permissions'     => $roleData['permissions'],
                ]
            );
        }

        $viewPermissionMap = [
            'Global'     => 'global',
            'Group'      => 'group',
            'Individual' => 'individual',
        ];

        // Specific Users Mapping from KTD-Users-List.txt
        $users = [
            // Root Admin
            ['name' => 'Amr Shah', 'email' => 'amr.shah@gmail.com', 'role' => 'Root Admin', 'group' => 'Karachi_HQ'],

            // LHR
            ['name' => 'Umair Zulqarnain', 'role' => 'Sales Manager', 'group' => 'Lahore_RO'],
            ['name' => 'Adeel Bashir', 'role' => 'Sales Manager', 'group' => 'Lahore_RO'],
            ['name' => 'JUNAID Mustafa', 'role' => 'Maintenance & Service Manager', 'group' => 'Lahore_RO'],
            ['name' => 'Usman Subhani', 'role' => 'Maintenace and Service Executive', 'group' => 'Lahore_RO'],
            
            // ISB
            ['name' => 'Syed Amer Raza Gillani', 'role' => 'Sales Manager', 'group' => 'Islamabad_RO'],
            
            // KHI
            ['name' => 'Syed Shariq Hussain', 'role' => 'Director', 'group' => 'Karachi_HQ'],
            ['name' => 'Zeeshan Zafar', 'role' => 'Sales executive', 'group' => 'Karachi_HQ'],
            ['name' => 'Inayat Ullah', 'role' => 'Accounts', 'group' => 'Karachi_HQ'],
            ['name' => 'Amir Qureshi', 'role' => 'Maintenance & Service Manager', 'group' => 'Karachi_HQ'],
            ['name' => 'Muhammad Ameen', 'role' => 'Maintenace and Service Executive', 'group' => 'Karachi_HQ'],
            ['name' => 'Rehman Moeen', 'role' => 'Sales Manager', 'group' => 'Karachi_HQ'],
            ['name' => 'Mujahid Ali Mian', 'role' => 'Sales Manager', 'group' => 'Karachi_HQ'],
            ['name' => 'Raees Mubeen', 'role' => 'Sales executive', 'group' => 'Karachi_HQ'],
            ['name' => 'Muhammad Sami', 'role' => 'Sales executive', 'group' => 'Karachi_HQ'],
            ['name' => 'Abbas Ali', 'role' => 'HR & Admin', 'group' => 'Karachi_HQ'],
        ];

        foreach ($users as $userData) {
            $role = Role::where('name', $userData['role'])->first();
            $group = Group::where('name', $userData['group'])->first();

            if ($role && $group) {
                // Use provided email or generate: username@kausartrade.com
                $email = $userData['email'] ?? (str_replace('-', '.', \Illuminate\Support\Str::slug($userData['name'])) . '@kausartrade.com');

                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'            => $userData['name'],
                        'password'        => Hash::make('password123'),
                        'role_id'         => $role->id,
                        'status'          => 1,
                        'view_permission' => $viewPermissionMap[$role->access_level],
                    ]
                );
                $user->groups()->sync([$group->id]);
            }
        }
    }
}
