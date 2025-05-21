<?php

declare(strict_types=1);

use App\Filament\Resources\DomaineResource\Pages\ListDomaines;
use App\Models\Domaine;

use function Pest\Livewire\livewire;

test('La table liste les domaines', function () {
    $fiches = Domaine::factory()->count(10)->create();

    livewire(ListDomaines::class)
        ->assertCanSeeTableRecords($fiches);
});
