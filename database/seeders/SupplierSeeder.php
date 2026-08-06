<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT. Indofood Sukses Makmur',
                'code' => 'SUP-0001',
                'pic' => 'Budi Santoso',
                'phone' => '021-5795-8822',
                'email' => 'order@indofood.co.id',
                'npwp' => '01.234.567.8-901.000',
                'address' => 'Sudirman Plaza, Indofood Tower Lt. 27',
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'is_active' => true,
            ],
            [
                'name' => 'PT. Unilever Indonesia',
                'code' => 'SUP-0002',
                'pic' => 'Siti Rahmawati',
                'phone' => '021-526-2112',
                'email' => 'procurement@unilever.co.id',
                'npwp' => '01.345.678.9-012.000',
                'address' => 'Grha Unilever, BSD Green Office Park',
                'city' => 'Tangerang Selatan',
                'province' => 'Banten',
                'is_active' => true,
            ],
            [
                'name' => 'PT. Sinar Mas Agro Resources',
                'code' => 'SUP-0003',
                'pic' => 'Hendra Wijaya',
                'phone' => '021-6922-555',
                'email' => 'sales@sinarmas.com',
                'npwp' => '01.456.789.0-123.000',
                'address' => 'Plaza BII Tower 2 Lt. 30',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'is_active' => true,
            ],
            [
                'name' => 'PT. Wings Surya',
                'code' => 'SUP-0004',
                'pic' => 'Andi Pratama',
                'phone' => '031-822-6666',
                'email' => 'order@wings.co.id',
                'npwp' => '01.567.890.1-234.000',
                'address' => 'Jl. Kalisosok Kidul No. 2',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'is_active' => true,
            ],
            [
                'name' => 'PT. Mayora Indah',
                'code' => 'SUP-0005',
                'pic' => 'Diana Putri',
                'phone' => '021-5890-8888',
                'email' => 'sales@mayora.co.id',
                'npwp' => '01.678.901.2-345.000',
                'address' => 'Gedung Mayora Lt. 8',
                'city' => 'Jakarta Barat',
                'province' => 'DKI Jakarta',
                'is_active' => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['code' => $supplier['code']],
                $supplier
            );
        }
    }
}
