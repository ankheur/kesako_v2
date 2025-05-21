<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TypeFiche;
use App\Models\Domaine;
use App\Models\Fiche;
use Illuminate\Contracts\View\View;

final class TypeFicheController
{
    public function show(TypeFiche $type): View
    {
        return view('type-fiche', [
            'domaines' => Domaine::published()->get(),
            'type' => $type,
            'fiches' => Fiche::where('type', $type->value)->get(),
        ]);
    }
}
