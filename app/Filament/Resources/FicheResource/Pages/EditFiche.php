<?php

declare(strict_types=1);

namespace App\Filament\Resources\FicheResource\Pages;

use App\Filament\Resources\FicheResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditFiche extends EditRecord
{
    protected static string $resource = FicheResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
