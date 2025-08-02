<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiches', function (Blueprint $table): void {
            $table->id();
            $table->string('titre');
            $table->string('soustitre')->nullable();
            $table->string('slug')->unique();
            $table->string('illustration')->nullable();
            $table->string('illustration_alt')->nullable();
            $table->text('description');
            $table->longText('contenu');
            $table->string('type_fiche');
            $table->string('status')->default('brouillon');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('featured_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['published_at', 'featured_at']);
            $table->index(['type_fiche', 'published_at']);
            $table->index(['status', 'published_at']);
            $table->index('slug');
            /* $table->fullText(['titre', 'soustitre', 'description', 'contenu']); */
        });
    }
};
