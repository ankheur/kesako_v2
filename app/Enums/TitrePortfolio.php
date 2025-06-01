<?php

declare(strict_types=1);

namespace App\Enums;

enum TitrePortfolio: string
{
    case Oeuvres = 'oeuvres';
    case Protagonistes = 'protagonistes';
    case Equipe = 'equipe';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Oeuvres => 'Oeuvres principales',
            self::Protagonistes => 'Protagonistes principaux',
            self::Equipe => 'L\'Equipe'
        };
    }
}
