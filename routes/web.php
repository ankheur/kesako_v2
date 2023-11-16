<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\PageHomeController;
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

Route::get('categorie/{categorie:slug}', [CategorieController::class, 'show'])->name('categorie.show');

Route::get('article/{article:slug}', [ArticleController::class, 'show'])->name('article.show');
