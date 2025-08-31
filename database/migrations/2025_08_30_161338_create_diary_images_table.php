<?php

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
        Schema::create('diary_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diary_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->timestamps();

            // 検索を早くするためインデックスを作る
            $table->index('diary_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diary_images');
    }
};
