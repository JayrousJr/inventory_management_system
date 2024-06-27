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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('s_or_c_name')->index()->nullable();
            $table->decimal('total_amount', 20, 2)->index();
            $table->decimal('paid_amount', 20, 2)->index();
            $table->decimal('re_paid', 20, 2)->index()->nullable();
            $table->decimal('remaining_amount', 20, 2)->index();
            $table->integer('source_id')->index()->nullable();
            $table->string('source_name')->index()->nullable();
            $table->string('debt_type')->index()->nullable();
            $table->string('edited_by')->index()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
