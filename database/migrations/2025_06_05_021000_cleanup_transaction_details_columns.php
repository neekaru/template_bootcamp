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
            // Migrate data from old columns to new columns if both exist
            if (Schema::hasColumn('transaction_details', 'transactions_id') && Schema::hasColumn('transaction_details', 'transaction_id')) {
                DB::statement('UPDATE transaction_details SET transaction_id = transactions_id WHERE transaction_id IS NULL AND transactions_id IS NOT NULL');
            }

            if (Schema::hasColumn('transaction_details', 'products_id') && Schema::hasColumn('transaction_details', 'produk_id')) {
                DB::statement('UPDATE transaction_details SET produk_id = products_id WHERE produk_id IS NULL AND products_id IS NOT NULL');
            }

            // Make the new columns non-nullable after data migration
            Schema::table('transaction_details', function (Blueprint $table) {
                if (Schema::hasColumn('transaction_details', 'transaction_id')) {
                    $table->unsignedBigInteger('transaction_id')->nullable(false)->change();
                }
                if (Schema::hasColumn('transaction_details', 'produk_id')) {
                    $table->unsignedBigInteger('produk_id')->nullable(false)->change();
                }
            });

            // Drop the old columns if they exist
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
        if (Schema::hasTable('transaction_details')) {
            // Add back the old columns
            Schema::table('transaction_details', function (Blueprint $table) {
                if (!Schema::hasColumn('transaction_details', 'transactions_id')) {
                    $table->unsignedBigInteger('transactions_id')->nullable();
                    $table->foreign('transactions_id')->references('id')->on('transactions')->onDelete('cascade');
                }
                if (!Schema::hasColumn('transaction_details', 'products_id')) {
                    $table->unsignedBigInteger('products_id')->nullable();
                    $table->foreign('products_id')->references('id')->on('produks')->onDelete('cascade');
                }
                if (!Schema::hasColumn('transaction_details', 'qty')) {
                    $table->integer('qty')->default(0);
                }
            });

            // Migrate data back to old columns
            if (Schema::hasColumn('transaction_details', 'transaction_id')) {
                DB::statement('UPDATE transaction_details SET transactions_id = transaction_id WHERE transactions_id IS NULL AND transaction_id IS NOT NULL');
            }
            if (Schema::hasColumn('transaction_details', 'produk_id')) {
                DB::statement('UPDATE transaction_details SET products_id = produk_id WHERE products_id IS NULL AND produk_id IS NOT NULL');
            }

            // Make new columns nullable again
            Schema::table('transaction_details', function (Blueprint $table) {
                if (Schema::hasColumn('transaction_details', 'transaction_id')) {
                    $table->unsignedBigInteger('transaction_id')->nullable()->change();
                }
                if (Schema::hasColumn('transaction_details', 'produk_id')) {
                    $table->unsignedBigInteger('produk_id')->nullable()->change();
                }
            });
        }
    }
};