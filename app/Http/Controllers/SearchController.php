<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $categories = Categorie::published()->get();

        $articles_found = Article::search($request->search)->get();
        return view('recherche', [
            'articles' => $articles_found,
            'categories' => $categories,
            'query' => $request->search
        ]);
    }
}
