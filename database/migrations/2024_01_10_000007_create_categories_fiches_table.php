<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories_fiches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('fiche_id')->constrained('fiches')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['categorie_id', 'fiche_id']);
            $table->index('categorie_id');
            $table->index('fiche_id');
        });
    }
};
