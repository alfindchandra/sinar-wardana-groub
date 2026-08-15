<?php

namespace App\Enums;

enum ProductUnit: string
{
    case DUS = 'dus';
    case SAK = 'sak';
    case BAL = 'bal';
    case KARUNG = 'karung';
    case PACK = 'pack';
    case PCS = 'pcs';
    case BOTOL = 'botol';
    case RENCENG = 'renceng';

    public function label(): string
    {
        return match ($this) {
            self::DUS => 'Dus',
            self::SAK => 'Sak',
            self::BAL => 'Bal',
            self::KARUNG => 'Karung',
            self::PACK => 'Pack',
            self::PCS => 'Pcs',
            self::BOTOL => 'Botol',
            self::RENCENG => 'Renceng',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}