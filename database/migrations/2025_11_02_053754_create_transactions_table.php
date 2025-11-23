<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_category_id')->constrained()->cascadeOnDelete();

            $table->enum('transaction_type', ['income', 'expense'])->index();

            $table->timestamp('transaction_date')->useCurrent();
            $table->text('description')->nullable();

            $table->unsignedInteger('amount');
            $table->unsignedTinyInteger('quantity');

            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
