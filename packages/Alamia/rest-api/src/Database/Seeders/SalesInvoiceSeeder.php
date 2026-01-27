<?php

namespace Alamia\RestApi\Database\Seeders;

use Illuminate\Database\Seeder;
use Alamia\RestApi\Models\SalesInvoice;
use Illuminate\Support\Facades\DB;

class SalesInvoiceSeeder extends Seeder
{
    public function run()
    {
        DB::table('sales_invoices')->delete();
        
        $data = [
            [
                'invoice_number' => 'INV-2024-001',
                'customer_name' => 'Pak Industries Ltd',
                'total_amount' => 2500000,
                'amount_received' => 2500000,
                'status' => 'Released',
                'category' => 'CNC Machine',
                'issued_at' => '2024-01-15'
            ],
            [
                'invoice_number' => 'INV-2024-002',
                'customer_name' => 'Steel Works Pvt',
                'total_amount' => 1800000,
                'amount_received' => 1350000,
                'status' => 'Partial',
                'category' => 'Lathe Machine',
                'issued_at' => '2024-01-20'
            ],
            [
                'invoice_number' => 'INV-2024-003',
                'customer_name' => 'Metro Construction',
                'total_amount' => 3200000,
                'amount_received' => 3200000,
                'status' => 'Pending',
                'category' => 'Heavy Drill',
                'issued_at' => '2024-01-25'
            ],
            [
                'invoice_number' => 'INV-2024-004',
                'customer_name' => 'Textile Mills Co',
                'total_amount' => 950000,
                'amount_received' => 0,
                'status' => 'Pending',
                'category' => 'Industrial Sewing',
                'issued_at' => '2024-02-01'
            ],
            [
                'invoice_number' => 'INV-2024-005',
                'customer_name' => 'Auto Parts Manufacturing',
                'total_amount' => 4100000,
                'amount_received' => 4100000,
                'status' => 'Pending',
                'category' => 'CNC Machine',
                'issued_at' => '2024-02-05'
            ],
        ];

        foreach ($data as $item) {
            SalesInvoice::create($item);
        }
    }
}
