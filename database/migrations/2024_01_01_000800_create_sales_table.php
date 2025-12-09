<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 12, 2);
            $table->integer('box_qty')->default(0);
            $table->integer('unit_qty')->default(0);
            $table->integer('total_units')->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('payment_type'); // cash, debt, mixed
            $table->decimal('cash_amount', 12, 2)->default(0);
            $table->decimal('debt_amount', 12, 2)->default(0);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
