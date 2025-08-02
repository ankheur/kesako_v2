<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domaines_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('domaine_id')->constrained('domaines')->cascadeOnDelete();
            $table->foreignId('categorie_id')->constrained('categories')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['domaine_id', 'categorie_id']);
            $table->index('domaine_id');
            $table->index('categorie_id');
        });
    }
};
