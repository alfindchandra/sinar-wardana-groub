<?php

namespace App\Enums;

enum CustomerType: string
{
    case RETAIL = 'retail';
    case AGEN = 'agen';
    case DISTRIBUTOR = 'distributor';

    public function label(): string
    {
        return match ($this) {
            self::RETAIL => 'Retail / Toko',
            self::AGEN => 'Agen',
            self::DISTRIBUTOR => 'Distributor',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::RETAIL => 'blue',
            self::AGEN => 'green',
            self::DISTRIBUTOR => 'purple',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
