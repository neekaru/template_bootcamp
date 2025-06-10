<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('transaction_details')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                if (!Schema::hasColumn('transaction_details', 'transaction_id')) {
                    $table->unsignedBigInteger('transaction_id')->nullable()->after('id');
                    $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
                }

                if (!Schema::hasColumn('transaction_details', 'produk_id')) {
                    $table->unsignedBigInteger('produk_id')->nullable()->after('transaction_id');
                    $table->foreign('produk_id')->references('id')->on('produks')->onDelete('cascade');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('transaction_details')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                if (Schema::hasColumn('transaction_details', 'transaction_id')) {
                    $table->dropForeign(['transaction_id']);
                    $table->dropColumn('transaction_id');
                }

                if (Schema::hasColumn('transaction_details', 'produk_id')) {
                    $table->dropForeign(['produk_id']);
                    $table->dropColumn('produk_id');
                }
            });
        }
    }
};
