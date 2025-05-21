<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TypeCategorie;
use App\Models\Domaine;
use App\Models\Fiche;
use Illuminate\View\View;

final class DomaineController
{
    public function show(Domaine $domaine): View
    {
        if (! $domaine->published_at) {
            abort(404);
        }

        return view('domaine', [
            'domaines' => Domaine::published()->get(),
            'domaine' => $domaine,
            'fiches' => Fiche::fromDomaine($domaine->id)->get(),
            'types_categorie' => TypeCategorie::cases(),
            'categories' => $domaine->categories,
            /* 'popularPosts' => Fiche::popularToday()->limit(3)->get() */
        ]);
    }
}
