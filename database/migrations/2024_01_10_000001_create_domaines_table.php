<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domaines', function (Blueprint $table): void {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->string('icone')->nullable();
            $table->text('description')->nullable();
            $table->string('couleur')->default('#6B7280');
            $table->foreignId('fiche_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['published_at', 'ordre']);
            $table->index('slug');
        });
    }
};
