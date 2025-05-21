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
        Schema::create('fiche_tag', function (Blueprint $table): void {
            $table->foreignId('fiche_id')->index();
            $table->foreignId('tag_id')->index();
        });
    }
};
