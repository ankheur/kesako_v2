<?php

declare(strict_types=1);

namespace App\Filament\Resources\DomaineResource\Pages;

use App\Filament\Resources\DomaineResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateDomaine extends CreateRecord
{
    protected static string $resource = DomaineResource::class;

    protected function getActions(): array
    {
        return [

        ];
    }
}
