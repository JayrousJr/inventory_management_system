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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('source_id');
            $table->integer('product_quantity_sold');
            $table->decimal('total_price', 20, 2)->index();
            $table->decimal('paid_amount', 20, 2)->nullable();
            $table->decimal('unpaid_amount', 20, 2)->nullable();
            $table->decimal('profit', 20, 2);
            $table->decimal('pro_buying_price', 20, 2)->index();
            $table->decimal('pro_selling_price', 20, 2)->index();
            $table->string('saler_name');
            $table->foreign('saler_name')->references('name')->on('users')->cascadeOnUpdate()->noActionOnDelete();
            $table->string('customer_name');
            $table->foreign('customer_name')->references('name')->on('users')->cascadeOnUpdate()->noActionOnDelete();
            $table->string('debt_type')->default('Creditor');
            $table->string('product_name')->index()->nullable();
            $table->string('product_name_category')->index()->nullable();
            $table->string('source')->default('sale');
            $table->string('edited_by')->default('Not edited');
            $table->string('category')->index()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
