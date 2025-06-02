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
        // Drop foreign key constraint if it exists
        if (Schema::hasTable('transaction_details')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                // Drop foreign key constraint using Laravel's built-in methods
                if (Schema::hasColumn('transaction_details', 'products_id')) {
                    $table->dropForeign(['products_id']);
                }
            });
            
            // Add new foreign key constraint to produks table
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->foreign('products_id')->references('id')->on('produks')->onDelete('cascade');
            });
        } else {
            Schema::create('transaction_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transactions_id')->nullable()->constrained('transactions')->onDelete('cascade');
                $table->unsignedBigInteger('products_id')->nullable();
                $table->foreign('products_id')->references('id')->on('produks')->onDelete('cascade');
                $table->integer('qty')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transaction_details')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                // Drop foreign key constraint using Laravel's built-in methods
                if (Schema::hasColumn('transaction_details', 'products_id')) {
                    $table->dropForeign(['products_id']);
                }
                
                // Add back original constraint to products table
                $table->foreign('products_id')->references('id')->on('products')->onDelete('cascade');
            });
        }
    }
};
