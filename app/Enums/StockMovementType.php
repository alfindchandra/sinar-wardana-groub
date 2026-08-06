<?php

namespace App\Enums;

enum StockMovementType: string
{
    case IN = 'in';
    case OUT = 'out';
    case ADJUSTMENT = 'adjustment';
    case MUTATION = 'mutation';
    case RETURN = 'return';
    case OPNAME = 'opname';

    public function label(): string
    {
        return match ($this) {
            self::IN => 'Barang Masuk',
            self::OUT => 'Barang Keluar',
            self::ADJUSTMENT => 'Penyesuaian',
            self::MUTATION => 'Mutasi',
            self::RETURN => 'Retur',
            self::OPNAME => 'Stock Opname',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::IN => 'green',
            self::OUT => 'red',
            self::ADJUSTMENT => 'yellow',
            self::MUTATION => 'blue',
            self::RETURN => 'orange',
            self::OPNAME => 'purple',
        };
    }

    public function isIncoming(): bool
    {
        return in_array($this, [self::IN, self::RETURN]);
    }

    public function isOutgoing(): bool
    {
        return in_array($this, [self::OUT]);
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
