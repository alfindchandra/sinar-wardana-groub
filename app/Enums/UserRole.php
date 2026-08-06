<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case OWNER = 'owner';
    case ADMIN_GUDANG = 'admin_gudang';
    case ADMIN_PENJUALAN = 'admin_penjualan';
    case PURCHASING = 'purchasing';
    case SALES = 'sales';
    case DRIVER = 'driver';
    case FINANCE = 'finance';
    case PELANGGAN = 'pelanggan';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::OWNER => 'Owner',
            self::ADMIN_GUDANG => 'Admin Gudang',
            self::ADMIN_PENJUALAN => 'Admin Penjualan',
            self::PURCHASING => 'Purchasing',
            self::SALES => 'Sales',
            self::DRIVER => 'Driver',
            self::FINANCE => 'Finance',
            self::PELANGGAN => 'Pelanggan / Toko',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'red',
            self::OWNER => 'purple',
            self::ADMIN_GUDANG => 'blue',
            self::ADMIN_PENJUALAN => 'green',
            self::PURCHASING => 'yellow',
            self::SALES => 'indigo',
            self::DRIVER => 'orange',
            self::FINANCE => 'teal',
            self::PELANGGAN => 'slate',
        };
    }

    public function isAdmin(): bool
    {
        return in_array($this, [
            self::SUPER_ADMIN,
            self::OWNER,
            self::ADMIN_GUDANG,
            self::ADMIN_PENJUALAN,
            self::PURCHASING,
            self::FINANCE,
        ]);
    }

    public function isPortalUser(): bool
    {
        return $this === self::PELANGGAN;
    }

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::PELANGGAN => 'portal.dashboard',
            default => 'dashboard',
        };
    }

    public static function adminRoles(): array
    {
        return [
            self::SUPER_ADMIN,
            self::OWNER,
            self::ADMIN_GUDANG,
            self::ADMIN_PENJUALAN,
            self::PURCHASING,
            self::SALES,
            self::DRIVER,
            self::FINANCE,
        ];
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
