<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\SalesPerson;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSalesPersons();
        $this->seedDrivers();
        $this->seedVehicles();
        $this->seedCustomers();
    }

    private function seedSalesPersons(): void
    {
        $sales1 = User::where('email', 'sales1@sinarwardana.com')->first();
        $sales2 = User::where('email', 'sales2@sinarwardana.com')->first();

        SalesPerson::firstOrCreate(
            ['code' => 'SLS-0001'],
            [
                'user_id' => $sales1?->id,
                'code' => 'SLS-0001',
                'name' => 'Ahmad Salesman',
                'phone' => '081234567895',
                'email' => 'sales1@sinarwardana.com',
                'area' => 'Jakarta Timur',
                'commission_rate' => 2.50,
                'is_active' => true,
            ]
        );

        SalesPerson::firstOrCreate(
            ['code' => 'SLS-0002'],
            [
                'user_id' => $sales2?->id,
                'code' => 'SLS-0002',
                'name' => 'Budi Salesman',
                'phone' => '081234567896',
                'email' => 'sales2@sinarwardana.com',
                'area' => 'Jakarta Selatan',
                'commission_rate' => 2.50,
                'is_active' => true,
            ]
        );
    }

    private function seedDrivers(): void
    {
        $driverUser = User::where('email', 'driver@sinarwardana.com')->first();

        Driver::firstOrCreate(
            ['license_number' => 'SIM-A-123456'],
            [
                'user_id' => $driverUser?->id,
                'name' => 'Supir Satu',
                'phone' => '081234567897',
                'license_number' => 'SIM-A-123456',
                'address' => 'Jl. Sopir No. 1',
                'is_active' => true,
            ]
        );

        Driver::firstOrCreate(
            ['license_number' => 'SIM-A-789012'],
            [
                'name' => 'Supir Dua',
                'phone' => '081234567801',
                'license_number' => 'SIM-A-789012',
                'address' => 'Jl. Sopir No. 2',
                'is_active' => true,
            ]
        );
    }

    private function seedVehicles(): void
    {
        $vehicles = [
            [
                'plate_number' => 'B 1234 SW',
                'type' => 'Pickup',
                'brand' => 'Mitsubishi',
                'model' => 'L300',
                'capacity' => '1.5 Ton',
                'color' => 'Hitam',
                'is_active' => true,
            ],
            [
                'plate_number' => 'B 5678 SW',
                'type' => 'Truk',
                'brand' => 'Mitsubishi',
                'model' => 'Colt Diesel',
                'capacity' => '4 Ton',
                'color' => 'Putih',
                'is_active' => true,
            ],
            [
                'plate_number' => 'B 9012 SW',
                'type' => 'Pickup',
                'brand' => 'Suzuki',
                'model' => 'Carry',
                'capacity' => '1 Ton',
                'color' => 'Silver',
                'is_active' => true,
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::firstOrCreate(
                ['plate_number' => $vehicle['plate_number']],
                $vehicle
            );
        }
    }

    private function seedCustomers(): void
    {
        $salesPerson1 = SalesPerson::where('code', 'SLS-0001')->first();
        $salesPerson2 = SalesPerson::where('code', 'SLS-0002')->first();
        $pelanggan1 = User::where('email', 'toko.makmur@gmail.com')->first();
        $pelanggan2 = User::where('email', 'toko.sejahtera@gmail.com')->first();

        $customers = [
            [
                'user_id' => $pelanggan1?->id,
                'code' => 'CUST-0001',
                'store_name' => 'Toko Makmur Jaya',
                'owner_name' => 'Haji Makmur',
                'phone' => '081234567899',
                'email' => 'toko.makmur@gmail.com',
                'address' => 'Jl. Pasar Baru No. 15, Kel. Makmur',
                'city' => 'Jakarta Timur',
                'province' => 'DKI Jakarta',
                'latitude' => -6.2145320,
                'longitude' => 106.8451230,
                'area' => 'Jakarta Timur',
                'sales_person_id' => $salesPerson1?->id,
                'credit_limit' => 50000000.00,
                'payment_term_days' => 14,
                'customer_type' => 'agen',
                'is_active' => true,
            ],
            [
                'user_id' => $pelanggan2?->id,
                'code' => 'CUST-0002',
                'store_name' => 'Toko Sejahtera',
                'owner_name' => 'Bu Sejahtera',
                'phone' => '081234567800',
                'email' => 'toko.sejahtera@gmail.com',
                'address' => 'Jl. Raya Selatan No. 45',
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'latitude' => -6.2876540,
                'longitude' => 106.7954320,
                'area' => 'Jakarta Selatan',
                'sales_person_id' => $salesPerson2?->id,
                'credit_limit' => 30000000.00,
                'payment_term_days' => 7,
                'customer_type' => 'retail',
                'is_active' => true,
            ],
            [
                'code' => 'CUST-0003',
                'store_name' => 'Grosir Abadi',
                'owner_name' => 'Pak Abadi',
                'phone' => '081234567802',
                'address' => 'Jl. Grosir No. 8',
                'city' => 'Bekasi',
                'province' => 'Jawa Barat',
                'area' => 'Jakarta Timur',
                'sales_person_id' => $salesPerson1?->id,
                'credit_limit' => 75000000.00,
                'payment_term_days' => 21,
                'customer_type' => 'distributor',
                'is_active' => true,
            ],
            [
                'code' => 'CUST-0004',
                'store_name' => 'Toko Berkah',
                'owner_name' => 'Hj. Berkah',
                'phone' => '081234567803',
                'address' => 'Jl. Berkah No. 12',
                'city' => 'Depok',
                'province' => 'Jawa Barat',
                'area' => 'Jakarta Selatan',
                'sales_person_id' => $salesPerson2?->id,
                'credit_limit' => 25000000.00,
                'payment_term_days' => 7,
                'customer_type' => 'retail',
                'is_active' => true,
            ],
            [
                'code' => 'CUST-0005',
                'store_name' => 'Agen Maju Bersama',
                'owner_name' => 'Pak Maju',
                'phone' => '081234567804',
                'address' => 'Jl. Maju No. 20',
                'city' => 'Tangerang',
                'province' => 'Banten',
                'area' => 'Jakarta Selatan',
                'sales_person_id' => $salesPerson2?->id,
                'credit_limit' => 60000000.00,
                'payment_term_days' => 14,
                'customer_type' => 'agen',
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(
                ['code' => $customer['code']],
                $customer
            );
        }
    }
}
