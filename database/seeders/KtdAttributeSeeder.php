<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;

class KtdAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entityTypes = ['leads', 'products', 'persons', 'organizations'];

        foreach ($entityTypes as $entityType) {
            $attribute = Attribute::updateOrCreate(
                [
                    'code'        => 'region',
                    'entity_type' => $entityType,
                ],
                [
                    'name'            => 'Region',
                    'type'            => 'select',
                    'is_required'     => 1,
                    'is_user_defined' => 1,
                    'quick_add'       => 1,
                ]
            );

            $options = [
                ['name' => 'Karachi', 'sort_order' => 1],
                ['name' => 'Lahore', 'sort_order' => 2],
                ['name' => 'Islamabad', 'sort_order' => 3],
            ];

            foreach ($options as $optionData) {
                AttributeOption::updateOrCreate(
                    [
                        'attribute_id' => $attribute->id,
                        'name'         => $optionData['name'],
                    ],
                    [
                        'sort_order' => $optionData['sort_order'],
                    ]
                );
            }
        }
    }
}
