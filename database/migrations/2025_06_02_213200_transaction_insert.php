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
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'id')) {
                $table->id();
            }
            if (!Schema::hasColumn('transactions', 'pembeli_id')) {
                $table->foreignId('pembeli_id')->nullable()->constrained('pembelis')->onDelete('cascade');
            }
            if (!Schema::hasColumn('transactions', 'invoice')) {
                $table->string('invoice')->nullable()->unique();
            }
            if (!Schema::hasColumn('transactions', 'berat')) {
                $table->integer('berat')->default(0);
            }
            if (!Schema::hasColumn('transactions', 'alamat')) {
                $table->text('alamat')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'total')) {
                $table->bigInteger('total')->default(0);
            }
            if (!Schema::hasColumn('transactions', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('transactions', 'snap_token')) {
                $table->string('snap_token')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'created_at') && !Schema::hasColumn('transactions', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
