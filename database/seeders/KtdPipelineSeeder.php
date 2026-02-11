<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Lead\Models\Pipeline;
use Webkul\Lead\Models\Stage;

class KtdPipelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Heavy Machinery Sales Pipeline
        $pipeline = Pipeline::updateOrCreate(
            ['name' => 'KTD Heavy Machinery Sales'],
            ['is_default' => 0]
        );

        $stages = [
            ['name' => 'Inquiry Received',          'probability' => 10,  'sort_order' => 1],
            ['name' => 'Technical Specs Alignment', 'probability' => 30,  'sort_order' => 2],
            ['name' => 'CEO Rate Approval',         'probability' => 50,  'sort_order' => 3],
            ['name' => 'Proforma Issued',           'probability' => 70,  'sort_order' => 4],
            ['name' => 'Payment Confirmed',         'probability' => 90,  'sort_order' => 5],
            ['name' => 'Delivered & Commissioned',  'probability' => 100, 'sort_order' => 6],
        ];

        foreach ($stages as $stageData) {
            Stage::updateOrCreate(
                [
                    'name'             => $stageData['name'],
                    'lead_pipeline_id' => $pipeline->id,
                ],
                [
                    'probability' => $stageData['probability'],
                    'sort_order'  => $stageData['sort_order'],
                ]
            );
        }

        // 2. After-Sales & Maintenance Pipeline
        $supportPipeline = Pipeline::updateOrCreate(
            ['name' => 'KTD After-Sales & Maintenance'],
            ['is_default' => 0]
        );

        $supportStages = [
            ['name' => 'Warranty Period',        'probability' => 100, 'sort_order' => 1],
            ['name' => 'Scheduled Maintenance',  'probability' => 100, 'sort_order' => 2],
            ['name' => 'Repair Ticket',           'probability' => 100, 'sort_order' => 3],
            ['name' => 'Parts Replacement',      'probability' => 100, 'sort_order' => 4],
            ['name' => 'Case Closed',            'probability' => 100, 'sort_order' => 5],
        ];

        foreach ($supportStages as $stageData) {
            Stage::updateOrCreate(
                [
                    'name'             => $stageData['name'],
                    'lead_pipeline_id' => $supportPipeline->id,
                ],
                [
                    'probability' => $stageData['probability'],
                    'sort_order'  => $stageData['sort_order'],
                ]
            );
        }

        // 3. Lead Types
        $leadTypes = [
            'Machine Purchase', 
            'Maintenance & Repair', 
            'Spare Parts Inquiry', 
            'Industrial Consultation'
        ];

        foreach ($leadTypes as $typeName) {
            \Webkul\Lead\Models\Type::updateOrCreate(['name' => $typeName]);
        }

        // 4. Lead Sources
        $leadSources = [
            'Trade Show', 
            'Manufacturer Referral', 
            'Direct Inbound', 
            'Existing Customer',
            'Cold Outreach'
        ];

        foreach ($leadSources as $sourceName) {
            \Webkul\Lead\Models\Source::updateOrCreate(['name' => $sourceName]);
        }
    }
}
