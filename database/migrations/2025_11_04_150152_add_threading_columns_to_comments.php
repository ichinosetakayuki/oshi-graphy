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
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedTinyInteger('depth')->default(0)->index();
            $table->string('path', 255)->nullable()->index();
            $table->unsignedTinyInteger('root_id')->nullable()->index();

            $table->index(['diary_id', 'path']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['dairy_id', 'path']);
            $table->dropColumn(['depth', 'path', 'root_id']);
        });
    }
};
