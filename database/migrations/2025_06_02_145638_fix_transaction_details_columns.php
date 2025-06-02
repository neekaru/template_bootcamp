<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make transaction_id nullable temporarily
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->integer('transaction_id')->nullable()->change();
        });

        // Copy data from transactions_id to transaction_id
        DB::statement('UPDATE transaction_details SET transaction_id = transactions_id WHERE transaction_id IS NULL AND transactions_id IS NOT NULL');
        
        // Copy data from products_id to produk_id
        DB::statement('UPDATE transaction_details SET produk_id = products_id WHERE produk_id IS NULL AND products_id IS NOT NULL');

        // Make transaction_id required again
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->integer('transaction_id')->nullable(false)->change();
        });
        
        // Drop redundant columns
        Schema::table('transaction_details', function (Blueprint $table) {
            // Drop foreign key constraints first
            if (Schema::hasColumn('transaction_details', 'transactions_id')) {
                $table->dropForeign(['transactions_id']);
                $table->dropColumn('transactions_id');
            }
            
            if (Schema::hasColumn('transaction_details', 'products_id')) {
                $table->dropForeign(['products_id']);
                $table->dropColumn('products_id');
            }
            
            if (Schema::hasColumn('transaction_details', 'qty')) {
                $table->dropColumn('qty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the columns if needed
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->unsignedBigInteger('transactions_id')->nullable();
            $table->unsignedBigInteger('products_id')->nullable();
            $table->integer('qty')->default(0);
            
            // Add foreign keys back
            $table->foreign('transactions_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('products_id')->references('id')->on('produks')->onDelete('cascade');
        });
    }
};
