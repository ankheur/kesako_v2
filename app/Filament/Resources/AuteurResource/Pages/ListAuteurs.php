<?php

namespace App\Filament\Resources\AuteurResource\Pages;

use App\Filament\Resources\AuteurResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuteurs extends ListRecords
{
    protected static string $resource = AuteurResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
