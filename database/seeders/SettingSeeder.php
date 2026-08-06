<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Company Settings
            ['group' => 'company', 'key' => 'company_name', 'value' => 'Sinar Wardana', 'type' => 'string', 'description' => 'Nama Perusahaan'],
            ['group' => 'company', 'key' => 'company_address', 'value' => 'Jl. Raya Utama No. 1, Jakarta', 'type' => 'string', 'description' => 'Alamat Perusahaan'],
            ['group' => 'company', 'key' => 'company_phone', 'value' => '021-12345678', 'type' => 'string', 'description' => 'Telepon Perusahaan'],
            ['group' => 'company', 'key' => 'company_email', 'value' => 'info@sinarwardana.com', 'type' => 'string', 'description' => 'Email Perusahaan'],
            ['group' => 'company', 'key' => 'company_npwp', 'value' => '01.234.567.8-901.000', 'type' => 'string', 'description' => 'NPWP Perusahaan'],
            ['group' => 'company', 'key' => 'company_logo', 'value' => null, 'type' => 'string', 'description' => 'Logo Perusahaan'],

            // Invoice Settings
            ['group' => 'invoice', 'key' => 'invoice_prefix', 'value' => 'INV', 'type' => 'string', 'description' => 'Prefix Nomor Invoice'],
            ['group' => 'invoice', 'key' => 'po_prefix', 'value' => 'PO', 'type' => 'string', 'description' => 'Prefix Nomor PO'],
            ['group' => 'invoice', 'key' => 'so_prefix', 'value' => 'SO', 'type' => 'string', 'description' => 'Prefix Nomor Sales Order'],
            ['group' => 'invoice', 'key' => 'sj_prefix', 'value' => 'SJ', 'type' => 'string', 'description' => 'Prefix Nomor Surat Jalan'],
            ['group' => 'invoice', 'key' => 'default_payment_term', 'value' => '14', 'type' => 'integer', 'description' => 'Default Jatuh Tempo (hari)'],
            ['group' => 'invoice', 'key' => 'tax_rate', 'value' => '11', 'type' => 'integer', 'description' => 'Tarif PPN (%)'],

            // Stock Settings
            ['group' => 'stock', 'key' => 'low_stock_threshold', 'value' => '10', 'type' => 'integer', 'description' => 'Batas Stok Minimum Default'],
            ['group' => 'stock', 'key' => 'enable_batch_tracking', 'value' => '1', 'type' => 'boolean', 'description' => 'Aktifkan Tracking Batch'],
            ['group' => 'stock', 'key' => 'enable_expiry_tracking', 'value' => '0', 'type' => 'boolean', 'description' => 'Aktifkan Tracking Kadaluarsa'],

            // Notification Settings
            ['group' => 'notification', 'key' => 'notify_low_stock', 'value' => '1', 'type' => 'boolean', 'description' => 'Notifikasi Stok Menipis'],
            ['group' => 'notification', 'key' => 'notify_new_order', 'value' => '1', 'type' => 'boolean', 'description' => 'Notifikasi Order Baru'],
            ['group' => 'notification', 'key' => 'notify_overdue_receivable', 'value' => '1', 'type' => 'boolean', 'description' => 'Notifikasi Piutang Jatuh Tempo'],
            ['group' => 'notification', 'key' => 'overdue_reminder_days', 'value' => '3', 'type' => 'integer', 'description' => 'Reminder H- Jatuh Tempo (hari)'],

            // Portal Settings
            ['group' => 'portal', 'key' => 'enable_portal', 'value' => '1', 'type' => 'boolean', 'description' => 'Aktifkan Portal Pelanggan'],
            ['group' => 'portal', 'key' => 'enable_online_order', 'value' => '1', 'type' => 'boolean', 'description' => 'Aktifkan Order Online'],
            ['group' => 'portal', 'key' => 'show_stock_status', 'value' => '1', 'type' => 'boolean', 'description' => 'Tampilkan Status Stok di Portal'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                $setting
            );
        }
    }
}
