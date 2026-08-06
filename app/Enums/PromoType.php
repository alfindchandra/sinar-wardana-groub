<?php

namespace App\Enums;

enum PromoType: string
{
    case DISCOUNT_PERCENT = 'discount_percent';
    case DISCOUNT_AMOUNT = 'discount_amount';
    case BUY_X_GET_Y = 'buy_x_get_y';
    case FREE_SHIPPING = 'free_shipping';

    public function label(): string
    {
        return match ($this) {
            self::DISCOUNT_PERCENT => 'Diskon Persen',
            self::DISCOUNT_AMOUNT => 'Diskon Nominal',
            self::BUY_X_GET_Y => 'Beli X Gratis Y',
            self::FREE_SHIPPING => 'Gratis Ongkir',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DISCOUNT_PERCENT => 'green',
            self::DISCOUNT_AMOUNT => 'blue',
            self::BUY_X_GET_Y => 'purple',
            self::FREE_SHIPPING => 'yellow',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
