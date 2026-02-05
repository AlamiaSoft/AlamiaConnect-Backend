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
        // Heavy Machinery Sales Pipeline
        $pipeline = Pipeline::updateOrCreate(
            ['name' => 'KTD Heavy Machinery Sales'],
            [
                'is_default' => 0,
            ]
        );

        $stages = [
            ['name' => 'Inquiry Received', 'probability' => 10, 'sort_order' => 1],
            ['name' => 'Technical Specs Alignment', 'probability' => 30, 'sort_order' => 2],
            ['name' => 'CEO Rate Approval', 'probability' => 50, 'sort_order' => 3],
            ['name' => 'Proforma Issued', 'probability' => 70, 'sort_order' => 4],
            ['name' => 'Payment Confirmed', 'probability' => 90, 'sort_order' => 5],
            ['name' => 'Delivered & Commissioned', 'probability' => 100, 'sort_order' => 6],
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
    }
}
