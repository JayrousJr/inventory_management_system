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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('expense_type');
            $table->decimal('total_amount', 20, 2)->index();
            $table->decimal('paid_amount', 20, 2)->nullable();
            $table->decimal('unpaid_amount', 20, 2)->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('source')->default('expenses')->nullable();
            $table->string('debt_type')->default('Debtor')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
