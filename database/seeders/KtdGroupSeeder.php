<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\User\Models\Group;

class KtdGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            [
                'name'        => 'Karachi_HQ',
                'description' => 'KTD Head Office - Karachi',
            ],
            [
                'name'        => 'Lahore_RO',
                'description' => 'KTD Regional Office - Lahore',
            ],
            [
                'name'        => 'Islamabad_RO',
                'description' => 'KTD Regional Office - Islamabad',
            ],
        ];

        foreach ($groups as $group) {
            Group::updateOrCreate(
                ['name' => $group['name']],
                $group
            );
        }
    }
}
