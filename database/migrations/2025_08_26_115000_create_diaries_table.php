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
        Schema::create('diaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('artist_id')->constrained()->restrictOnDelete(); // restrict:削除されない
            $table->date('happened_on');
            $table->text('body');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            // ↓DB検索を早くするためのしおりのようなもの
            $table->index(['user_id', 'created_at']);
            $table->index(['artist_id', 'created_at']);
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diaries');
    }
};
