<?php

namespace App\Enums;

enum DeliveryStatus: string
{
    case PENDING = 'pending';
    case LOADING = 'loading';
    case ON_DELIVERY = 'on_delivery';
    case DELIVERED = 'delivered';
    case FAILED = 'failed';
    case RETURNED = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu',
            self::LOADING => 'Loading',
            self::ON_DELIVERY => 'Dalam Pengiriman',
            self::DELIVERED => 'Terkirim',
            self::FAILED => 'Gagal',
            self::RETURNED => 'Dikembalikan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::LOADING => 'yellow',
            self::ON_DELIVERY => 'blue',
            self::DELIVERED => 'green',
            self::FAILED => 'red',
            self::RETURNED => 'orange',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
