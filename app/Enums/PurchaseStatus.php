<?php

namespace App\Enums;

enum PurchaseStatus: string
{
    case DRAFT = 'draft';
    case APPROVED = 'approved';
    case RECEIVED = 'received';
    case CLOSED = 'closed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::APPROVED => 'Disetujui',
            self::RECEIVED => 'Diterima',
            self::CLOSED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::APPROVED => 'blue',
            self::RECEIVED => 'green',
            self::CLOSED => 'slate',
            self::CANCELLED => 'red',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
