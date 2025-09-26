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
        if (Schema::hasTable('produks')) {
            Schema::table('produks', function (Blueprint $table) {
                if (Schema::hasColumn('produks', 'ulasan_produk')) {
                    $table->dropColumn('ulasan_produk');
                }
            });

            Schema::table('produks', function (Blueprint $table) {
                if (!Schema::hasColumn('produks', 'ulasan_id')) {
                    $table->foreignId('ulasan_id')->nullable()->constrained('ratings')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('produks')) {
            Schema::table('produks', function (Blueprint $table) {
                if (Schema::hasColumn('produks', 'ulasan_id')) {
                    $table->dropForeign(['ulasan_id']);
                    $table->dropColumn('ulasan_id');
                }

                if (!Schema::hasColumn('produks', 'ulasan_produk')) {
                    $table->text('ulasan_produk')->nullable();
                }
            });
        }
    }
};
