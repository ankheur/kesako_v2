<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function show(Categorie $categorie)
    {
        if(!$categorie->published_at) {
            abort(404);
        }

        return view('categorie', [
            'categorie' => $categorie,
            'articles' => $categorie->articles
        ]);
    }
}
