<?php

declare(strict_types=1);

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DomaineController;
use App\Http\Controllers\FicheController;
use App\Http\Controllers\PageHomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TypeFicheController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', PageHomeController::class)->name('pages.home');

Route::get('domaine/{domaine:slug}', [DomaineController::class, 'show'])->name('domaine.show');

Route::get('fiche/{fiche:slug}', [FicheController::class, 'show'])->name('fiche.show');

Route::get('type-fiche/{type}', [TypeFicheController::class, 'show'])->name('fiche-type.show');

Route::get('tag/{tag:slug}', [TagController::class, 'show'])->name('tag.show');

Route::get('categorie/{categorie:slug}', [CategorieController::class, 'show'])->name('categorie.show');

Route::post('recherche', SearchController::class)->name('recherche');
