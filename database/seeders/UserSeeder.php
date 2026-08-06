<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@sinarwardana.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@sinarwardana.com'],
            [
                'name' => 'Pemilik Sinar Wardana',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $owner->assignRole('owner');

        // Admin Gudang
        $adminGudang = User::firstOrCreate(
            ['email' => 'gudang@sinarwardana.com'],
            [
                'name' => 'Admin Gudang',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $adminGudang->assignRole('admin_gudang');

        // Admin Penjualan
        $adminPenjualan = User::firstOrCreate(
            ['email' => 'penjualan@sinarwardana.com'],
            [
                'name' => 'Admin Penjualan',
                'password' => Hash::make('password'),
                'phone' => '081234567893',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $adminPenjualan->assignRole('admin_penjualan');

        // Purchasing
        $purchasing = User::firstOrCreate(
            ['email' => 'purchasing@sinarwardana.com'],
            [
                'name' => 'Staff Purchasing',
                'password' => Hash::make('password'),
                'phone' => '081234567894',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $purchasing->assignRole('purchasing');

        // Sales 1
        $sales1 = User::firstOrCreate(
            ['email' => 'sales1@sinarwardana.com'],
            [
                'name' => 'Ahmad Salesman',
                'password' => Hash::make('password'),
                'phone' => '081234567895',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $sales1->assignRole('sales');

        // Sales 2
        $sales2 = User::firstOrCreate(
            ['email' => 'sales2@sinarwardana.com'],
            [
                'name' => 'Budi Salesman',
                'password' => Hash::make('password'),
                'phone' => '081234567896',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $sales2->assignRole('sales');

        // Driver
        $driver = User::firstOrCreate(
            ['email' => 'driver@sinarwardana.com'],
            [
                'name' => 'Supir Satu',
                'password' => Hash::make('password'),
                'phone' => '081234567897',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $driver->assignRole('driver');

        // Finance
        $finance = User::firstOrCreate(
            ['email' => 'finance@sinarwardana.com'],
            [
                'name' => 'Staff Keuangan',
                'password' => Hash::make('password'),
                'phone' => '081234567898',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $finance->assignRole('finance');

        // Pelanggan 1
        $pelanggan1 = User::firstOrCreate(
            ['email' => 'toko.makmur@gmail.com'],
            [
                'name' => 'Toko Makmur Jaya',
                'password' => Hash::make('password'),
                'phone' => '081234567899',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $pelanggan1->assignRole('pelanggan');

        // Pelanggan 2
        $pelanggan2 = User::firstOrCreate(
            ['email' => 'toko.sejahtera@gmail.com'],
            [
                'name' => 'Toko Sejahtera',
                'password' => Hash::make('password'),
                'phone' => '081234567800',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $pelanggan2->assignRole('pelanggan');
    }
}
