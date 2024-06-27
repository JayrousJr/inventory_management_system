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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('shop_id')->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->nullable();
            $table->decimal('buying_price', 20, 2)->index();
            $table->decimal('selling_price', 20, 2)->index();
            $table->integer('quantity')->index()->default(0);
            $table->integer('source_id')->index()->nullable();
            $table->string('source_name')->index()->nullable();
            $table->string('product_name')->index()->nullable();
            $table->string('product_name_category')->index()->nullable();
            $table->string('category')->index()->nullable();
            $table->string('edited_by')->index()->nullable()->default('Not edited');
            // $table->string('source_type', ['App\Models\Store', 'App\Models\Purchase']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
