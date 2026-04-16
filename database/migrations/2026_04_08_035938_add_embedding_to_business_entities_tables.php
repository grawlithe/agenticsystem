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
        Schema::table('products', function (Blueprint $table) {
            $table->vector('embedding', 768)->nullable();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->vector('embedding', 768)->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->vector('embedding', 768)->nullable();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->vector('embedding', 768)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('embedding');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('embedding');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('embedding');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('embedding');
        });
    }
};
