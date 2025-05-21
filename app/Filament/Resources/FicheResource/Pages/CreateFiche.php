<?php

declare(strict_types=1);

namespace App\Filament\Resources\FicheResource\Pages;

use App\Filament\Resources\FicheResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateFiche extends CreateRecord
{
    protected static string $resource = FicheResource::class;

    protected function getActions(): array
    {
        return [

        ];
    }
}
