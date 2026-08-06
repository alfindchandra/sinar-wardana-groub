<?php

namespace App\Enums;

enum WarehouseType: string
{
    case UTAMA = 'utama';
    case TRANSIT = 'transit';
    case CABANG = 'cabang';

    public function label(): string
    {
        return match ($this) {
            self::UTAMA => 'Gudang Utama',
            self::TRANSIT => 'Gudang Transit',
            self::CABANG => 'Gudang Cabang',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::UTAMA => 'blue',
            self::TRANSIT => 'yellow',
            self::CABANG => 'green',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
