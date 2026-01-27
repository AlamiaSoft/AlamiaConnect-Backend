<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateDefaultPipelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pipelineId = 1;

        // Ensure the default pipeline exists
        $pipeline = DB::table('lead_pipelines')->where('id', $pipelineId)->first();
        if (!$pipeline) {
             DB::table('lead_pipelines')->insert([
                'id'         => $pipelineId,
                'name'       => 'Default Pipeline',
                'is_default' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Delete existing stages for this pipeline to replace them cleanly
        // Note: In production with real leads, we would need to migrate them.
        // For now, assuming dev/initial setup as per "seed data" request.
        DB::table('lead_pipeline_stages')->where('lead_pipeline_id', $pipelineId)->delete();

        $stages = [
            [
                'name' => 'Qualification',
                'code' => 'qualification',
                'description' => 'A lead has been contacted and meets your ICP (Ideal Customer Profile) and BANT criteria.',
                'probability' => 10,
                'sort_order' => 1,
            ],
            [
                'name' => 'Discovery / Demo',
                'code' => 'discovery',
                'description' => 'A formal meeting or demo has been held to uncover pain points and present value.',
                'probability' => 30,
                'sort_order' => 2,
            ],
            [
                'name' => 'Proposal Sent',
                'code' => 'proposal',
                'description' => 'A formal quote or contract has been delivered to the prospect.',
                'probability' => 50,
                'sort_order' => 3,
            ],
            [
                'name' => 'Negotiation',
                'code' => 'negotiation',
                'description' => 'The prospect is reviewing terms, legal, or pricing adjustments.',
                'probability' => 75,
                'sort_order' => 4,
            ],
            [
                'name' => 'Verbal Agreement',
                'code' => 'verbal',
                'description' => 'The prospect has said "yes" but the paperwork is not yet signed.',
                'probability' => 90,
                'sort_order' => 5,
            ],
            [
                'name' => 'Closed Won',
                'code' => 'won',
                'description' => 'The contract is signed and payment/onboarding has begun.',
                'probability' => 100,
                'sort_order' => 6,
            ],
            [
                'name' => 'Closed Lost',
                'code' => 'lost',
                'description' => 'The deal is no longer active (archive for future nurturing).',
                'probability' => 0,
                'sort_order' => 7,
            ],
        ];

        foreach ($stages as $stage) {
            DB::table('lead_pipeline_stages')->insert([
                'lead_pipeline_id' => $pipelineId,
                'code' => $stage['code'],
                'name' => $stage['name'],
                'description' => $stage['description'],
                'probability' => $stage['probability'],
                'sort_order' => $stage['sort_order'],
            ]);
        }
    }
}
