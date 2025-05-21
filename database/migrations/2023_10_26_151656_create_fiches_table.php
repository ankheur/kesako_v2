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
            $table->string('soustitre');
            $table->string('illustration')->nullable();
            $table->string('slug');
            $table->text('description');
            $table->text('contenu');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
