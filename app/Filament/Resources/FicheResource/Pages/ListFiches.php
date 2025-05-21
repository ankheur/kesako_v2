<?php

declare(strict_types=1);

namespace App\Filament\Resources\FicheResource\Pages;

use App\Filament\Resources\FicheResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListFiches extends ListRecords
{
    protected static string $resource = FicheResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
