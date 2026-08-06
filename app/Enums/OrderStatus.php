<?php

namespace App\Enums;

enum OrderStatus: string
{
    case DRAFT = 'draft';
    case CONFIRMED = 'confirmed';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::CONFIRMED => 'Dikonfirmasi',
            self::PROCESSING => 'Diproses',
            self::SHIPPED => 'Dikirim',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::CONFIRMED => 'blue',
            self::PROCESSING => 'yellow',
            self::SHIPPED => 'indigo',
            self::COMPLETED => 'green',
            self::CANCELLED => 'red',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'pencil',
            self::CONFIRMED => 'check-circle',
            self::PROCESSING => 'cog',
            self::SHIPPED => 'truck',
            self::COMPLETED => 'badge-check',
            self::CANCELLED => 'x-circle',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
