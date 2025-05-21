<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TypeFiche: string implements HasLabel
{
    case Oeuvre = 'oeuvre';
    case Biographie = 'biographie';
    case Evenement = 'evenement';
    case Theme = 'theme';
    case Chronologie = 'chronologie';
    case Concept = 'concept';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Oeuvre => 'Oeuvre',
            self::Biographie => 'Biographie',
            self::Evenement => 'Evènement',
            self::Theme => 'Thème',
            self::Chronologie => 'Chronologie',
            self::Concept => 'Concept',
        };
    }
}
