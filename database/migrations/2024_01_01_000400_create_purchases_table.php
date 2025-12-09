<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('firm_id')->constrained()->cascadeOnDelete();
            $table->decimal('purchase_price', 12, 2)->default(0); // за коробку, $
            $table->decimal('delivery_cn', 12, 2)->default(0);     // доставка внутри Китая, $
            $table->decimal('delivery_tj', 12, 2)->default(0);     // доставка внутри Таджикистана, $
            $table->decimal('cost_per_unit', 12, 4)->default(0);   // себестоимость за 1 шт
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
