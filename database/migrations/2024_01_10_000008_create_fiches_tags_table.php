<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiches_tags', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('fiche_id')->constrained('fiches')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['fiche_id', 'tag_id']);
            $table->index('fiche_id');
            $table->index('tag_id');
        });
    }
};
