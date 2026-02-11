<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KTDSystemSeeder extends Seeder
{
    public function run()
    {
        // 1. Define KTD Roles
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
            ['name' => 'CEO/Executive', 'permission_type' => 'all',    'permissions' => null],
            ['name' => 'Sales Head',    'permission_type' => 'custom', 'permissions' => $permissions],
            ['name' => 'Sales Manager', 'permission_type' => 'custom', 'permissions' => $permissions],
            ['name' => 'Sales Staff',   'permission_type' => 'custom', 'permissions' => $permissions],
            ['name' => 'HR',            'permission_type' => 'custom', 'permissions' => $permissions],
            ['name' => 'Admin',         'permission_type' => 'custom', 'permissions' => $permissions],
        ];

        foreach ($roles as $roleData) {
            // Check if role already exists
            $roleId = DB::table('roles')->where('name', $roleData['name'])->value('id');

            if (! $roleId) {
                $roleId = DB::table('roles')->insertGetId([
                    'name'            => $roleData['name'],
                    'description'     => $roleData['name'] . " role for KTD operations",
                    'permission_type' => $roleData['permission_type'],
                    'permissions'     => isset($roleData['permissions']) ? json_encode($roleData['permissions']) : null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            } else {
                // Update existing role permissions if necessary
                DB::table('roles')->where('id', $roleId)->update([
                    'permission_type' => $roleData['permission_type'],
                    'permissions'     => isset($roleData['permissions']) ? json_encode($roleData['permissions']) : null,
                    'updated_at'      => now(),
                ]);
            }

            // 2. Create 1 User per Role for Testing
            // Note: CEO/Executive -> ceo-executive
            $email = str_replace(['/', ' '], '-', strtolower($roleData['name'])) . "@alamiaconnect.com";
            
            // Check if user already exists
            $userExists = DB::table('users')->where('email', $email)->exists();

            if (! $userExists) {
                DB::table('users')->insert([
                    'name'       => "Test " . $roleData['name'],
                    'email'      => $email,
                    'password'   => Hash::make('password123'),
                    'role_id'    => $roleId,
                    'status'     => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 3. Seed KTD Machine Sales Pipeline
        $salesPipelineName = 'Heavy Machinery Sales';
        $salesPipelineId = DB::table('lead_pipelines')->where('name', $salesPipelineName)->value('id');

        if (! $salesPipelineId) {
            $salesPipelineId = DB::table('lead_pipelines')->insertGetId([
                'name'       => $salesPipelineName,
                'is_default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $salesStages = [
            ['name' => 'Inquiry Received', 'probability' => 10],
            ['name' => 'Technical Specs Alignment', 'probability' => 30],
            ['name' => 'Quotation & Import Costing', 'probability' => 50],
            ['name' => 'LC/Payment Confirmed', 'probability' => 80],
            ['name' => 'Customs & Logistics', 'probability' => 90],
            ['name' => 'Delivered & Commissioned', 'probability' => 100],
        ];

        foreach ($salesStages as $index => $stage) {
            $stageExists = DB::table('lead_pipeline_stages')
                ->where('name', $stage['name'])
                ->where('lead_pipeline_id', $salesPipelineId)
                ->exists();

            if (! $stageExists) {
                DB::table('lead_pipeline_stages')->insert([
                    'name'             => $stage['name'],
                    'probability'      => $stage['probability'],
                    'sort_order'       => $index + 1,
                    'lead_pipeline_id' => $salesPipelineId,
                ]);
            }
        }

        // 4. Seed Post-Sale/Maintenance Pipeline
        $supportPipelineName = 'After-Sales & Maintenance';
        $supportPipelineId = DB::table('lead_pipelines')->where('name', $supportPipelineName)->value('id');

        if (! $supportPipelineId) {
            $supportPipelineId = DB::table('lead_pipelines')->insertGetId([
                'name'       => $supportPipelineName,
                'is_default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $supportStages = ['Warranty Period', 'Scheduled Maintenance', 'Repair Ticket', 'Parts Replacement', 'Case Closed'];
        foreach ($supportStages as $index => $name) {
            $stageExists = DB::table('lead_pipeline_stages')
                ->where('name', $name)
                ->where('lead_pipeline_id', $supportPipelineId)
                ->exists();

            if (! $stageExists) {
                DB::table('lead_pipeline_stages')->insert([
                    'name'             => $name,
                    'probability'      => 100,
                    'sort_order'       => $index + 1,
                    'lead_pipeline_id' => $supportPipelineId,
                ]);
            }
        }

        // 5. Seed KTD Lead Types
        $leadTypes = [
            'Machine Purchase', 
            'Maintenance & Repair', 
            'Spare Parts Inquiry', 
            'Industrial Consultation'
        ];

        foreach ($leadTypes as $typeName) {
            $typeExists = DB::table('lead_types')->where('name', $typeName)->exists();

            if (! $typeExists) {
                DB::table('lead_types')->insert([
                    'name'       => $typeName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 6. Seed KTD Lead Sources
        $leadSources = [
            'Email',
            'Whatsapp',
            'Trade Show', 
            'Manufacturer Referral', 
            'Direct Inbound', 
            'Existing Customer',
            'Cold Outreach'
        ];

        foreach ($leadSources as $sourceName) {
            $sourceExists = DB::table('lead_sources')->where('name', $sourceName)->exists();

            if (! $sourceExists) {
                DB::table('lead_sources')->insert([
                    'name'       => $sourceName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}