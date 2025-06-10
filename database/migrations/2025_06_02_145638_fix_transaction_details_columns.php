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
        if (Schema::hasTable('transaction_details')) {

            Schema::table('transaction_details', function (Blueprint $table) {
                $table->integer('transaction_id')->nullable()->change();
            });

            // Cek apakah kolom 'transactions_id' ada sebelum update
            if (Schema::hasColumn('transaction_details', 'transactions_id')) {
                DB::statement('UPDATE transaction_details SET transaction_id = transactions_id WHERE transaction_id IS NULL AND transactions_id IS NOT NULL');
            }

            if (Schema::hasColumn('transaction_details', 'products_id')) {
                DB::statement('UPDATE transaction_details SET produk_id = products_id WHERE produk_id IS NULL AND products_id IS NOT NULL');
            }

            Schema::table('transaction_details', function (Blueprint $table) {
                $table->integer('transaction_id')->nullable(false)->change();
            });

            Schema::table('transaction_details', function (Blueprint $table) {
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
