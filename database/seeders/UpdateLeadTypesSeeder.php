<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateLeadTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'name' => 'Hot Lead',
                'description' => 'Highly engaged; requested a demo or price quote immediately.',
                'priority' => 'High',
                'crm_action' => 'Immediate callback (within 5-15 mins).',
            ],
            [
                'name' => 'Warm Lead',
                'description' => 'Has interacted with content (downloaded a PDF, attended a webinar).',
                'priority' => 'Medium',
                'crm_action' => 'Add to a 3-day follow-up sequence.',
            ],
            [
                'name' => 'Cold Lead',
                'description' => 'Fits your profile but has had zero interaction (outbound/prospecting).',
                'priority' => 'Low',
                'crm_action' => 'Long-term email nurture or "drip" campaign.',
            ],
        ];

        // Clear existing types to reset state matching the requirement
        // Disable foreign key checks to allow truncation despite existing leads
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lead_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($types as $type) {
            DB::table('lead_types')->insert($type);
        }
    }
}
