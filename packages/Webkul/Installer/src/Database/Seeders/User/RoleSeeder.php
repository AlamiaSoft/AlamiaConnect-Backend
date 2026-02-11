<?php

namespace Webkul\Installer\Database\Seeders\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('users')->delete();

        DB::table('roles')->delete();

        $defaultLocale = $parameters['locale'] ?? config('app.locale');

        DB::table('roles')->insert([
            'id'              => 1,
            'name'            => 'Root Admin',
            'description'     => 'System Root Administrator',
            'permission_type' => 'all',
        ]);

        DB::table('roles')->insert([
            'id'              => 2,
            'name'            => trans('installer::app.seeders.user.role.administrator', [], $defaultLocale),
            'description'     => trans('installer::app.seeders.user.role.administrator-role', [], $defaultLocale),
            'permission_type' => 'custom',
            'permissions'     => json_encode([
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
            ]),
        ]);
    }
}
