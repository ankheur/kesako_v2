<?php

namespace App\Filament\Resources\CategorieResource\Pages;

use App\Filament\Resources\CategorieResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategorie extends CreateRecord
{
    protected static string $resource = CategorieResource::class;

    protected function getActions(): array
    {
        return [

        ];
    }
}
