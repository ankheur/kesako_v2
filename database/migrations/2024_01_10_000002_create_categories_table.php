<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->string('type_categorie');
            $table->text('description')->nullable();
            $table->foreignId('fiche_id')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['published_at', 'type_categorie']);
            $table->index(['type_categorie', 'ordre']);
            $table->index('slug');
        });
    }
};
