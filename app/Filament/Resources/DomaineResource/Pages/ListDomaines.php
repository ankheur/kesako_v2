<?php

declare(strict_types=1);

namespace App\Filament\Resources\DomaineResource\Pages;

use App\Filament\Resources\DomaineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListDomaines extends ListRecords
{
    protected static string $resource = DomaineResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
