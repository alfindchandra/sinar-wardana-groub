<?php

namespace App\Enums;

/**
 * Satuan yang bisa dipilih di baris "Breakdown Harga Otomatis" pada form produk.
 * Sengaja dipisah dari ProductUnit (satuan checkout utama) karena di sini
 * boleh memilih Pcs juga — breakdown ini cuma info, bukan satuan yang dijual.
 */
enum BreakdownUnit: string
{
    case DUS = 'dus';
    case SAK = 'sak';
    case BAL = 'bal';
    case KARUNG = 'karung';
    case PACK = 'pack';
    case PCS = 'pcs';

    public function label(): string
    {
        return match ($this) {
            self::DUS => 'Dus',
            self::SAK => 'Sak',
            self::BAL => 'Bal',
            self::KARUNG => 'Karung',
            self::PACK => 'Pack',
            self::PCS => 'Pcs',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
