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
                'name' => 'Root Admin',
                'description' => 'System Root Administrator. Absolute full control.',
                'access_level' => 'Global',
                'permission_type' => 'all',
                'permissions' => json_encode([]),
            ],
            [
                'name' => 'Super Admin',
                'description' => 'Full control except for global system configurations.',
                'access_level' => 'Global',
                'permission_type' => 'custom',
                'permissions' => json_encode([
                    "dashboard", "leads", "leads.create", "leads.view", "leads.edit", "leads.delete", "quotes",
                    "quotes.create", "quotes.edit", "quotes.print", "quotes.delete", "mail", "mail.inbox",
                    "mail.draft", "mail.outbox", "mail.sent", "mail.trash", "mail.compose", "mail.view",
                    "mail.edit", "mail.delete", "activities", "activities.create", "activities.edit",
                    "activities.delete", "contacts", "contacts.persons", "contacts.persons.create",
                    "contacts.persons.edit", "contacts.persons.delete", "contacts.persons.view",
                    "contacts.organizations", "contacts.organizations.create", "contacts.organizations.edit",
                    "contacts.organizations.delete", "products", "products.create", "products.edit",
                    "products.delete", "products.view", "settings", 
                    "settings.user", "settings.user.groups", "settings.user.groups.create", "settings.user.groups.edit", "settings.user.groups.delete",
                    "settings.user.roles", "settings.user.roles.create", "settings.user.roles.edit", "settings.user.roles.delete",
                    "settings.user.users", "settings.user.users.create", "settings.user.users.edit", "settings.user.users.delete",
                    "settings.lead", "settings.lead.pipelines",
                    "settings.lead.pipelines.create", "settings.lead.pipelines.edit", "settings.lead.pipelines.delete",
                    "settings.lead.sources", "settings.lead.sources.create", "settings.lead.sources.edit",
                    "settings.lead.sources.delete", "settings.lead.types", "settings.lead.types.create",
                    "settings.lead.types.edit", "settings.lead.types.delete", 
                    "settings.automation", "settings.automation.attributes", "settings.automation.attributes.create", "settings.automation.attributes.edit", "settings.automation.attributes.delete",
                    "settings.automation.email_templates", "settings.automation.email_templates.create", "settings.automation.email_templates.edit", "settings.automation.email_templates.delete",
                    "settings.automation.workflows", "settings.automation.workflows.create", "settings.automation.workflows.edit", "settings.automation.workflows.delete",
                    "settings.automation.events", "settings.automation.events.create", "settings.automation.events.edit", "settings.automation.events.delete",
                    "settings.automation.campaigns", "settings.automation.campaigns.create", "settings.automation.campaigns.edit", "settings.automation.campaigns.delete",
                    "settings.automation.webhooks", "settings.automation.webhooks.create", "settings.automation.webhooks.edit", "settings.automation.webhooks.delete",
                    "settings.other_settings",
                    "settings.other_settings.web_forms", "settings.other_settings.web_forms.view",
                    "settings.other_settings.web_forms.create", "settings.other_settings.web_forms.edit",
                    "settings.other_settings.web_forms.delete", "settings.other_settings.tags",
                    "settings.other_settings.tags.create", "settings.other_settings.tags.edit",
                    "settings.other_settings.tags.delete", "settings.data_transfer", "settings.data_transfer.imports",
                    "settings.data_transfer.imports.create", "settings.data_transfer.imports.edit",
                    "settings.data_transfer.imports.delete", "settings.data_transfer.imports.import"
                ]),

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
