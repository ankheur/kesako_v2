<?php

use App\Models\Categorie;
use App\Models\Auteur;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Categorie::class);
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

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
