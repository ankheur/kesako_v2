<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TypeCategorie: string implements HasLabel
{
    case Courant = 'courant';
    case Activite = 'activite';
    case Periode = 'periode';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Courant => 'Courant',
            self::Activite => 'Activité',
            self::Periode => 'Période',
        };
    }
}
