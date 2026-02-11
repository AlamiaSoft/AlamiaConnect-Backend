<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
/**
 * System Seeder for KTD requirements and a fresh clean start point
 * How to run?
 * php artisan db:seed --class=KtdSetupSeeder
 */
class KtdSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            KtdGroupSeeder::class,
            KtdRoleSeeder::class,
            KtdAttributeSeeder::class,
            KtdPipelineSeeder::class,
            KtdWorkflowSeeder::class,
            KtdProductSeeder::class,
            KtdTestLeadSeeder::class,
        ]);

        $this->command->info('KTD CRM Structure Initialized Successfully!');
    }
}
