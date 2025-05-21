<?php

declare(strict_types=1);

namespace App\Filament\Resources\CategorieResource\Pages;

use App\Filament\Resources\CategorieResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCategorie extends CreateRecord
{
    protected static string $resource = CategorieResource::class;
}
