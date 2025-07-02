<?php

namespace App\Enums;

enum PRODUCT_MEASUREMENT_UNITS: string
{
    case KILOGRAM = 'Kg';
    case GRAM = 'g';
    case LITRE = 'l';
    case MILLILITRE = 'ml';

    public function label(): string
    {
        return match ($this) {
            self::KILOGRAM => 'Kilogram',
            self::GRAM => 'Gram',
            self::LITRE => 'Litre',
            self::MILLILITRE => 'Millilitre',
        };
    }

    public static function labels(): array
    {
        $labels = [];

        foreach(self::cases() as $unit) {
            $labels[$unit->value] = $unit->label();
        }

        return $labels;
    }
}
