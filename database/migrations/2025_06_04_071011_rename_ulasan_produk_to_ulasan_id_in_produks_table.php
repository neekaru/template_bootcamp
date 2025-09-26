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
        // Check if column exists before dropping
        if (Schema::hasColumn('produks', 'ulasan_produk')) {
            Schema::table('produks', function (Blueprint $table) {
                $table->dropColumn('ulasan_produk');
            });
        }

        // Check if ulasan_id column doesn't exist before adding
        if (!Schema::hasColumn('produks', 'ulasan_id')) {
            Schema::table('produks', function (Blueprint $table) {
                $table->foreignId('ulasan_id')->nullable()->constrained('ratings')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropForeign(['ulasan_id']);
            $table->dropColumn('ulasan_id');
            $table->text('ulasan_produk')->nullable();
        });
    }
};
