<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Gudang Utama Sinar Wardana',
                'code' => 'GDG-001',
                'type' => 'utama',
                'address' => 'Jl. Raya Utama No. 1',
                'phone' => '021-12345678',
                'pic' => 'Admin Gudang',
                'is_active' => true,
            ],
            [
                'name' => 'Gudang Transit',
                'code' => 'GDG-002',
                'type' => 'transit',
                'address' => 'Jl. Transit No. 2',
                'phone' => '021-12345679',
                'pic' => 'Staff Transit',
                'is_active' => true,
            ],
            [
                'name' => 'Gudang Cabang 1',
                'code' => 'GDG-003',
                'type' => 'cabang',
                'address' => 'Jl. Cabang No. 3',
                'phone' => '021-12345680',
                'pic' => 'Staff Cabang',
                'is_active' => true,
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::firstOrCreate(
                ['code' => $warehouse['code']],
                $warehouse
            );
        }
    }
}
