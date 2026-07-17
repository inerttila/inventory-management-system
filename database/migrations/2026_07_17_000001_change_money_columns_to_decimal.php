<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Allow fractional currency amounts (e.g. USD 29.95).
     * Previously money columns were integers (IDR-style whole units).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('purchase_price', 15, 4)->default(0)->change();
            $table->decimal('selling_price', 15, 4)->default(0)->change();
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->decimal('unit_price', 15, 4)->change();
            $table->decimal('selling_price', 15, 4)->nullable()->change();
            $table->decimal('subtotal', 15, 4)->change();
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('total', 15, 4)->default(0)->change();
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('cost_price', 15, 4)->change();
            $table->decimal('unit_price', 15, 4)->change();
            $table->decimal('discount', 15, 4)->default(0)->change();
            $table->decimal('final_price', 15, 4)->change();
            $table->decimal('subtotal', 15, 4)->change();
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('subtotal', 15, 4)->default(0)->change();
            $table->decimal('global_discount', 15, 4)->default(0)->change();
            $table->decimal('total_discount', 15, 4)->default(0)->change();
            $table->decimal('total', 15, 4)->default(0)->change();
            $table->decimal('cash_received', 15, 4)->default(0)->change();
            $table->decimal('change', 15, 4)->default(0)->change();
        });

        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->decimal('amount', 15, 4)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('purchase_price')->change();
            $table->bigInteger('selling_price')->change();
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->bigInteger('unit_price')->change();
            $table->bigInteger('selling_price')->nullable()->change();
            $table->bigInteger('subtotal')->change();
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->bigInteger('total')->default(0)->change();
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->bigInteger('cost_price')->change();
            $table->bigInteger('unit_price')->change();
            $table->bigInteger('discount')->default(0)->change();
            $table->bigInteger('final_price')->change();
            $table->bigInteger('subtotal')->change();
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->bigInteger('subtotal')->default(0)->change();
            $table->bigInteger('global_discount')->default(0)->change();
            $table->bigInteger('total_discount')->default(0)->change();
            $table->bigInteger('total')->default(0)->change();
            $table->bigInteger('cash_received')->default(0)->change();
            $table->bigInteger('change')->default(0)->change();
        });

        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->bigInteger('amount')->change();
        });
    }
};
