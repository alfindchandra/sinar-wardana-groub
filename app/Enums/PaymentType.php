<?php

namespace App\Enums;

enum PaymentType: string
{
    case CASH = 'cash';
    case TEMPO = 'tempo';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Tunai',
            self::TEMPO => 'Tempo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::CASH => 'green',
            self::TEMPO => 'yellow',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
