<?php

declare(strict_types=1);

namespace App\Filament\Resources\AuteurResource\Pages;

use App\Filament\Resources\AuteurResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateAuteur extends CreateRecord
{
    protected static string $resource = AuteurResource::class;

    protected function getActions(): array
    {
        return [

        ];
    }
}
