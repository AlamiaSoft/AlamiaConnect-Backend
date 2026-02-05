<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Automation\Models\Workflow;
use Webkul\Lead\Models\Stage;

class KtdWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stage = Stage::where('name', 'CEO Rate Approval')->first();

        if (! $stage) {
            return;
        }

        Workflow::updateOrCreate(
            ['name' => 'KTD CEO Rate Approval Notification'],
            [
                'description'    => 'Notifies CEO and adds a tag when a lead reaches the CEO Rate Approval stage.',
                'entity_type'    => 'leads',
                'event'          => 'lead.update.after',
                'condition_type' => 'and',
                'conditions'     => [
                    [
                        'attribute'      => 'lead_pipeline_stage_id',
                        'attribute_type' => 'lookup',
                        'operator'       => '==',
                        'value'          => $stage->id,
                    ],
                ],
                'actions'        => [
                    [
                        'id'    => 'add_tag',
                        'value' => 'CEO Approval Required',
                    ],
                    [
                        'id'    => 'add_note_as_activity',
                        'value' => 'Lead moved to CEO Rate Approval stage. High-priority review required.',
                    ],
                ],
            ]
        );
    }
}
