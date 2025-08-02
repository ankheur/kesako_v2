<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiches_connexes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('fiche_id')->constrained('fiches')->cascadeOnDelete();
            $table->foreignId('fiche_connexe_id')->constrained('fiches')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['fiche_id', 'fiche_connexe_id']);
            $table->index('fiche_id');
            $table->index('fiche_connexe_id');
        });
    }
};
