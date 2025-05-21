<?php

declare(strict_types=1);

use App\Filament\Resources\FicheResource\Pages\ListFiches;
use App\Models\Fiche;

use function Pest\Livewire\livewire;

test('La table liste les fiches', function () {
    $fiches = Fiche::factory()->count(10)->create();

    livewire(ListFiches::class)
        ->assertCanSeeTableRecords($fiches);
});
