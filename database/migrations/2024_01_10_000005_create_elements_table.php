<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('fiche_id')->constrained('fiches')->cascadeOnDelete();
            $table->string('nom');
            $table->string('complement')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->foreignId('fiche_liee_id')->nullable()->constrained('fiches')->nullOnDelete();
            $table->string('lien_externe')->nullable();
            $table->string('type_lien')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();

            $table->index(['fiche_id', 'ordre']);
            $table->index('fiche_liee_id');
            $table->index('type_lien');
        });
    }
};
