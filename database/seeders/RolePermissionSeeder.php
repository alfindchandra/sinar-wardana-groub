<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all modules and their CRUD permissions
        $modules = [
            'dashboard',
            'users',
            'roles',
            'categories',
            'products',
            'suppliers',
            'warehouses',
            'customers',
            'purchase_orders',
            'goods_receipts',
            'supplier_returns',
            'stock_movements',
            'stock_batches',
            'stock_opnames',
            'stock_mutations',
            'sales_orders',
            'invoices',
            'deliveries',
            'sales_returns',
            'sales_persons',
            'sales_visits',
            'sales_targets',
            'sales_commissions',
            'drivers',
            'vehicles',
            'receivables',
            'payments',
            'payables',
            'payment_outs',
            'promos',
            'online_orders',
            'reports',
            'activity_logs',
            'settings',
            'notifications',
        ];

        $actions = ['view', 'create', 'update', 'delete'];

        // Create all permissions
        $permissions = [];
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permName = "{$action}_{$module}";
                $permissions[] = $permName;
                Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
            }
        }

        // Additional special permissions
        $specialPermissions = [
            'approve_purchase_orders',
            'approve_stock_opnames',
            'approve_stock_mutations',
            'approve_sales_orders',
            'approve_supplier_returns',
            'approve_sales_returns',
            'export_reports',
            'import_data',
            'manage_settings',
            'view_financial_summary',
            'manage_roles',
            'portal_access',
            'portal_order',
            'portal_view_products',
            'portal_view_invoices',
            'portal_view_receivables',
        ];

        foreach ($specialPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        // 1. Super Admin - all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. Owner - almost all permissions
        $owner = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $owner->givePermissionTo(Permission::all());

        // 3. Admin Gudang
        $adminGudang = Role::firstOrCreate(['name' => 'admin_gudang', 'guard_name' => 'web']);
        $adminGudang->givePermissionTo([
            'view_dashboard',
            'view_categories', 'create_categories', 'update_categories',
            'view_products', 'create_products', 'update_products',
            'view_suppliers',
            'view_warehouses', 'create_warehouses', 'update_warehouses',
            'view_purchase_orders',
            'view_goods_receipts', 'create_goods_receipts', 'update_goods_receipts',
            'view_supplier_returns', 'create_supplier_returns',
            'view_stock_movements', 'create_stock_movements',
            'view_stock_batches', 'create_stock_batches',
            'view_stock_opnames', 'create_stock_opnames', 'update_stock_opnames',
            'view_stock_mutations', 'create_stock_mutations', 'update_stock_mutations',
            'approve_stock_opnames', 'approve_stock_mutations',
            'view_deliveries',
            'view_reports', 'export_reports',
            'view_notifications',
        ]);

        // 4. Admin Penjualan
        $adminPenjualan = Role::firstOrCreate(['name' => 'admin_penjualan', 'guard_name' => 'web']);
        $adminPenjualan->givePermissionTo([
            'view_dashboard',
            'view_products',
            'view_categories',
            'view_customers', 'create_customers', 'update_customers',
            'view_sales_orders', 'create_sales_orders', 'update_sales_orders', 'delete_sales_orders',
            'approve_sales_orders',
            'view_invoices', 'create_invoices', 'update_invoices',
            'view_deliveries', 'create_deliveries', 'update_deliveries',
            'view_sales_returns', 'create_sales_returns', 'update_sales_returns',
            'approve_sales_returns',
            'view_sales_persons',
            'view_sales_visits',
            'view_sales_targets', 'create_sales_targets', 'update_sales_targets',
            'view_sales_commissions', 'create_sales_commissions', 'update_sales_commissions',
            'view_receivables',
            'view_payments', 'create_payments',
            'view_promos', 'create_promos', 'update_promos',
            'view_online_orders', 'update_online_orders',
            'view_drivers',
            'view_vehicles',
            'view_reports', 'export_reports',
            'view_notifications',
        ]);

        // 5. Purchasing
        $purchasing = Role::firstOrCreate(['name' => 'purchasing', 'guard_name' => 'web']);
        $purchasing->givePermissionTo([
            'view_dashboard',
            'view_products',
            'view_categories',
            'view_suppliers', 'create_suppliers', 'update_suppliers',
            'view_warehouses',
            'view_purchase_orders', 'create_purchase_orders', 'update_purchase_orders', 'delete_purchase_orders',
            'view_goods_receipts', 'create_goods_receipts',
            'view_supplier_returns', 'create_supplier_returns', 'update_supplier_returns',
            'approve_supplier_returns',
            'view_payables',
            'view_payment_outs', 'create_payment_outs',
            'view_reports', 'export_reports',
            'view_notifications',
        ]);

        // 6. Sales
        $sales = Role::firstOrCreate(['name' => 'sales', 'guard_name' => 'web']);
        $sales->givePermissionTo([
            'view_dashboard',
            'view_products',
            'view_categories',
            'view_customers', 'create_customers', 'update_customers',
            'view_sales_orders', 'create_sales_orders',
            'view_invoices',
            'view_deliveries',
            'view_sales_persons',
            'view_sales_visits', 'create_sales_visits',
            'view_sales_targets',
            'view_sales_commissions',
            'view_receivables',
            'view_online_orders',
            'view_notifications',
        ]);

        // 7. Driver
        $driver = Role::firstOrCreate(['name' => 'driver', 'guard_name' => 'web']);
        $driver->givePermissionTo([
            'view_dashboard',
            'view_deliveries', 'update_deliveries',
            'view_sales_orders',
            'view_customers',
            'view_notifications',
        ]);

        // 8. Finance
        $finance = Role::firstOrCreate(['name' => 'finance', 'guard_name' => 'web']);
        $finance->givePermissionTo([
            'view_dashboard',
            'view_financial_summary',
            'view_sales_orders',
            'view_invoices', 'create_invoices', 'update_invoices',
            'view_receivables', 'create_receivables', 'update_receivables',
            'view_payments', 'create_payments', 'update_payments',
            'view_payables', 'create_payables', 'update_payables',
            'view_payment_outs', 'create_payment_outs', 'update_payment_outs',
            'view_purchase_orders',
            'view_customers',
            'view_suppliers',
            'view_reports', 'export_reports',
            'view_notifications',
        ]);

        // 9. Pelanggan (Customer Portal)
        $pelanggan = Role::firstOrCreate(['name' => 'pelanggan', 'guard_name' => 'web']);
        $pelanggan->givePermissionTo([
            'portal_access',
            'portal_order',
            'portal_view_products',
            'portal_view_invoices',
            'portal_view_receivables',
            'view_notifications',
        ]);
    }
}
