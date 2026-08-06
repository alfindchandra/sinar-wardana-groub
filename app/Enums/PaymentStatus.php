<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PARTIAL = 'partial';
    case PAID = 'paid';
    case OVERDUE = 'overdue';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Belum Dibayar',
            self::PARTIAL => 'Dibayar Sebagian',
            self::PAID => 'Lunas',
            self::OVERDUE => 'Jatuh Tempo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::UNPAID => 'gray',
            self::PARTIAL => 'yellow',
            self::PAID => 'green',
            self::OVERDUE => 'red',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
