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
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('denomination', 255);
            $table->string('slug', 255);
            $table->enum('type', App\Enums\TypeCategorie::toArray());
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }
};
