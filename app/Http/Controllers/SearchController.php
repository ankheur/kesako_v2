<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Fiche;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SearchController
{
    public function __invoke(Request $request): View
    {
        $domaines = Domaine::published()->get();

        $fiches_found = Fiche::search($request->search)->get();

        return view('recherche', [
            'fiches' => $fiches_found,
            'domaines' => $domaines,
            'query' => $request->search,
            /* 'popularPosts' => Fiche::popularToday()->limit(3)->get() */
        ]);
    }
}
