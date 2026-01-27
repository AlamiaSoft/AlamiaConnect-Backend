<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateLeadSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sources = [
            [
                'name' => 'Inbound',
                'source_examples' => 'Website Form, SEO, Google Ads',
                'best_for' => 'High intent, fast conversion.',
            ],
            [
                'name' => 'Outbound',
                'source_examples' => 'Cold Call, LinkedIn Outreach, Email Blast',
                'best_for' => 'Scaling fast, targeting specific "Big Fish."',
            ],
            [
                'name' => 'Referral',
                'source_examples' => 'Client Referral, Partner, Employee',
                'best_for' => 'Highest trust and closing rate.',
            ],
            [
                'name' => 'Offline',
                'source_examples' => 'Trade Shows, Physical Mailers, Networking',
                'best_for' => 'Building long-term brand presence.',
            ],
        ];

        // Clear existing sources
        // Disable foreign key checks to allow truncation despite existing leads
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lead_sources')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($sources as $source) {
            DB::table('lead_sources')->insert($source);
        }
    }
}
