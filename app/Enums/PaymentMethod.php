<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case TRANSFER = 'transfer';
    case GIRO = 'giro';
    case CEK = 'cek';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Tunai',
            self::TRANSFER => 'Transfer Bank',
            self::GIRO => 'Giro',
            self::CEK => 'Cek',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::CASH => 'green',
            self::TRANSFER => 'blue',
            self::GIRO => 'purple',
            self::CEK => 'yellow',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
