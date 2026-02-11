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
        // 1. Create Root Admin Role (Hidden)
        $rootRole = Role::updateOrCreate(
            ['name' => 'Root Admin'],
            [
                'description'     => 'System Root Administrator (Hidden)',
                'permission_type' => 'all',
                'permissions'     => [],
            ]
        );

        // 2. Create Administrator Role (Restricted)
        $adminRole = Role::updateOrCreate(
            ['name' => 'Administrator'],
            [
                'description'     => 'Administrator with comprehensive permissions',
                'permission_type' => 'custom',
                'permissions'     => [
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
                ],
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
