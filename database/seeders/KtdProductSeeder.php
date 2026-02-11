<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Product\Models\Product;

class KtdProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productsData = [
            ['sku' => '4T36/24', 'name' => 'KTD FIBER LASER CUTTING MACHINE C3 BODOR', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4T50/24', 'name' => 'KTD CNC FIBER LASER CUTTING MACHINE A4 3000W', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4751/24', 'name' => 'KTD CNC FIBER LASER CUTTING MACHINE A4 3000W', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4752124', 'name' => 'KTD CNC FIBER LASER CUTTING MACHINE A3 1500W', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4753/24', 'name' => 'KTD CNC FIBER LASER CUTTING MACHINE A3 3000W', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4T54124', 'name' => 'KTD CNC FIBER LASER CUTTING MACHINE A3 3000W', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4756124', 'name' => 'KTD FIBER LASER CUTTING MACHINE', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4T5T124', 'name' => 'KTD FIBER LASER CUTTING MACHINE', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4T58124', 'name' => 'KTD FIBER LASER CUTTING MACHINE (SCAN CUTT!', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4759124', 'name' => 'KTD FIBER LASER CUTTING MACHINE', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4760/24', 'name' => 'KTD FIBER LASER CUTTING MACHINE', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4T61124', 'name' => 'KTD FIBER LASER CUTTING MACHINE', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4762/24', 'name' => 'KTD FIBER LASER CUTTING MACHINE', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4763124', 'name' => 'KTD FIBER LASER CUTTING MACHINE', 'category' => 'MACHINE (NEW)'],
            ['sku' => '4808/25', 'name' => 'KTD FIBER LASER CUTTING MACHINE A3015', 'category' => 'MACHINE (NEW)'],
        ];

        foreach ($productsData as $data) {
            Product::updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'name'        => $data['name'],
                    'description' => $data['category'],
                    'price'       => 0,
                    'quantity'    => 0,
                ]
            );
        }

        $this->command->info('KTD Products Seeded Successfully!');
    }
}
