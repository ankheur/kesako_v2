<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatutFiche: string implements HasColor, HasLabel
{
    case Publiee = 'publiee';
    case Brouillon = 'brouillon';
    case Revision = 'revision';
    case Refusee = 'refusee';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Publiee => 'Publiée',
            self::Brouillon => 'Brouillon',
            self::Revision => 'En attente de validation',
            self::Refusee => 'Refusée',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Publiee => 'success',
            self::Brouillon => 'warning',
            self::Revision => 'info',
            self::Refusee => 'danger',
        };
    }
}
