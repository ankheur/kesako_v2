<?php

namespace App\Filament\Resources\AuteurResource\Pages;

use App\Filament\Resources\AuteurResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuteur extends EditRecord
{
    protected static string $resource = AuteurResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
