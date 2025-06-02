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
        if (!Schema::hasTable('transaction_details')) {
            Schema::create('transaction_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transactions_id')->nullable()->constrained('transactions')->onDelete('cascade');
                $table->foreignId('products_id')->nullable()->constrained('products')->onDelete('cascade');
                $table->integer('qty')->default(0);
                $table->timestamps();
            });
        } else {
            Schema::table('transaction_details', function (Blueprint $table) {
                if (!Schema::hasColumn('transaction_details', 'id')) {
                    $table->id();
                }
                if (!Schema::hasColumn('transaction_details', 'transactions_id')) {
                    $table->foreignId('transactions_id')->nullable()->constrained('transactions')->onDelete('cascade');
                }
                if (!Schema::hasColumn('transaction_details', 'products_id')) {
                    $table->foreignId('products_id')->nullable()->constrained('products')->onDelete('cascade');
                }
                if (!Schema::hasColumn('transaction_details', 'qty')) {
                    $table->integer('qty')->default(0);
                }
                if (!Schema::hasColumn('transaction_details', 'created_at') && !Schema::hasColumn('transaction_details', 'updated_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
