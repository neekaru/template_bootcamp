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
        if (!Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembeli_id')->constrained('pembelis')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->tinyInteger('rating');
            $table->text('review')->nullable();
            $table->string('foto_review')->nullable();
            $table->timestamps();
            });
        } else {
            if (!Schema::hasColumn('ratings', 'pembeli_id')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->foreignId('pembeli_id')->constrained('pembelis')->onDelete('cascade');
            });
            }
            if (!Schema::hasColumn('ratings', 'produk_id')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            });
            }
            if (!Schema::hasColumn('ratings', 'transaction_id')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            });
            }
            if (!Schema::hasColumn('ratings', 'rating')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->tinyInteger('rating');
            });
            }
            if (!Schema::hasColumn('ratings', 'review')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->text('review')->nullable();
            });
            }
            if (!Schema::hasColumn('ratings', 'foto_review')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->string('foto_review')->nullable();
            });
            }
            if (!Schema::hasColumn('ratings', 'created_at') || !Schema::hasColumn('ratings', 'updated_at')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->timestamps();
            });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
