<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Lead\Models\Lead;
use Webkul\Lead\Models\Pipeline;
use Webkul\Lead\Models\Stage;
use Webkul\Lead\Models\Type;
use Webkul\Lead\Models\Source;
use Webkul\User\Models\User;
use Webkul\Contact\Models\Person;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeValue;

class KtdTestLeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pipeline = Pipeline::where('name', 'KTD Heavy Machinery Sales')->first();
        if (! $pipeline) return;

        $stages = Stage::where('lead_pipeline_id', $pipeline->id)->get();
        $firstStage = $stages->where('sort_order', 1)->first();
        
        // Get default type and source to prevent UI null pointer errors
        $type = Type::first();
        $source = Source::first();

        $regionAttribute = Attribute::where(['code' => 'region', 'entity_type' => 'leads'])->first();
        if (! $regionAttribute) return;

        $users = User::where('email', 'like', '%@alamiaconnect.com')->get();

        foreach ($users as $user) {
            $group = $user->groups()->first();
            if (! $group) continue;

            $cityName = explode('_', $group->name)[0];

            // Ensure we have a person
            $person = Person::first();
            if (! $person) {
                $person = Person::create([
                    'name'    => 'Default Test Person',
                    'emails'  => [['value' => 'test@example.com', 'label' => 'work']],
                    'user_id' => $user->id,
                ]);
            }

            // Create a lead for this user with all required relationships
            $lead = Lead::create([
                'title'                  => "Test Lead for {$user->name} in {$cityName}",
                'description'            => "This is a verification lead created for testing regional isolation.",
                'lead_value'             => rand(50000, 500000),
                'status'                 => 1,
                'user_id'                => $user->id,
                'person_id'              => $person->id,
                'lead_source_id'         => $source?->id,
                'lead_type_id'           => $type?->id,
                'lead_pipeline_id'       => $pipeline->id,
                'lead_pipeline_stage_id' => $firstStage->id,
            ]);

            // Set region attribute
            $option = $regionAttribute->options()->where('name', $cityName)->first();
            if ($option) {
                AttributeValue::create([
                    'attribute_id'  => $regionAttribute->id,
                    'integer_value' => $option->id,
                    'entity_id'     => $lead->id,
                    'entity_type'   => 'leads',
                ]);
            }
        }
    }
}
