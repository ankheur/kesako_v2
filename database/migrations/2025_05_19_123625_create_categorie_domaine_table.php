<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categorie_domaine', function (Blueprint $table): void {
            $table->foreignId('categorie_id')->index();
            $table->foreignId('domaine_id')->index();
        });
    }
};
