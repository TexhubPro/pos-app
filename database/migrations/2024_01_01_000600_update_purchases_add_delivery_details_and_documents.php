<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('delivery_cn_volume', 12, 2)->default(0)->after('purchase_price');
            $table->decimal('delivery_cn_rate', 12, 2)->default(0)->after('delivery_cn_volume');
            $table->decimal('delivery_tj_volume', 12, 2)->default(0)->after('delivery_cn_rate');
            $table->decimal('delivery_tj_rate', 12, 2)->default(0)->after('delivery_tj_volume');
        });

        Schema::create('purchase_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name');
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_documents');

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_cn_volume',
                'delivery_cn_rate',
                'delivery_tj_volume',
                'delivery_tj_rate',
            ]);
        });
    }
};
