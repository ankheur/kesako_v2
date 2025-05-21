<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Fiche;
use Illuminate\View\View;

final class FicheController
{
    public function show(Fiche $fiche): View
    {
        if (! $fiche->published_at) {
            abort(404);
        }

        /* $fiche->visit()->hourlyInterval(); */

        return view('fiche', [
            'fiche' => $fiche,
            'domaines' => Domaine::published()->get(),
            /* 'popularPosts' => Fiche::popularToday()->limit(3)->get() */
        ]);
    }
}
