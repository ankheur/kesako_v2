<?php

namespace App\Filament\Resources\AuteurResource\Pages;

use App\Filament\Resources\AuteurResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAuteur extends CreateRecord
{
    protected static string $resource = AuteurResource::class;

    protected function getActions(): array
    {
        return [

        ];
    }
}
