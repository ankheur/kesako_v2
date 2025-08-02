<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cette migration ajoute les foreign keys vers les fiches explicatives
     * Elle doit être exécutée après la création de toutes les tables principales
     */
    public function up(): void
    {
        Schema::table('domaines', function (Blueprint $table): void {
            $table->foreign('fiche_id')->references('id')->on('fiches')->nullOnDelete();
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->foreign('fiche_id')->references('id')->on('fiches')->nullOnDelete();
        });

        Schema::table('tags', function (Blueprint $table): void {
            $table->foreign('fiche_id')->references('id')->on('fiches')->nullOnDelete();
        });
    }
};
