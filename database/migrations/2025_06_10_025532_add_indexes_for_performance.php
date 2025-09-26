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
        Schema::table('carts', function (Blueprint $table) {
            // Add composite index for frequent cart queries
            $table->index(['pembeli_id', 'produk_id'], 'idx_carts_pembeli_produk');
            $table->index('pembeli_id', 'idx_carts_pembeli_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            // Add indexes for transaction queries
            $table->index('pembeli_id', 'idx_transactions_pembeli_id');
            $table->index('status', 'idx_transactions_status');
            $table->index('snap_token', 'idx_transactions_snap_token');
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            // Add indexes for transaction detail queries
            $table->index('transaction_id', 'idx_transaction_details_transaction_id');
            $table->index('produk_id', 'idx_transaction_details_produk_id');
        });

        Schema::table('produks', function (Blueprint $table) {
            // Add indexes for product queries
            $table->index('kategori_produk', 'idx_produks_kategori');
            $table->index('created_at', 'idx_produks_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex('idx_carts_pembeli_produk');
            $table->dropIndex('idx_carts_pembeli_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_transactions_pembeli_id');
            $table->dropIndex('idx_transactions_status');
            $table->dropIndex('idx_transactions_snap_token');
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropIndex('idx_transaction_details_transaction_id');
            $table->dropIndex('idx_transaction_details_produk_id');
        });

        Schema::table('produks', function (Blueprint $table) {
            $table->dropIndex('idx_produks_kategori');
            $table->dropIndex('idx_produks_created_at');
        });
    }
};
