<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('transaction_details') && !Schema::hasColumn('transaction_details', 'transaction_id')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->unsignedBigInteger('transaction_id')->nullable()->after('id');

                // Tambahkan relasi foreign key jika perlu
                $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('transaction_details') && Schema::hasColumn('transaction_details', 'transaction_id')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->dropForeign(['transaction_id']);
                $table->dropColumn('transaction_id');
            });
        }
    }
};
