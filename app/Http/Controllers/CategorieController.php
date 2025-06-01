<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Domaine;
use Illuminate\View\View;

final class CategorieController
{
    public function show(Categorie $categorie): View
    {
        if (! $categorie->published_at) {
            abort(404);
        }

        /* $categorie->visit()->hourlyInterval(); */

        return view('categorie', [
            'categorie' => $categorie,
            'fiches' => $categorie->fiches,
            'domaines' => Domaine::published()->get(),
        ]);
    }
}
