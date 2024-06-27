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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('supplier')->index();
            $table->string('category_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('product_name')->index();
            $table->string('product_name_category')->index();
            $table->integer('quantity_in_store')->index()->default(0);
            $table->decimal('buying_price', 20, 2)->index();
            $table->decimal('selling_price', 20, 2)->index();
            $table->decimal('total_cost', 20, 2)->nullable();
            $table->decimal('paid_amount', 20, 2)->nullable();
            $table->decimal('unpaid_amount', 20, 2)->nullable();
            $table->decimal('amount_exp', 20, 2)->nullable();
            $table->string('source')->default('store');
            $table->string('debt_type')->default('Debtor');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
